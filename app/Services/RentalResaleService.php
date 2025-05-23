<?php

namespace App\Services;

use App\Http\Requests\Admin\RentalResaleRequest;
use App\Http\Requests\Admin\UpdateRentalResaleRequest;
use App\Models\Amount;
use App\Models\Page;
use App\Models\RentalResale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RentalResaleService
{
    public function getRentalIndexData()
    {
        $rentalResales = RentalResale::orderBy('created_at', 'desc')->paginate(10);

        $tags = collect();
        foreach ($rentalResales as $rentalResale) {
            $tags = $tags->merge(Page::where('type_id', $rentalResale->tags)->get());
        }

        return compact('rentalResales', 'tags');
    }

    public function storeRentalResale(RentalResaleRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['slug'] = $this->generateUniqueSlug($request->input('slug'));

        // Handle QR photo upload
        if ($request->hasFile('qr_photo')) {
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        // Handle agent photo upload
        if ($request->hasFile('agent_photo')) {
            $validatedData['agent_photo'] = $request->file('agent_photo')->store('agent_photo', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];

            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }

            $validatedData['gallery_images'] = json_encode($galleryImages);
        } else {
            $validatedData['gallery_images'] = json_encode([]);
        }

        // Get location ID from the validated data
        $locationId = $validatedData['location_id'] ?? null;
        unset($validatedData['location_id']);

        // Remove amount fields for now (they go into a separate table)
        unset($validatedData['amount']);
        unset($validatedData['amount_dirhams']);

        return DB::transaction(function () use ($validatedData, $locationId, $request) {
            // Create the RentalResale record
            $rentalResale = RentalResale::create($validatedData);

            // Attach location if available
            if ($locationId) {
                $rentalResale->locations()->attach($locationId);
            }

            // Create the related Amount record
            Amount::create([
                'rental_resale_id' => $rentalResale->id,
                'amount' => $request->amount,
                'amount_dirhams' => $request->amount_dirhams,
            ]);

            return $rentalResale;
        });
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
            $rentalResale->qr_photo = '';
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

    public function uploadGalleryImages(Request $request)
    {
        $validatedData = $request->validate([
            'image' => 'required|image',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('public/gallery');
            $imageUrl = asset(str_replace('public/', 'storage/', $path));

            return [
                'success' => true,
                'image_url' => $imageUrl,
                'image_id' => uniqid(), // Replace with actual image ID if available
            ];
        }

        return ['success' => false, 'message' => 'No image uploaded'];
    }

    public function updateRentalResale(UpdateRentalResaleRequest $request, $id)
    {
        $validatedData = $request->validated();
        $rentalResale = RentalResale::findOrFail($id);

        // Handle slug update
        if ($request->has('slug') && $request->input('slug') !== $rentalResale->slug) {
            $validatedData['slug'] = $this->generateUniqueSlug($request->input('slug'));
        }

        // Handle QR photo update
        if ($request->hasFile('qr_photo')) {
            if ($rentalResale->qr_photo) {
                Storage::delete($rentalResale->qr_photo);
            }
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        // Handle agent photo update
        if ($request->hasFile('agent_photo')) {
            if ($rentalResale->agent_photo) {
                Storage::delete($rentalResale->agent_photo);
            }
            $validatedData['agent_photo'] = $request->file('agent_photo')->store('agent_photo', 'public');
        }

        // Handle gallery images update
        if ($request->hasFile('gallery_images')) {
            $galleryImages = is_array($rentalResale->gallery_images) ? $rentalResale->gallery_images : json_decode($rentalResale->gallery_images, true) ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }
            $validatedData['gallery_images'] = json_encode($galleryImages);
        }

        // Handle alt texts
        if ($request->has('alt_texts')) {
            $altTexts = $request->input('alt_texts');
            if (is_array($altTexts)) {
                // Get existing alt texts
                $existingAltTexts = json_decode($rentalResale->alt_texts, true) ?? [];

                // Handle gallery images alt texts
                if (isset($altTexts['gallery_images'])) {
                    if (is_string($altTexts['gallery_images'])) {
                        // If it's a JSON string, decode it
                        $galleryAltTexts = json_decode($altTexts['gallery_images'], true);
                        if ($galleryAltTexts) {
                            $existingAltTexts['gallery_images'] = $galleryAltTexts;
                        }
                    } else {
                        // If it's already an array, use it directly
                        $existingAltTexts['gallery_images'] = $altTexts['gallery_images'];
                    }
                }

                // Handle agent photo alt text
                if (isset($altTexts['agent_photo'])) {
                    $existingAltTexts['agent_photo'] = $altTexts['agent_photo'];
                }

                // Clean up any nested gallery_images structure
                if (isset($existingAltTexts['gallery_images']['gallery_images'])) {
                    $existingAltTexts['gallery_images'] = $existingAltTexts['gallery_images']['gallery_images'];
                }

                $validatedData['alt_texts'] = json_encode($existingAltTexts);
            }
        }

        // Remove amount fields
        unset($validatedData['amount']);
        unset($validatedData['amount_dirhams']);

        // Store location ID
        $locationId = $request->input('location_id')[0] ?? null;
        unset($validatedData['location_id']);

        return DB::transaction(function () use ($rentalResale, $validatedData, $locationId, $request) {
            // Update the RentalResale record
            $rentalResale->update($validatedData);

            // Sync location (replace old with new)
            if ($locationId) {
                $rentalResale->locations()->sync([$locationId]);
            }

            // Update the related Amount record
            $rentalResale->amount->update([
                'amount' => $request->amount,
                'amount_dirhams' => $request->amount_dirhams,
            ]);

            return $rentalResale;
        });
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
        while (RentalResale::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
