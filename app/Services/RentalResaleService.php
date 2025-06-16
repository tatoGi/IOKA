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
use Illuminate\Support\Str;

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
        $validatedData['slug'] = $this->generateUniqueSlug($request->input('title'));

        // Handle QR photo upload
        if ($request->hasFile('qr_photo')) {
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        // Handle agent photo upload
        if ($request->hasFile('agent_photo')) {
            $agentPhotos = [];
            foreach ($request->file('agent_photo') as $photo) {
                $agentPhotos[] = $photo->store('agent_photo', 'public');
            }
            $validatedData['agent_photo'] = json_encode($agentPhotos);
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

            // Handle metadata creation
            if ($request->has('metadata')) {
                $this->handleMetadataCreation($rentalResale, $request);
            }

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
            // Delete existing agent photos
            if ($rentalResale->agent_photo) {
                $existingPhotos = is_array($rentalResale->agent_photo) ? $rentalResale->agent_photo : json_decode($rentalResale->agent_photo, true);
                if (is_array($existingPhotos)) {
                    foreach ($existingPhotos as $photo) {
                        Storage::delete($photo);
                    }
                }
            }
            $agentPhotos = [];
            foreach ($request->file('agent_photo') as $photo) {
                $agentPhotos[] = $photo->store('agent_photo', 'public');
            }
            $validatedData['agent_photo'] = json_encode($agentPhotos);
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
                $existingAltTexts = is_array($rentalResale->alt_texts) ? $rentalResale->alt_texts : json_decode($rentalResale->alt_texts, true) ?? [];

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

            // Handle metadata update
            if ($request->has('metadata')) {
                $this->handleMetadataUpdate($rentalResale, $request);
            }

            return $rentalResale;
        });
    }

    private function generateUniqueSlug(string $slug): string
    {
        // Use Laravel's helper for a cleaner slug
        $slug = Str::slug($slug);

        $originalSlug = $slug;
        $counter = 1;

        // Ensure the slug is unique
        while (RentalResale::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Handle metadata creation for a rental resale
     *
     * @param RentalResale $rentalResale
     * @param Request $request
     * @return void
     */
    private function handleMetadataCreation(RentalResale $rentalResale, Request $request)
    {
        $metadata = $request->input('metadata');

        // Handle metadata file uploads
        if ($request->hasFile('metadata.og_image')) {
            $metadata['og_image'] = $request->file('metadata.og_image')->store('meta-images/og', 'public');
        } elseif ($request->hasFile('og_image')) {
            // Fallback for direct og_image upload
            $metadata['og_image'] = $request->file('og_image')->store('meta-images/og', 'public');
        }

        if ($request->hasFile('metadata.twitter_image')) {
            $metadata['twitter_image'] = $request->file('metadata.twitter_image')->store('meta-images/twitter', 'public');
        } elseif ($request->hasFile('twitter_image')) {
            // Fallback for direct twitter_image upload
            $metadata['twitter_image'] = $request->file('twitter_image')->store('meta-images/twitter', 'public');
        }

        // Ensure we have a metadata record
        if (!$rentalResale->metadata) {
            $rentalResale->metadata()->create($metadata);
        } else {
            $rentalResale->updateMetadata($metadata);
        }
    }

    /**
     * Handle metadata update for a rental resale
     *
     * @param RentalResale $rentalResale
     * @param Request $request
     * @return void
     */
    private function handleMetadataUpdate(RentalResale $rentalResale, Request $request)
    {
        $metadata = $request->input('metadata') ?? [];

        // Handle metadata file uploads
        if ($request->hasFile('metadata.og_image')) {
            // Delete old OG image if it exists
            if ($rentalResale->metadata?->og_image) {
                Storage::disk('public')->delete($rentalResale->metadata->og_image);
            }
            $metadata['og_image'] = $request->file('metadata.og_image')->store('meta-images/og', 'public');
        } elseif ($request->hasFile('og_image')) {
            // Fallback for direct og_image upload
            if ($rentalResale->metadata?->og_image) {
                Storage::disk('public')->delete($rentalResale->metadata->og_image);
            }
            $metadata['og_image'] = $request->file('og_image')->store('meta-images/og', 'public');
        }

        if ($request->hasFile('metadata.twitter_image')) {
            // Delete old Twitter image if it exists
            if ($rentalResale->metadata?->twitter_image) {
                Storage::disk('public')->delete($rentalResale->metadata->twitter_image);
            }
            $metadata['twitter_image'] = $request->file('metadata.twitter_image')->store('meta-images/twitter', 'public');
        } elseif ($request->hasFile('twitter_image')) {
            // Fallback for direct twitter_image upload
            if ($rentalResale->metadata?->twitter_image) {
                Storage::disk('public')->delete($rentalResale->metadata->twitter_image);
            }
            $metadata['twitter_image'] = $request->file('twitter_image')->store('meta-images/twitter', 'public');
        }

        // Handle image removal
        if (isset($metadata['remove_og_image']) && $metadata['remove_og_image']) {
            if ($rentalResale->metadata?->og_image) {
                Storage::disk('public')->delete($rentalResale->metadata->og_image);
            }
            $metadata['og_image'] = null;
        }

        if (isset($metadata['remove_twitter_image']) && $metadata['remove_twitter_image']) {
            if ($rentalResale->metadata?->twitter_image) {
                Storage::disk('public')->delete($rentalResale->metadata->twitter_image);
            }
            $metadata['twitter_image'] = null;
        }

        // Remove the removal flags from metadata
        unset($metadata['remove_og_image'], $metadata['remove_twitter_image']);

        // Ensure we have a metadata record
        if (!$rentalResale->metadata) {
            $rentalResale->metadata()->create($metadata);
        } else {
            $rentalResale->updateMetadata($metadata);
        }
    }
}
