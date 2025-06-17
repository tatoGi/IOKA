<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\DeveloperAward;
use App\Models\Offplan;
use App\Models\RentalResale;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeveloperSeeder extends Seeder
{
    protected $totalRecords = 10000;

    public function setCount(int $count): self
    {
        $this->totalRecords = $count;
        return $this;
    }

    public function run()
    {
        $faker = Faker::create();

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DeveloperAward::truncate();
        Developer::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create developers in batches for better performance
        $batchSize = 100;

        for ($batch = 0; $batch < $this->totalRecords; $batch += $batchSize) {
            $developerRecords = [];
            $awardRecords = [];

            for ($i = 0; $i < $batchSize && ($batch + $i) < $this->totalRecords; $i++) {
                $title = substr($faker->company, 0, 255);
                $slug = $this->generateUniqueSlug($title, $batch + $i);

                $developerRecords[] = [
                    'title' => $title,
                    'slug' => $slug,
                    'paragraph' => $faker->paragraph(5),
                    'phone' => substr($faker->phoneNumber, 0, 20),
                    'whatsapp' => substr($faker->phoneNumber, 0, 20),
                    'logo' => $faker->imageUrl(200, 100, 'business', true),
                    'logo_alt' => substr($faker->sentence(3), 0, 255),
                    'photo' => json_encode([
                        [
                            'file' => $faker->imageUrl(640, 480, 'business', true),
                            'alt' => substr($faker->sentence(3), 0, 255),
                        ],
                        [
                            'file' => $faker->imageUrl(640, 480, 'business', true),
                            'alt' => substr($faker->sentence(3), 0, 255),
                        ],
                        [
                            'file' => $faker->imageUrl(640, 480, 'business', true),
                            'alt' => substr($faker->sentence(3), 0, 255),
                        ],
                    ]),
                    'tags' => json_encode($faker->words(5)),
                    'rental_listings' => json_encode([]), // Will be populated later if needed
                    'offplan_listings' => json_encode([]), // Will be populated later if needed
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert developer records
            Developer::insert($developerRecords);

            // Get the inserted records to create awards
            $insertedRecords = Developer::orderBy('id', 'desc')->limit($batchSize)->get();

            foreach ($insertedRecords as $developer) {
                // Create 1-3 awards per developer
                $numAwards = $faker->numberBetween(1, 3);
                for ($k = 0; $k < $numAwards; $k++) {
                    $awardRecords[] = [
                        'developer_id' => $developer->id,
                        'award_title' => substr($faker->sentence(4), 0, 255),
                        'award_year' => $faker->numberBetween(2010, 2024),
                        'award_description' => $faker->paragraph(3),
                        'award_photo' => $faker->imageUrl(400, 300, 'business', true),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Insert award records
            if (!empty($awardRecords)) {
                DeveloperAward::insert($awardRecords);
            }

            // Show progress
            if (method_exists($this, 'command') && $this->command) {
                $this->command->info("Created " . min($batch + $batchSize, $this->totalRecords) . " of {$this->totalRecords} developers");
            }
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully created {$this->totalRecords} developers!");
        }
    }

    /**
     * Generate a unique slug for each developer
     */
    private function generateUniqueSlug(string $title, int $index): string
    {
        $slug = str_replace(' ', '-', strtolower($title));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = substr($slug, 0, 200); // Leave room for index
        return $slug . '-' . $index;
    }

    /**
     * Remove all seeder records
     */
    public function removeSeederRecords()
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing all developers...");
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        DeveloperAward::truncate();
        Developer::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("All developers have been removed successfully!");
        }
    }

    /**
     * Remove specific number of records
     */
    public function removeSpecificCount(int $count)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing {$count} developers...");
        }

        $deleted = Developer::limit($count)->delete();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully removed {$deleted} developers!");
        }
    }
}
