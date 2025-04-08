<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffplanRequest;
use App\Models\Offplan;
use App\Services\OffplanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;

class OffplanController extends Controller
{
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
        $data = $request->validated();
        $data['slug'] = $this->generateUniqueSlug($data['slug']);

        $this->offplanService->handleFileUploads($request, $data);
        $offplan = $this->offplanService->createOffplan($data);

        return redirect()
            ->route('admin.offplan.index')
            ->with('success', 'Offplan created successfully.');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Failed to create offplan: ' . $e->getMessage());
    }
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
    $selectedLocations = $offplan->locations->pluck('id')->toArray();

    return view('admin.offplan.edit', compact('offplan', 'locations', 'selectedLocations'));
}

    public function update(StoreOffplanRequest $request, $id)
    {
        $offplan = Offplan::findOrFail($id);
        $data = $request->validated();

        // Check if the slug has changed
        if ($request->has('slug') && $request->input('slug') !== $offplan->slug) {
            // Generate a unique slug only if the slug has changed
            $data['slug'] = $this->generateUniqueSlug($data['slug']);
        } else {
            // Keep the existing slug
            $data['slug'] = $offplan->slug;
        }

        // Handle file uploads and update the offplan
        $this->offplanService->handleFileUploads($request, $data);
        $this->offplanService->updateOffplan($offplan, $data);

        return redirect()->route('admin.offplan.index')->with('success', 'Offplan updated successfully.');
    }

    public function destroy($id)
    {
        $offplan = Offplan::findOrFail($id);
        $offplan->delete();

        return redirect()->route('admin.offplan.index')->with('success', 'Offplan deleted successfully.');
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
