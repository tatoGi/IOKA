<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RentalResale;
use App\Http\Requests\Admin\RentalResaleRequest;
use App\Models\Amount;
use Illuminate\Support\Facades\Log;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use App\Services\RentalResaleService;

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
        return view('admin.rental_resale.create');
    }

    public function rentalstore(RentalResaleRequest $request)
    {
        $this->rentalResaleService->storeRentalResale($request);
        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post created successfully.');
    }

    public function rentaledit($id)
    {
        $rentalResale = $this->rentalResaleService->getRentalResaleById($id);
        return view('admin.rental_resale.edit', compact('rentalResale'));
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
        $this->rentalResaleService->removeGalleryImage($id, $request);
        return response()->json(['success' => true]);
    }

    public function getGalleryImages($id)
    {
        $images = $this->rentalResaleService->getGalleryImages($id);
        return response()->json(['images' => $images]);
    }

    public function uploadGalleryImages($id, Request $request)
    {
        $this->rentalResaleService->uploadGalleryImages($id, $request);
        return response()->json(['success' => true]);
    }
}
