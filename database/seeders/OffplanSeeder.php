<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offplan;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class OffplanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing offplans
        Offplan::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 10 offplans
        for ($i = 1; $i <= 20; $i++) {
            Offplan::create([
                'title' => $faker->sentence(3),
                'slug' => str_replace(' ', '-', strtolower($faker->sentence(3))),
                'subtitle' => $faker->sentence(6),
                'amount' => $faker->randomFloat(2, 100000, 1000000),
                'amount_dirhams' => $faker->randomFloat(2, 367000, 3670000),
                'description' => $faker->paragraph(5),
                'features' => json_encode($faker->words(5)),
                'near_by' => json_encode([
                    ['title' => $faker->city, 'distance' => $faker->randomFloat(2, 1, 20)],
                    ['title' => $faker->city, 'distance' => $faker->randomFloat(2, 1, 20)],
                ]),
                'amenities' => json_encode($faker->words(5)),
                'map_location' => $faker->address,
                'property_type' => $faker->randomElement(['Apartment', 'Villa', 'Townhouse']),
                'bathroom' => $faker->numberBetween(1, 5),
                'bedroom' => $faker->numberBetween(1, 5),
                'garage' => $faker->numberBetween(1, 3),
                'sq_ft' => $faker->numberBetween(1000, 5000),
                'qr_title' => $faker->sentence(2),
                'qr_text' => $faker->paragraph(2),
                'download_brochure' => $faker->url,
                'agent_title' => $faker->name,
                'agent_status' => $faker->randomElement(['Available', 'Busy']),
                'agent_telephone' => $faker->phoneNumber,
                'agent_whatsapp' => $faker->phoneNumber,
                'agent_linkedin' => $faker->url,
                'location' => $faker->city,
            ]);
        }
    }
}
