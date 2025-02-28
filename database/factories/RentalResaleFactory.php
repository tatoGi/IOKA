<?php

namespace Database\Factories;

use App\Models\RentalResale;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalResaleFactory extends Factory
{
    protected $model = RentalResale::class;

    public function definition()
    {
        return [
            'amount' => $this->faker->numberBetween(100, 10000),
            'amount_dirhams' => $this->faker->numberBetween(367, 36700),
            'qr_photo' => $this->faker->imageUrl(),
            'gallery_images' => json_encode([$this->faker->imageUrl(), $this->faker->imageUrl()]),
        ];
    }
}
