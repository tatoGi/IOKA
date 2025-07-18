<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffplanRequest;
use App\Models\Offplan;
use App\Services\OffplanService;
use App\Traits\HandlesMetaData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;

class OffplanController extends Controller
{
    use HandlesMetaData;

    protected $offplanService;

    public function __construct(OffplanService $offplanService)
    {
        $this->offplanService = $offplanService;
    }

    public function index()
    {
        $offplans = Offplan::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.offplan.index', compact('offplans'));
    }

    public function create()
    {
        $locations = Location::all();
        return view('admin.offplan.create', compact('locations'));
    }

    public function store(StoreOffplanRequest $request)
    {
        try {
            // Check for file upload size before validation
            if ($request->hasFile('images')) {
                $maxSize = $this->getMaxUploadSizeInBytes();
                foreach ($request->file('images') as $file) {
                    if ($file->getSize() > $maxSize) {
                        return back()
                            ->withInput()
                            ->with('error', sprintf(
                                'The file "%s" is too large. Maximum allowed size is %s.',
                                $file->getClientOriginalName(),
                                $this->formatBytes($maxSize)
                            ));
                    }
                }
            }

            $data = $request->validate(array_merge(
                $request->rules(),
                $this->getMetadataValidationRules()
            ));

            $data['slug'] = $this->generateUniqueSlug($data['slug']);

            // Handle agent languages
            if ($request->has('agent_languages')) {
                $data['agent_languages'] = array_filter($request->input('agent_languages'));
            }

            $this->offplanService->handleFileUploads($request, $data);
            $offplan = $this->offplanService->createOffplan($data);

            // Handle metadata
            $this->handleMetadata($request, $offplan);

            return redirect()
                ->route('admin.offplan.index')
                ->with('success', 'Offplan created successfully.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create offplan: ' . $e->getMessage());
        }
    }

    /**
     * Get the maximum upload size in bytes
     */
    private function getMaxUploadSizeInBytes(): int
    {
        $postMaxSize = $this->parseSize(ini_get('post_max_size'));
        $uploadMaxSize = $this->parseSize(ini_get('upload_max_filesize'));
        
        // Return the smaller of the two values
        return min($postMaxSize, $uploadMaxSize);
    }
    
    /**
     * Parse size from php.ini string to bytes
     */
    private function parseSize(string $size): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = (float) preg_replace('/[^0-9\\.]/', '', $size);
        
        if ($unit) {
            $size = round($size * (1024 ** stripos('bkmgtpezy', $unit[0])));
        }
        
        return (int) $size;
    }
    
    /**
     * Format bytes to a human-readable format
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    private function generateUniqueSlug(string $slug): string
    {
        // Replace spaces with dashes
        $slug = str_replace(' ', '-', $slug);

        // Alternatively, use Laravel's helper for a cleaner slug
        // $slug = \Illuminate\Support\Str::slug($slug);

        $originalSlug = $slug;
        $counter = 1;

        // Ensure the slug is unique
        while (Offplan::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

   public function edit(Offplan $offplan)
{
    $locations = Location::all(); // Get all available locations
    $selectedLocation = $offplan->locations->first()?->id;

    return view('admin.offplan.edit', compact('offplan', 'locations', 'selectedLocation'));
}

    public function update(StoreOffplanRequest $request, $id)
    {
        try {
            $offplan = Offplan::findOrFail($id);
            
            // Check for file upload size before validation
            if ($request->hasFile('images')) {
                $maxSize = $this->getMaxUploadSizeInBytes();
                foreach ($request->file('images') as $file) {
                    if ($file->getSize() > $maxSize) {
                        return back()
                            ->withInput()
                            ->with('error', sprintf(
                                'The file "%s" is too large. Maximum allowed size is %s.',
                                $file->getClientOriginalName(),
                                $this->formatBytes($maxSize)
                            ));
                    }
                }
            }
            
            $data = $request->validate(array_merge(
                $request->rules(),
                $this->getMetadataValidationRules()
            ));

            // Check if the slug has changed
            if ($request->has('slug') && $request->input('slug') !== $offplan->slug) {
                // Generate a unique slug only if the slug has changed
                $data['slug'] = $this->generateUniqueSlug($data['slug']);
            } else {
                // Keep the existing slug
                $data['slug'] = $offplan->slug;
            }

            // Handle agent languages
            if ($request->has('agent_languages')) {
                $data['agent_languages'] = array_filter($request->input('agent_languages'));
            }

            // Handle file uploads and update the offplan
            $this->offplanService->handleFileUploads($request, $data);
            $this->offplanService->updateOffplan($offplan, $data);

            // Handle metadata
            $this->handleMetadata($request, $offplan);

            return redirect()->back()->with('success', 'Offplan updated successfully.');
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update offplan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $offplan = Offplan::findOrFail($id);

        // Delete metadata images if they exist
        if ($offplan->metadata?->og_image) {
            Storage::disk('public')->delete($offplan->metadata->og_image);
        }
        if ($offplan->metadata?->twitter_image) {
            Storage::disk('public')->delete($offplan->metadata->twitter_image);
        }

        $offplan->delete();

        return redirect()->route('admin.offplan.index')->with('success', 'Offplan deleted successfully.');
    }

    /**
     * Delete the OG image for the specified offplan.
     */
    public function deleteOgImage(Offplan $offplan)
    {
        if ($offplan->metadata && $offplan->metadata->og_image) {
            Storage::disk('public')->delete($offplan->metadata->og_image);
            $offplan->metadata->update(['og_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No OG image found.'], 404);
    }

    /**
     * Delete the Twitter image for the specified offplan.
     */
    public function deleteTwitterImage(Offplan $offplan)
    {
        if ($offplan->metadata && $offplan->metadata->twitter_image) {
            Storage::disk('public')->delete($offplan->metadata->twitter_image);
            $offplan->metadata->update(['twitter_image' => null]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No Twitter image found.'], 404);
    }

    public function exteriorGallery()
    {
        $exteriorPhotos = Storage::disk('public')->files('offplan_exteriors');

        return view('admin.offplan.exterior_gallery', compact('exteriorPhotos'));
    }

    public function storeExteriorGallery(Request $request)
    {
        if ($request->hasFile('exterior_gallery')) {
            foreach ($request->file('exterior_gallery') as $file) {
                $file->store('offplan_exteriors', 'public');
            }
        }

        return redirect()->route('admin.offplan.exterior_gallery')->with('success', 'Exterior photos uploaded successfully.');
    }

    public function interiorGallery()
    {
        $interiorPhotos = Storage::disk('public')->files('offplan_interiors');

        return view('admin.offplan.interior_gallery', compact('interiorPhotos'));
    }

    public function storeInteriorGallery(Request $request)
    {
        if ($request->hasFile('interior_gallery')) {
            foreach ($request->file('interior_gallery') as $file) {
                $file->store('offplan_interiors', 'public');
            }
        }

        return redirect()->route('admin.offplan.interior_gallery')->with('success', 'Interior photos uploaded successfully.');
    }

    public function deleteImage(Request $request)
    {

        $type = $request->input('type');
        $path = $request->input('path');

        if ($type && $path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);

            return response()->json(['success' => 'Image deleted successfully.']);
        }

        return response()->json(['error' => 'Image not found.'], 404);
    }
}
