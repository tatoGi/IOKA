<?php

namespace App\Services;

use App\Models\Offplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OffplanService
{
    public function handleFileUploads(Request $request, array &$data): void
    {
        try {
            $altTexts = [];

            // Handle amenities icons if they exist in the data array
            if (isset($data['amenities']) && is_array($data['amenities'])) {
                $deletedIcons = $request->input('deleted_amenities_icon', []);
                
                // Process deleted icons
                if (is_array($deletedIcons)) {
                    foreach ($deletedIcons as $deletedIcon) {
                        if (!empty($deletedIcon)) {
                            Storage::disk('public')->delete($deletedIcon);
                            
                            // Remove the icon from amenities array
                            foreach ($data['amenities'] as &$amenity) {
                                if (is_array($amenity) && isset($amenity['icon']) && $amenity['icon'] === $deletedIcon) {
                                    $amenity['icon'] = '';
                                }
                            }
                        }
                    }
                }
                
                // Process file uploads for amenities
                foreach ($data['amenities'] as $index => &$amenity) {
                    if (!is_array($amenity)) {
                        $amenity = [
                            'name' => $amenity,
                            'icon' => ''
                        ];
                    }
                    
                    // Handle file upload for this amenity
                    if ($request->hasFile('amenities_icon.' . $index)) {
                        $file = $request->file('amenities_icon.' . $index);
                        if ($file && $file->isValid()) {
                            // Delete old icon if exists
                            if (!empty($amenity['icon'])) {
                                Storage::disk('public')->delete($amenity['icon']);
                            }
                            // Store new icon
                            $amenity['icon'] = $file->store('amenities_icons', 'public');
                        }
                    }
                }
                
                // Clean up any empty amenities
                $data['amenities'] = array_values(array_filter($data['amenities'], function($amenity) {
                    return !empty($amenity['name']) || !empty($amenity['icon']);
                }));
            }

            // Handle main photo and its alt text
            if ($request->hasFile('main_photo')) {
                $data['main_photo'] = $request->file('main_photo')->store('offplan_main_photos', 'public');
            }
            if ($request->has('main_photo_alt')) {
                $altTexts['main_photo'] = $request->input('main_photo_alt');
            }

            // Handle banner photo and its alt text
            if ($request->hasFile('banner_photo')) {
                $data['banner_photo'] = $request->file('banner_photo')->store('offplan_banner_photos', 'public');
            }
            if ($request->has('banner_photo_alt')) {
                $altTexts['banner_photo'] = $request->input('banner_photo_alt');
            }

            // Handle exterior gallery and its alt texts
            if ($request->hasFile('exterior_gallery')) {
                $exteriorGallery = [];
                foreach ($request->file('exterior_gallery') as $file) {
                    $exteriorGallery[] = $file->store('offplan_exteriors', 'public');
                }
                $data['exterior_gallery'] = $exteriorGallery;
            }
            if ($request->has('exterior_gallery_alt')) {
                $altTexts['exterior_gallery'] = $request->input('exterior_gallery_alt');
            }

            // Handle interior gallery and its alt texts
            if ($request->hasFile('interior_gallery')) {
                $interiorGallery = [];
                foreach ($request->file('interior_gallery') as $file) {
                    $interiorGallery[] = $file->store('offplan_interiors', 'public');
                }
                $data['interior_gallery'] = $interiorGallery;
            }
            if ($request->has('interior_gallery_alt')) {
                $altTexts['interior_gallery'] = $request->input('interior_gallery_alt');
            }

            // Handle QR photo and its alt text
            if ($request->hasFile('qr_photo')) {
                $data['qr_photo'] = $request->file('qr_photo')->store('offplan_qr_photos', 'public');
            }
            if ($request->has('qr_photo_alt')) {
                $altTexts['qr_photo'] = $request->input('qr_photo_alt');
            }

            // Handle agent image and its alt text
            if ($request->hasFile('agent_image')) {
                $data['agent_image'] = $request->file('agent_image')->store('offplan_agent_images', 'public');
            }
            if ($request->has('agent_image_alt')) {
                $altTexts['agent_image'] = $request->input('agent_image_alt');
            }

            // Handle mobile main photo and its alt text
            if ($request->has('mobile_main_photo_compressed') && $request->input('mobile_main_photo_compressed') !== '') {
                // Process base64 image data
                $imageData = $request->input('mobile_main_photo_compressed');
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                // Generate a unique filename
                $filename = 'mobile_main_photo_' . time() . '.jpg';
                $path = 'offplan_mobile_photos/' . $filename;

                // Store the image
                if (Storage::disk('public')->put($path, $imageData)) {
                    $data['mobile_main_photo'] = $path;
                }
            }
            if ($request->has('mobile_main_photo_alt')) {
                $altTexts['mobile_main_photo'] = $request->input('mobile_main_photo_alt');
            }

            // Handle mobile banner photo and its alt text
            if ($request->has('mobile_banner_photo_compressed') && $request->input('mobile_banner_photo_compressed') !== '') {
                // Process base64 image data
                $imageData = $request->input('mobile_banner_photo_compressed');
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);

                // Generate a unique filename
                $filename = 'mobile_banner_photo_' . time() . '.jpg';
                $path = 'offplan_mobile_banners/' . $filename;

                // Store the image
                if (Storage::disk('public')->put($path, $imageData)) {
                    $data['mobile_banner_photo'] = $path;
                }
            }
            if ($request->has('mobile_banner_photo_alt')) {
                $altTexts['mobile_banner_photo'] = $request->input('mobile_banner_photo_alt');
            }

            // Store alt texts in the data array if we have any
            if (!empty($altTexts)) {
                $data['alt_texts'] = $altTexts;
            }
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function createOffplan(array $data): Offplan
    {
        try {
            // Prepare array fields
            $data['features'] = $data['features'] ?? [];
            $data['near_by'] = $data['near_by'] ?? [];

            // Store location ID before creation
            $locationId = $data['location_id'] ?? null;
            unset($data['location_id']);

            return DB::transaction(function () use ($data, $locationId) {
                $offplan = Offplan::create($data);

                if ($locationId) {
                    $offplan->locations()->attach($locationId);
                }

                return $offplan;
            });

        } catch (\Exception $e) {
            Log::error('Offplan creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOffplan(Offplan $offplan, array $data)
    {
        try {
            DB::beginTransaction();

            // Log incoming data for debugging
            Log::info('Updating offplan with data:', $data);

            // Ensure features is properly formatted as an array
            if (!isset($data['features']) || !is_array($data['features'])) {
                $data['features'] = [];
            }
            
            // Clean and filter features
            $features = array_values(array_filter(array_map('trim', $data['features']), 'strlen'));
            
            // Ensure amenities is properly formatted as an array
            $amenities = [];
            if (isset($data['amenities']) && is_array($data['amenities'])) {
                // Process each amenity to ensure proper structure
                foreach ($data['amenities'] as $amenity) {
                    if (is_array($amenity)) {
                        $name = trim($amenity['name'] ?? '');
                        $icon = $amenity['icon'] ?? '';
                        
                        if (!empty($name) || !empty($icon)) {
                            $amenities[] = [
                                'name' => $name,
                                'icon' => $icon
                            ];
                        }
                    } elseif (is_string($amenity) && !empty(trim($amenity))) {
                        $amenities[] = [
                            'name' => trim($amenity),
                            'icon' => ''
                        ];
                    }
                }
            }

            // Prepare the update data
            $updateData = $data;
            $updateData['features'] = $features;
            $updateData['amenities'] = $amenities;
            
            // Log the prepared update data
            Log::info('Final update data:', $updateData);
            
            // Update the offplan with the provided data
            $offplan->fill($updateData);
            $offplan->save();
            
            // Log the saved data for verification
            Log::info('Offplan updated successfully:', [
                'id' => $offplan->id,
                'features' => $offplan->features,
                'amenities' => $offplan->amenities
            ]);

            // Handle metadata if present
            if (isset($data['metadata'])) {
                $metadata = $offplan->metadata()->firstOrNew([]);
                $metadata->fill($data['metadata']);
                $offplan->metadata()->save($metadata);
            }

            // Handle locations if present
            if (isset($data['location_id'])) {
                $offplan->locations()->sync([$data['location_id']]);
            }

            // Handle alt texts - store as JSON in the alt_texts column
            if (isset($data['alt_texts']) && is_array($data['alt_texts'])) {
                $altTexts = [];
                foreach ($data['alt_texts'] as $imagePath => $altText) {
                    if ($altText) {
                        $altTexts[$imagePath] = $altText;
                    }
                }
                $offplan->alt_texts = $altTexts;
                $offplan->save();
            }

            DB::commit();
            return $offplan;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Offplan update failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
