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

    public function updateOffplan(Offplan $offplan, array $data): Offplan
    {
        try {
            // Prepare array fields
            $data['features'] = $data['features'] ?? [];
            $data['near_by'] = $data['near_by'] ?? [];

            // Handle location if provided
            $locationId = $data['location_id'] ?? null;
            unset($data['location_id']);

            return DB::transaction(function () use ($offplan, $data, $locationId) {
                $offplan->update($data);

                if ($locationId !== null) {
                    $offplan->locations()->sync([$locationId]);
                }

                return $offplan;
            });

        } catch (\Exception $e) {
            Log::error('Offplan update failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
