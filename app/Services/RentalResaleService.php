<?php

namespace App\Services;

use App\Http\Requests\Admin\RentalResaleRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\UpdateRentalResaleRequest;
use App\Models\Amount;
use App\Models\Location;
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

    /**
     * Save a base64 encoded image to storage
     *
     * @param string $base64Image
     * @param string $directory
     * @return string
     */
    protected function saveBase64Image($base64Image, $directory)
    {
        // Remove the data:image/...;base64, part
        $image = explode(';base64,', $base64Image);
        $imageType = explode('image/', $image[0]);
        $imageType = $imageType[1] ?? 'png';
        
        // Decode the base64 data
        $imageData = base64_decode($image[1]);
        
        // Generate a unique filename
        $filename = uniqid() . '.' . $imageType;
        $path = $directory . '/' . $filename;
        
        // Save the file to storage
        Storage::disk('public')->put($path, $imageData);
        
        return $path;
    }

    public function storeRentalResale(RentalResaleRequest $request)
    {
        $validatedData = $request->validated();
        
        // Generate unique slug
        $validatedData['slug'] = $this->generateUniqueSlug($request->input('title'));

        // Handle QR photo upload
        if ($request->hasFile('qr_photo')) {
            $validatedData['qr_photo'] = $request->file('qr_photo')->store('qr_photos', 'public');
        }

        // Handle agent photo upload (single file, not array)
        if ($request->hasFile('agent_photo')) {
            $validatedData['agent_photo'] = $request->file('agent_photo')->store('agent_photos', 'public');
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('gallery_images', 'public');
            }
            $validatedData['gallery_images'] = $galleryImages; // Let the model handle JSON encoding
        }

        // Handle mobile gallery images upload
        $mobileGalleryImages = [];
        if ($request->hasFile('mobile_gallery_images')) {
            foreach ($request->file('mobile_gallery_images') as $image) {
                $mobileGalleryImages[] = $image->store('mobile_gallery_images', 'public');
            }
            $validatedData['mobile_gallery_images'] = $mobileGalleryImages; // Let the model handle JSON encoding
        }

        // Handle mobile agent photo (base64 string)
        if ($request->filled('mobile_agent_photo_compressed')) {
            $validatedData['mobile_agent_photo'] = $this->saveBase64Image($request->input('mobile_agent_photo_compressed'), 'mobile_agent_photos');
        }

        // Handle mobile QR photo (base64 string)
        if ($request->filled('qr_mobile_photo_compressed')) {
            $validatedData['mobile_qr_photo'] = $this->saveBase64Image($request->input('qr_mobile_photo_compressed'), 'mobile_qr_photos');
        }

        // Get location IDs (handling both single and multiple locations)
        $locationIds = [];
        if ($request->has('location_ids')) {
            $locationIds = array_filter(
                (array)$request->input('location_ids'),
                function($id) {
                    return !empty($id) && is_numeric($id);
                }
            );
        } elseif ($request->has('location_id') && !empty($request->input('location_id'))) {
            $locationId = (int)$request->input('location_id');
            if ($locationId > 0) {
                $locationIds = [$locationId];
            }
        }

        // Handle mobile gallery images
        if ($request->hasFile('mobile_gallery_images')) {
            $mobileGalleryImages = [];
            foreach ($request->file('mobile_gallery_images') as $image) {
                $path = $image->store('mobile_gallery', 'public');
                if ($path) {
                    $mobileGalleryImages[] = $path;
                }
            }
            $validatedData['mobile_gallery_images'] = $mobileGalleryImages;
        }

        // Handle JSON fields
        $jsonFields = ['details', 'amenities', 'addresses', 'tags', 'languages', 'alt_texts', 'mobile_gallery_images'];
        foreach ($jsonFields as $field) {
            if (isset($validatedData[$field]) && is_string($validatedData[$field])) {
                $validatedData[$field] = json_decode($validatedData[$field], true);
            }
        }

        // Remove fields that shouldn't go directly to the model
        $amount = $validatedData['amount'] ?? null;
        $amountDirhams = $validatedData['amount_dirhams'] ?? null;
        unset(
            $validatedData['amount'],
            $validatedData['amount_dirhams'],
            $validatedData['location_id'],
            $validatedData['location_ids'],
            $validatedData['mobile_agent_photo_compressed'],
            $validatedData['qr_mobile_photo_compressed']
        );

        // Start database transaction
        return DB::transaction(function () use ($validatedData, $locationIds, $amount, $amountDirhams, $request) {
            try {
                // Create the RentalResale record
                $rentalResale = RentalResale::create($validatedData);

                // Attach locations if we have valid location IDs
                if (!empty($locationIds) && is_array($locationIds)) {
                    // Ensure all location IDs are integers
                    $locationIds = array_map('intval', array_filter($locationIds, 'is_numeric'));
                    
                    // Only proceed if we have valid location IDs
                    if (!empty($locationIds)) {
                        // Check if all locations exist
                        $existingLocations = \App\Models\Location::whereIn('id', $locationIds)->pluck('id')->toArray();
                        $missingLocations = array_diff($locationIds, $existingLocations);
                        
                        // Log any missing locations
                        if (!empty($missingLocations)) {
                            Log::warning('Attempted to sync non-existent location IDs: ' . implode(', ', $missingLocations));
                        }
                        
                        // Only sync locations that exist
                        $validLocationIds = array_intersect($locationIds, $existingLocations);
                        
                        if (!empty($validLocationIds)) {
                            $rentalResale->locations()->sync($validLocationIds);
                        } else {
                            Log::warning('No valid locations found to sync for rental resale ID: ' . $rentalResale->id);
                        }
                    }
                }

                // Create the related Amount record
                if ($amount !== null) {
                    Amount::create([
                        'rental_resale_id' => $rentalResale->id,
                        'amount' => $amount,
                        'amount_dirhams' => $amountDirhams,
                    ]);
                }

                // Handle metadata creation
                if ($request->has('metadata')) {
                    $this->handleMetadataCreation($rentalResale, $request);
                }

                return $rentalResale;
            } catch (\Exception $e) {
                Log::error('Error creating rental resale: ' . $e->getMessage());
                throw $e;
            }
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

    public function removeAgentPhoto($id)
    {
        $rentalResale = RentalResale::findOrFail($id);
        if ($rentalResale->agent_photo) {
            Storage::delete($rentalResale->agent_photo);
            $rentalResale->agent_photo = null;
            $rentalResale->save();
        }
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

    public function removeMobilePhoto($id, Request $request)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $photoPath = $request->input('photo');
        
        // Get the current mobile upload photos
        $mobileUploadPhotos = is_array($rentalResale->mobile_upload_photos) 
            ? $rentalResale->mobile_upload_photos 
            : json_decode($rentalResale->mobile_upload_photos, true) ?? [];
        
        // Find and remove the photo from the array
        $updatedPhotos = array_filter($mobileUploadPhotos, function($photo) use ($photoPath) {
            return $photo !== $photoPath;
        });
        
        // Delete the photo file from storage
        if (Storage::disk('public')->exists($photoPath)) {
            Storage::disk('public')->delete($photoPath);
        }
        
        // Update the model with the new photos array
        $rentalResale->mobile_upload_photos = array_values($updatedPhotos); // Reindex the array
        $rentalResale->save();
        
        return true;
    }

    public function removeMobileGalleryImage($id, Request $request)
    {
        $rentalResale = RentalResale::findOrFail($id);
        $imagePath = $request->input('image');
        
        // Get the current mobile gallery images
        $mobileGalleryImages = is_array($rentalResale->mobile_gallery_images) 
            ? $rentalResale->mobile_gallery_images 
            : (json_decode($rentalResale->mobile_gallery_images, true) ?? []);
        
        // Find and remove the image from the array
        $updatedImages = array_filter($mobileGalleryImages, function($image) use ($imagePath) {
            return $image !== $imagePath;
        });
        
        // Delete the image file from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
        
        // Update the model with the new images array
        $rentalResale->mobile_gallery_images = array_values($updatedImages); // Reindex the array
        $rentalResale->save();
        
        return true;
    }

    public function updateRentalResale(UpdateRentalResaleRequest $request, $id)
    {
        $validatedData = $request->validated();
        $rentalResale = RentalResale::findOrFail($id);

        // Handle the 'top' checkbox value.
        $validatedData['top'] = $request->has('top');

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
            $validatedData['gallery_images'] = $galleryImages; // No need to json_encode as it's handled by the model cast
        }
        
        // Handle mobile gallery images update
        if ($request->hasFile('mobile_gallery_images')) {
            $mobileGalleryImages = $rentalResale->mobile_gallery_images ?? [];
            foreach ($request->file('mobile_gallery_images') as $image) {
                $mobileGalleryImages[] = $image->store('mobile_gallery', 'public');
            }
            $validatedData['mobile_gallery_images'] = $mobileGalleryImages;
        }
        
        // Handle removed mobile gallery images
        if ($request->has('removed_mobile_gallery_images') && is_array($request->input('removed_mobile_gallery_images'))) {
            $currentImages = $rentalResale->mobile_gallery_images ?? [];
            $removedImages = $request->input('removed_mobile_gallery_images');
            
            // Filter out removed images
            $updatedImages = array_filter($currentImages, function($image) use ($removedImages) {
                return !in_array($image, $removedImages);
            });
            
            // Delete the removed image files
            foreach ($removedImages as $removedImage) {
                if (Storage::disk('public')->exists($removedImage)) {
                    Storage::disk('public')->delete($removedImage);
                }
            }
            
            $validatedData['mobile_gallery_images'] = array_values($updatedImages);
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

        // Handle languages array
        if ($request->has('languages')) {
            $languagesInput = $request->input('languages');
            $languages = collect($languagesInput)
                ->pluck('language')
                ->filter()
                ->values()
                ->all();
            $validatedData['languages'] = $languages;
        }

        // Remove amount fields
        unset($validatedData['amount']);
        unset($validatedData['amount_dirhams']);

        // Store location ID
        $locationId = $request->input('location_id')[0] ?? null;
        unset($validatedData['location_id']);

        // Handle mobile_agent_photo_compressed (base64 string)
        if ($request->filled('mobile_agent_photo_compressed')) {
            $validatedData['mobile_agent_photo'] = $request->input('mobile_agent_photo_compressed');
        }

        // Handle qr_mobile_photo_compressed (base64 string)
        if ($request->filled('qr_mobile_photo_compressed')) {
            $validatedData['mobile_qr_photo'] = $request->input('qr_mobile_photo_compressed');
        }
        
        // Handle mobile_upload_photos
        if ($request->has('mobile_upload_photos') && is_array($request->input('mobile_upload_photos'))) {
            $validatedData['mobile_upload_photos'] = array_values(array_filter($request->input('mobile_upload_photos')));
        }

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
