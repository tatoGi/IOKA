<?php

namespace App\Services;

use App\Models\RentalResale;
use App\Models\Amount;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RentalResaleService
{
    public function getRentalIndexData()
    {
        $rentalResales = RentalResale::all();
        $tags = collect();
        foreach ($rentalResales as $rentalResale) {
            $tags = $tags->merge(Page::where('type_id', $rentalResale->tags)->get());
        }
        return compact('rentalResales', 'tags');
    }

    public function storeRentalResale(Request $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('qr_photo')) {
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }
            $validatedData['gallery_images'] = json_encode($galleryImages);
        }

        unset($validatedData['amount']);
        unset($validatedData['amount_dirhams']);

        $rentalResale = RentalResale::create($validatedData);
        Amount::create([
            'rental_resale_id' => $rentalResale->id,
            'amount' => $request->amount,
            'amount_dirhams' => $request->amount_dirhams,
        ]);
    }

    public function getRentalResaleById($id)
    {
        return RentalResale::with('amount')->findOrFail($id);
    }

    public function destroyRentalResale($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $rentalResale->delete();
    }

    public function removeQrPhoto($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        if ($rentalResale->qr_photo) {
            Storage::delete($rentalResale->qr_photo);
            $rentalResale->qr_photo = null;
            $rentalResale->save();
        }
    }

    public function removeGalleryImage($id, Request $request)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $galleryImages = is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true);
        if (($key = array_search($request->image, $galleryImages)) !== false) {
            Storage::delete($request->image);
            unset($galleryImages[$key]);
            $rentalResale->gallery_images = json_encode(array_values($galleryImages));
            $rentalResale->save();
        }
    }

    public function getGalleryImages($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        return is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true);
    }

    public function uploadGalleryImages($id, Request $request)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $galleryImages = is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }
            $rentalResale->gallery_images = json_encode($galleryImages);
            $rentalResale->save();
        }
    }
}
