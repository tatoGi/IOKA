<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffplanRequest;
use App\Models\Offplan;
use App\Services\OffplanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OffplanController extends Controller
{
    protected $offplanService;

    public function __construct(OffplanService $offplanService)
    {
        $this->offplanService = $offplanService;
    }

    public function index()
    {
        $offplans = Offplan::all();

        return view('admin.offplan.index', compact('offplans'));
    }

    public function create()
    {
        return view('admin.offplan.create');
    }

    public function store(StoreOffplanRequest $request)
    {
        $data = $request->validated();
        $this->offplanService->handleFileUploads($request, $data);
        $this->offplanService->createOffplan($data);

        return redirect()->route('admin.offplan.index')->with('success', 'Offplan created successfully.');
    }

    public function edit($id)
    {
        $offplan = Offplan::findOrFail($id);

        return view('admin.offplan.edit', compact('offplan'));
    }

    public function update(StoreOffplanRequest $request, $id)
    {
        $offplan = Offplan::findOrFail($id);
        $data = $request->validated();
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
}
