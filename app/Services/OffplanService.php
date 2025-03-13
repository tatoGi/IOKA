<?php

namespace App\Services;

use App\Models\Offplan;
use Illuminate\Http\Request;

class OffplanService
{
    public function handleFileUploads(Request $request, &$data)
    {
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
            $data['exterior_gallery'] = json_encode($exteriorGallery);
        }

        if ($request->hasFile('interior_gallery')) {
            $interiorGallery = [];
            foreach ($request->file('interior_gallery') as $file) {
                $interiorGallery[] = $file->store('offplan_interiors', 'public');
            }
            $data['interior_gallery'] = json_encode($interiorGallery);
        }

        if ($request->hasFile('qr_photo')) {
            $data['qr_photo'] = $request->file('qr_photo')->store('offplan_qr_photos', 'public');
        }

        if ($request->hasFile('agent_image')) {
            $data['agent_image'] = $request->file('agent_image')->store('offplan_agent_images', 'public');
        }
    }

    public function createOffplan(array $data)
    {
        $data['features'] = json_encode($data['features']);
        $data['near_by'] = json_encode($data['near_by']);
        Offplan::create($data);
    }

    public function updateOffplan(Offplan $offplan, array $data)
    {
        $data['features'] = json_encode($data['features']);
        $data['near_by'] = json_encode($data['near_by']);
        $offplan->update($data);
    }
}
