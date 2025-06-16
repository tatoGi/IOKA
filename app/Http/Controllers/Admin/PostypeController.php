<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RentalResaleRequest;
use App\Http\Requests\Admin\UpdateRentalResaleRequest;
use App\Models\Location;
use App\Services\RentalResaleService;
use Illuminate\Http\Request;
use App\Models\RentalResale;
use App\Traits\HandlesMetaData;
use Illuminate\Support\Facades\Storage;

class PostypeController extends Controller
{
    use HandlesMetaData;

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
        try {
            $rentalResale = $this->rentalResaleService->storeRentalResale($request);

            return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental/Resale created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating Rental/Resale: ' . $e->getMessage());
        }
    }

    public function rentaledit($id)
    {
        $rentalResale = $this->rentalResaleService->getRentalResaleById($id);
        $locations = Location::all(); // Get all available locations
        $selectedLocations = $rentalResale->locations->pluck('id')->toArray();

        return view('admin.rental_resale.edit', compact('rentalResale', 'selectedLocations', 'locations'));
    }

    public function rentalupdate(UpdateRentalResaleRequest $request, RentalResale $postype)
    {
        try {
            $this->rentalResaleService->updateRentalResale($request, $postype->id);

            return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental/Resale updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating Rental/Resale: ' . $e->getMessage())->withInput();
        }
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

    public function deleteOgImage(RentalResale $postype)
    {
        try {
            if ($postype->metadata && $postype->metadata->og_image) {
                Storage::disk('public')->delete($postype->metadata->og_image);
                $postype->metadata->update(['og_image' => null]);
            }
            return response()->json(['success' => true, 'message' => 'OG image removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing OG image: ' . $e->getMessage()], 500);
        }
    }

    public function deleteTwitterImage(RentalResale $postype)
    {
        try {
            if ($postype->metadata && $postype->metadata->twitter_image) {
                Storage::disk('public')->delete($postype->metadata->twitter_image);
                $postype->metadata->update(['twitter_image' => null]);
            }
            return response()->json(['success' => true, 'message' => 'Twitter image removed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error removing Twitter image: ' . $e->getMessage()], 500);
        }
    }

}
