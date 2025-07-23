<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffplanRequest;
use App\Models\Location;
use App\Models\Offplan;
use App\Services\OffplanService;
use App\Traits\HandlesMetaData;
use Illuminate\Http\Request;

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

            // Process amenities and their icons
            if ($request->has('amenities') && is_array($request->input('amenities'))) {
                $amenities = $request->input('amenities');
                $amenitiesIcons = [];

                foreach ($amenities as $index => $amenity) {
                    if (empty($amenity)) continue; // Skip empty amenity names

                    $iconData = [
                        'name' => $amenity,
                        'icon' => ''
                    ];

                    // If there's a new icon uploaded, process it
                    if ($request->hasFile('amenities_icon.' . $index)) {
                        $file = $request->file('amenities_icon.' . $index);
                        if ($file && $file->isValid()) {
                            $iconPath = $file->store('amenities_icons', 'public');
                            $iconData['icon'] = $iconPath;
                        }
                    }

                    $amenitiesIcons[] = $iconData;
                }

                $data['amenities_icons'] = $amenitiesIcons;
                $data['amenities'] = array_values(array_filter($amenities));
            } else {
                $data['amenities'] = [];
                $data['amenities_icons'] = [];
            }

            $this->offplanService->handleFileUploads($request, $data);
            $offplan = $this->offplanService->createOffplan($data);

            // Handle metadata
            $this->handleMetadata($request, $offplan);

            return redirect()
                ->route('admin.offplan.index')
                ->with('success', 'Offplan created successfully.');

        } catch (\Exception $e) {
            // Clean up any uploaded files if there was an error
            if (isset($data) && isset($data['amenities_icons'])) {
                foreach ($data['amenities_icons'] as $icon) {
                    if (!empty($icon['icon'])) {
                        Storage::disk('public')->delete($icon['icon']);
                    }
                }
            }

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
          
            // Process features
            $features = [];
            if ($request->has('features') && is_array($request->input('features'))) {
                $features = array_values(array_filter($request->input('features'), function($feature) {
                    return !empty(trim($feature));
                }));
            }
            $data['features'] = $features;

            // Process amenities with their icons
            $amenities = [];
            $existingIcons = $request->input('existing_amenities_icon', []);
            $deletedIcons = $request->input('deleted_amenities_icon', []);
            $removedAmenities = $request->input('removed_amenities', []);
            
            // Process deleted icons first
            if (!empty($deletedIcons)) {
                foreach ($deletedIcons as $deletedIcon) {
                    if (!empty($deletedIcon) && Storage::disk('public')->exists($deletedIcon)) {
                        Storage::disk('public')->delete($deletedIcon);
                    }
                }
            }
            
            // Get all amenity inputs and their corresponding icons
            $amenityInputs = $request->input('amenities', []);
            $amenityIcons = $request->file('amenities_icon', []);
            
            // Track processed amenity names to avoid duplicates
            $processedAmenities = [];
            
            // Process existing amenities first
            $existingAmenities = collect($offplan->amenities ?? []);
            
            foreach ($amenityInputs as $index => $amenityName) {
                if (empty($amenityName)) continue;
                
                // Skip if we've already processed this amenity (by name)
                if (in_array($amenityName, $processedAmenities)) {
                    continue;
                }
                
                $iconPath = '';
                $isExisting = false;
                
                // Check if this is an existing amenity being updated
                if (is_numeric($index) && isset($existingIcons[$index])) {
                    $iconPath = $existingIcons[$index];
                    $isExisting = true;
                    
                    // If this icon was marked for deletion, skip it
                    if (in_array($iconPath, $deletedIcons)) {
                        $iconPath = '';
                    }
                }
                
                // Handle file upload if exists
                if (isset($amenityIcons[$index]) && $amenityIcons[$index]->isValid()) {
                    // Delete old icon if it exists and not already marked for deletion
                    if (!empty($iconPath) && !in_array($iconPath, $deletedIcons) && Storage::disk('public')->exists($iconPath)) {
                        Storage::disk('public')->delete($iconPath);
                    }
                    $iconPath = $amenityIcons[$index]->store('amenities_icons', 'public');
                }
                
                // Only add if either name or icon is not empty
                if (!empty($amenityName) || !empty($iconPath)) {
                    $amenities[] = [
                        'name' => $amenityName,
                        'icon' => $iconPath
                    ];
                    $processedAmenities[] = $amenityName;
                }
            }
            
            // Clean up deleted icons
            $deletedIcons = array_unique($deletedIcons);
            foreach ($deletedIcons as $deletedIcon) {
                if (!empty($deletedIcon)) {
                    Storage::disk('public')->delete($deletedIcon);
                }
            }
            
            // Filter out empty amenities and ensure proper format
            $data['amenities'] = array_values(array_filter($amenities, function($amenity) {
                return !empty($amenity['name']) || !empty($amenity['icon']);
            }));
            
            // Ensure features is properly formatted as JSON
            $data['features'] = $features;
            
            // Debug log
            Log::info('Processed data before update:', [
                'features' => $data['features'],
                'amenities' => $data['amenities']
            ]);
            

            // Handle file uploads and update the offplan
            $this->offplanService->handleFileUploads($request, $data);
            $this->offplanService->updateOffplan($offplan, $data);

            // Handle metadata
            $this->handleMetadata($request, $offplan);

            return redirect()->back()->with('success', 'Offplan updated successfully.');

        } catch (\Exception $e) {
            // Clean up any uploaded files if there was an error
            if (isset($data) && isset($data['amenities_icons'])) {
                foreach ($data['amenities_icons'] as $icon) {
                    if (!empty($icon['icon']) && (!isset($icon['is_existing']) || !$icon['is_existing'])) {
                        Storage::disk('public')->delete($icon['icon']);
                    }
                }
            }

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

    public function deleteImage(Request $request, Offplan $offplan)
    {
        try {
            $type = $request->input('type');
            $path = $request->input('path');
           
            if (!$type || !$path) {
                return response()->json(['error' => 'Missing required parameters'], 400);
            }

            // Delete the file from storage
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            // Update the offplan record to remove the reference to the deleted image
            if ($type === 'exterior_gallery') {
                $exteriorGallery = $offplan->exterior_gallery ?? [];
                $exteriorGallery = array_filter($exteriorGallery, function($item) use ($path) {
                    return $item !== $path;
                });
                $offplan->exterior_gallery = array_values($exteriorGallery); // Reindex array
            } elseif ($type === 'interior_gallery') {
                $interiorGallery = $offplan->interior_gallery ?? [];
                $interiorGallery = array_filter($interiorGallery, function($item) use ($path) {
                    return $item !== $path;
                });
                $offplan->interior_gallery = array_values($interiorGallery); // Reindex array
            }
           
            $offplan->save();

            return response()->json(['success' => 'Image deleted successfully.']);

        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['error' => 'Error deleting image: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Remove an amenity icon via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offplan  $offplan
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAmenityIcon(Request $request, Offplan $offplan)
    {
        $request->validate([
            'icon_path' => 'required|string',
            'amenity_index' => 'required|integer',
        ]);
        
        $iconPath = $request->input('icon_path');
        $amenityIndex = $request->input('amenity_index');
        
        try {
            // Get current amenities
            $amenities = $offplan->amenities ?? [];
            
            // Check if the amenity exists at the given index
            if (isset($amenities[$amenityIndex])) {
                // Remove the icon path from the amenity
                $amenities[$amenityIndex]['icon'] = '';
                
                // Save the updated amenities
                $offplan->amenities = $amenities;
                $offplan->save();
                
                // Delete the icon file from storage
                if (!empty($iconPath) && Storage::disk('public')->exists($iconPath)) {
                    Storage::disk('public')->delete($iconPath);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Icon removed successfully',
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Amenity not found',
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Error removing amenity icon: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove icon: ' . $e->getMessage(),
            ], 500);
        }
    }
}
