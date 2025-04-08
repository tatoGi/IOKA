<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RentalResaleRequest;
use App\Http\Requests\Admin\UpdateRentalResaleRequest;
use App\Models\Location;
use App\Services\RentalResaleService;
use Illuminate\Http\Request;

class PostypeController extends Controller
{
    protected $rentalResaleService;

    public function __construct(RentalResaleService $rentalResaleService)
    {
        $this->rentalResaleService = $rentalResaleService;
    }

    public function rentalindex()
    {
        $data = $this->rentalResaleService->getRentalIndexData();

        return view('admin.rental_resale.index', $data);
    }

    public function rentalcreate()
    {
        $locations = Location::all();

        return view('admin.rental_resale.create', compact('locations'));
    }

    public function rentalstore(RentalResaleRequest $request)
    {
        $this->rentalResaleService->storeRentalResale($request);

        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post created successfully.');
    }

    public function rentaledit($id)
    {
        $rentalResale = $this->rentalResaleService->getRentalResaleById($id);
        $locations = Location::all(); // Get all available locations
        $selectedLocations = $rentalResale->locations->pluck('id')->toArray();

        return view('admin.rental_resale.edit', compact('rentalResale', 'selectedLocations', 'locations'));
    }

    public function rentalupdate(UpdateRentalResaleRequest $request, $id)
    {

        $this->rentalResaleService->updateRentalResale($request, $id);

        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post updated successfully.');
    }

    public function rentaldestroy($id)
    {
        $this->rentalResaleService->destroyRentalResale($id);

        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post deleted successfully.');
    }

    public function removeQrPhoto($id)
    {
        $this->rentalResaleService->removeQrPhoto($id);

        return response()->json(['success' => true]);
    }

    public function removeGalleryImage($id, Request $request)
    {
        // dd($id, $request->all());
        $this->rentalResaleService->removeGalleryImage($id, $request);

        return response()->json(['success' => true]);
    }

    public function getGalleryImages($id)
    {
        $images = $this->rentalResaleService->getGalleryImages($id);

        return response()->json(['images' => $images]);
    }

    public function uploadGalleryImages(Request $request)
    {
        $result = $this->rentalResaleService->uploadGalleryImages($request);

        return response()->json($result);
    }

}
