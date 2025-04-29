<?php

namespace App\Services;

use App\Models\Offplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OffplanService
{
    public function handleFileUploads(Request $request, array &$data): void
    {
        try {
            if ($request->hasFile('main_photo')) {
                $data['main_photo'] = $request->file('main_photo')->store('offplan_main_photos', 'public');
            }

            if ($request->hasFile('banner_photo')) {
                $data['banner_photo'] = $request->file('banner_photo')->store('offplan_banner_photos', 'public');
            }

            if ($request->hasFile('exterior_gallery')) {
                $exteriorGallery = [];
                foreach ($request->file('exterior_gallery') as $file) {
                    $exteriorGallery[] = $file->store('offplan_exteriors', 'public');
                }
                $data['exterior_gallery'] = $exteriorGallery;
            }

            if ($request->hasFile('interior_gallery')) {
                $interiorGallery = [];
                foreach ($request->file('interior_gallery') as $file) {
                    $interiorGallery[] = $file->store('offplan_interiors', 'public');
                }
                $data['interior_gallery'] = $interiorGallery;
            }

            if ($request->hasFile('qr_photo')) {
                $data['qr_photo'] = $request->file('qr_photo')->store('offplan_qr_photos', 'public');
            }

            if ($request->hasFile('agent_image')) {
                $data['agent_image'] = $request->file('agent_image')->store('offplan_agent_images', 'public');
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
