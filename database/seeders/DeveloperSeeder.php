<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Developer;
use App\Models\DeveloperAward;
use App\Models\Offplan;
use App\Models\RentalResale;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
        public function run()
        {
            $faker = Faker::create();

            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Clear existing data
            DeveloperAward::truncate();
            Developer::truncate();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Create 10 developers
            for ($i = 1; $i <= 20; $i++) {
                $title = $faker->company;
                $slug = str_replace(' ', '-', strtolower($title));
                $paragraph = $faker->paragraph;
                $phone = $faker->phoneNumber;
                $whatsapp = $faker->phoneNumber;

                // Generate fake image URLs for photos
                $photos = [];
                for ($j = 0; $j < 3; $j++) {
                    // Fake image URL (you can use any image hosting URL)
                    $photos[] = [
                        'file' => $faker->imageUrl(640, 480, 'business', true), // Use Faker's imageUrl method to generate URLs
                        'alt' => $faker->sentence,
                    ];
                }

                $tags = $faker->words(5);
                $rentalListings = RentalResale::inRandomOrder()->limit(3)->pluck('id')->toArray();
                $offplanListings = Offplan::inRandomOrder()->limit(3)->pluck('id')->toArray();

                // Create Developer
                $developer = Developer::create([
                    'title' => $title,
                    'slug' => $slug,
                    'paragraph' => $paragraph,
                    'phone' => $phone,
                    'whatsapp' => $whatsapp,
                    'photo' => null, // Store the generated image URLs
                    'tags' => json_encode($tags),
                    'rental_listings' => json_encode($rentalListings),
                    'offplan_listings' => json_encode($offplanListings),
                ]);

                // Create DeveloperAwards
                for ($k = 0; $k < 2; $k++) {
                    DeveloperAward::create([
                        'developer_id' => $developer->id,
                        'award_title' => $faker->sentence,
                        'award_year' => $faker->year,
                        'award_description' => $faker->paragraph,
                        'award_photo' => null
                    ]);
                }
            }
        }
    }


