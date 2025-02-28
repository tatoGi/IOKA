<?php

namespace Database\Factories;

use App\Models\Offplan;
use Illuminate\Database\Eloquent\Factories\Factory;

class OffplanFactory extends Factory
{
    protected $model = Offplan::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'subtitle' => $this->faker->sentence,
            'amount' => $this->faker->numberBetween(50000, 200000),
            'amount_dirhams' => $this->faker->numberBetween(183500, 734000),
            'description' => $this->faker->paragraph,
            'features' => json_encode([$this->faker->word, $this->faker->word]),
            'amenities' => $this->faker->paragraph,
            'map_location' => $this->faker->address,
            'near_by' => json_encode([
                ['title' => $this->faker->word, 'distance' => $this->faker->randomFloat(1, 1, 10)],
                ['title' => $this->faker->word, 'distance' => $this->faker->randomFloat(1, 1, 10)],
            ]),
            'main_photo' => 'path/to/main_photo.jpg',
            'exterior_gallery' => json_encode(['path/to/exterior1.jpg', 'path/to/exterior2.jpg']),
            'interior_gallery' => json_encode(['path/to/interior1.jpg', 'path/to/interior2.jpg']),
            'property_type' => $this->faker->randomElement(['Villa', 'Townhouse', 'Apartment', 'Land', 'Full Building', 'Commercial']),
            'bathroom' => $this->faker->numberBetween(1, 5),
            'bedroom' => $this->faker->numberBetween(1, 5),
            'garage' => $this->faker->numberBetween(0, 3),
            'sq_ft' => $this->faker->numberBetween(500, 5000),
            'qr_title' => $this->faker->sentence,
            'qr_photo' => 'path/to/qr_photo.jpg',
            'qr_text' => $this->faker->paragraph,
            'download_brochure' => 'path/to/brochure.pdf',
            'agent_title' => $this->faker->sentence,
            'agent_status' => $this->faker->randomElement(['Active', 'Inactive']),
            'agent_image' => 'path/to/agent_image.jpg',
            'agent_telephone' => $this->faker->phoneNumber,
            'agent_whatsapp' => $this->faker->phoneNumber,
            'agent_linkedin' => $this->faker->url,
            'location' => $this->faker->address,
        ];
    }
}
