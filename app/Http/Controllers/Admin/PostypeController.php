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

class PostypeController extends Controller
{

    public function rentalindex()
    {
        $rentalResales = RentalResale::all();
        $tags = collect();
        foreach ($rentalResales as $rentalResale) {
            $tags = $tags->merge(Page::where('type_id', $rentalResale->tags)->get());
        }
        return view('admin.rental_resale.index', compact('rentalResales', 'tags'));
    }
    public function rentalcreate(){
        return view('admin.rental_resale.create');
    }

    public function rentalstore(RentalResaleRequest $request)
    {
        $validatedData = $request->validated();

        // Handle file upload
        if ($request->hasFile('qr_photo')) {
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }
            $validatedData['gallery_images'] = json_encode($galleryImages);
        }

        // Remove amount and amount_dirhams from validated data
        unset($validatedData['amount']);
        unset($validatedData['amount_dirhams']);

        $rentalResale = RentalResale::create($validatedData);
        Amount::create([
            'rental_resale_id' => $rentalResale->id,
            'amount' => $request->amount,
            'amount_dirhams' => $request->amount_dirhams,
        ]);

        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post created successfully.');
    }

    public function rentaledit($id)
    {
        $rentalResale = RentalResale::with('amount')->findOrFail($id);

        return view('admin.rental_resale.edit', compact('rentalResale'));
    }

    public function rentaldestroy($id)
    {
        $rentalResale = RentalResale::findOrFail($id);

        $rentalResale->delete();

        return redirect()->route('admin.postypes.rental.index')->with('success', 'Rental Resale Post deleted successfully.');
    }
    public function removeQrPhoto($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        if ($rentalResale->qr_photo) {
            Storage::delete($rentalResale->qr_photo);
            $rentalResale->qr_photo = null;
            $rentalResale->save();
        }
        return response()->json(['success' => true]);
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
        return response()->json(['success' => true]);
    }

    public function getGalleryImages($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $galleryImages = is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true);
        return response()->json(['images' => $galleryImages]);
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

        return response()->json(['success' => true]);
    }
}
