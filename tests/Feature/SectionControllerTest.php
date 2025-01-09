<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Page;
use App\Models\Section;

class SectionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_section_creation_with_file_upload()
    {
        // Create a page to associate with the section
        $page = Page::factory()->create();

        // Prepare the data for section creation
        $data = [
            'title' => 'Test Section',
            'description' => 'This is a test section description.',
            'section_key' => 'test_section',
            'fields' => [
                'image' => [
                    'items' => [
                        $this->createUploadedFile() // Simulate file upload
                    ]
                ]
            ]
        ];

        // Send a POST request to create the section
        $response = $this->post(route('admin.sections.store', ['pageId' => $page->id]), $data);

        // Assert that the section was created successfully
        $response->assertRedirect(route('admin.sections.edit', ['pageId' => $page->id, 'sectionKey' => 'test_section']));
        $this->assertDatabaseHas('sections', [
            'title' => 'Test Section',
            'description' => 'This is a test section description.',
            'page_id' => $page->id,
            'section_key' => 'test_section',
        ]);
    }

    protected function createUploadedFile()
    {
        return new \Illuminate\Http\UploadedFile(
            tempnam(sys_get_temp_dir(), 'test'),
            'test_image.jpg',
            'image/jpeg',
            null,
            true
        );
    }
}
