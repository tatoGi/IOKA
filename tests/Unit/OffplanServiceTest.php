<?php

namespace Tests\Unit;

use App\Models\Offplan;
use App\Services\OffplanService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OffplanServiceTest extends TestCase
{
    protected $offplanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->offplanService = new OffplanService;
        Storage::fake('public');
    }

    public function test_handle_file_uploads()
    {
        $request = Request::create('/upload', 'POST', [], [], [
            'main_photo' => UploadedFile::fake()->image('main_photo.jpg'),
            'exterior_gallery' => [
                UploadedFile::fake()->image('exterior1.jpg'),
                UploadedFile::fake()->image('exterior2.jpg'),
            ],
            'interior_gallery' => [
                UploadedFile::fake()->image('interior1.jpg'),
                UploadedFile::fake()->image('interior2.jpg'),
            ],
            'qr_photo' => UploadedFile::fake()->image('qr_photo.jpg'),
            'agent_image' => UploadedFile::fake()->image('agent_image.jpg'),
        ]);

        $data = [];
        $this->offplanService->handleFileUploads($request, $data);

        $this->assertTrue(Storage::disk('public')->exists($data['main_photo']));
        $this->assertCount(2, json_decode($data['exterior_gallery'], true));
        $this->assertCount(2, json_decode($data['interior_gallery'], true));
        $this->assertTrue(Storage::disk('public')->exists($data['qr_photo']));
        $this->assertTrue(Storage::disk('public')->exists($data['agent_image']));
    }

    public function test_create_offplan()
    {
        $data = [
            'title' => 'Test Offplan',
            'subtitle' => 'Test Subtitle',
            'amount' => 100000,
            'amount_dirhams' => 367000,
            'description' => 'Test Description',
            'features' => ['feature1', 'feature2'],
            'amenities' => 'Test Amenities',
            'map_location' => 'Test Location',
            'near_by' => [
                ['title' => 'Place1', 'distance' => 4.5],
                ['title' => 'Place2', 'distance' => 3.2],
            ],
            'main_photo' => 'path/to/main_photo.jpg',
            'exterior_gallery' => ['path/to/exterior1.jpg', 'path/to/exterior2.jpg'],
            'interior_gallery' => ['path/to/interior1.jpg', 'path/to/interior2.jpg'],
            'property_type' => 'Villa',
            'bathroom' => 2,
            'bedroom' => 3,
            'garage' => 1,
            'sq_ft' => 1500,
            'qr_title' => 'Test QR Title',
            'qr_photo' => 'path/to/qr_photo.jpg',
            'qr_text' => 'Test QR Text',
            'download_brochure' => 'path/to/brochure.pdf',
            'agent_title' => 'Test Agent Title',
            'agent_status' => 'Active',
            'agent_image' => 'path/to/agent_image.jpg',
            'agent_telephone' => '1234567890',
            'agent_whatsapp' => '1234567890',
            'agent_linkedin' => 'https://linkedin.com/in/test',
            'location' => 'Test Location',
        ];

        $this->offplanService->createOffplan($data);

        $this->assertDatabaseHas('offplans', [
            'title' => 'Test Offplan',
            'features' => json_encode(['feature1', 'feature2']),
            'near_by' => json_encode([
                ['title' => 'Place1', 'distance' => 4.5],
                ['title' => 'Place2', 'distance' => 3.2],
            ]),
        ]);
    }

    public function test_update_offplan()
    {
        $offplan = Offplan::factory()->create([
            'title' => 'Old Offplan',
            'subtitle' => 'Old Subtitle',
            'amount' => 50000,
            'amount_dirhams' => 183500,
            'description' => 'Old Description',
            'features' => json_encode(['old_feature']),
            'amenities' => 'Old Amenities',
            'map_location' => 'Old Location',
            'near_by' => json_encode([
                ['title' => 'Old Place', 'distance' => 2.5],
            ]),
            'main_photo' => 'path/to/old_main_photo.jpg',
            'exterior_gallery' => json_encode(['path/to/old_exterior1.jpg']),
            'interior_gallery' => json_encode(['path/to/old_interior1.jpg']),
            'property_type' => 'Apartment',
            'bathroom' => 1,
            'bedroom' => 1,
            'garage' => 0,
            'sq_ft' => 800,
            'qr_title' => 'Old QR Title',
            'qr_photo' => 'path/to/old_qr_photo.jpg',
            'qr_text' => 'Old QR Text',
            'download_brochure' => 'path/to/old_brochure.pdf',
            'agent_title' => 'Old Agent Title',
            'agent_status' => 'Inactive',
            'agent_image' => 'path/to/old_agent_image.jpg',
            'agent_telephone' => '0987654321',
            'agent_whatsapp' => '0987654321',
            'agent_linkedin' => 'https://linkedin.com/in/old',
            'location' => 'Old Location',
        ]);

        $data = [
            'title' => 'Updated Offplan',
            'subtitle' => 'Updated Subtitle',
            'amount' => 150000,
            'amount_dirhams' => 550500,
            'description' => 'Updated Description',
            'features' => ['new_feature1', 'new_feature2'],
            'amenities' => 'Updated Amenities',
            'map_location' => 'Updated Location',
            'near_by' => [
                ['title' => 'New Place1', 'distance' => 5.5],
                ['title' => 'New Place2', 'distance' => 6.2],
            ],
            'main_photo' => 'path/to/updated_main_photo.jpg',
            'exterior_gallery' => ['path/to/updated_exterior1.jpg', 'path/to/updated_exterior2.jpg'],
            'interior_gallery' => ['path/to/updated_interior1.jpg', 'path/to/updated_interior2.jpg'],
            'property_type' => 'Townhouse',
            'bathroom' => 3,
            'bedroom' => 4,
            'garage' => 2,
            'sq_ft' => 2000,
            'qr_title' => 'Updated QR Title',
            'qr_photo' => 'path/to/updated_qr_photo.jpg',
            'qr_text' => 'Updated QR Text',
            'download_brochure' => 'path/to/updated_brochure.pdf',
            'agent_title' => 'Updated Agent Title',
            'agent_status' => 'Active',
            'agent_image' => 'path/to/updated_agent_image.jpg',
            'agent_telephone' => '1122334455',
            'agent_whatsapp' => '1122334455',
            'agent_linkedin' => 'https://linkedin.com/in/updated',
            'location' => 'Updated Location',
        ];

        $this->offplanService->updateOffplan($offplan, $data);

        $this->assertDatabaseHas('offplans', [
            'id' => $offplan->id,
            'title' => 'Updated Offplan',
            'features' => json_encode(['new_feature1', 'new_feature2']),
            'near_by' => json_encode([
                ['title' => 'New Place1', 'distance' => 5.5],
                ['title' => 'New Place2', 'distance' => 6.2],
            ]),
        ]);
    }
}
