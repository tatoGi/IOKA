<?php

namespace Database\Seeders;

use App\Models\Amount;
use App\Models\RentalResale;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalResaleSeeder extends Seeder
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
        RentalResale::truncate();
        Amount::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create rental resale records in batches for better performance
        $batchSize = 100;

        for ($batch = 0; $batch < $this->totalRecords; $batch += $batchSize) {
            $rentalResaleRecords = [];
            $amountRecords = [];

            for ($i = 0; $i < $batchSize && ($batch + $i) < $this->totalRecords; $i++) {
                $rentalResaleRecords[] = [
                    'property_type' => $faker->randomElement(['Apartment', 'Villa', 'Townhouse', 'Land', 'Full Building', 'Commercial']),
                    'title' => substr($faker->sentence(3), 0, 255),
                    'subtitle' => substr($faker->sentence(6), 0, 255),
                    'top' => $faker->boolean(10), // 10% chance to be top
                    'bathroom' => $faker->numberBetween(1, 5),
                    'bedroom' => $faker->numberBetween(1, 5),
                    'sq_ft' => $faker->numberBetween(1000, 5000),
                    'garage' => $faker->numberBetween(1, 3),
                    'description' => $faker->paragraph(5),
                    'details' => json_encode([
                        'detail1' => substr($faker->sentence, 0, 255),
                        'detail2' => substr($faker->sentence, 0, 255),
                        'detail3' => substr($faker->sentence, 0, 255),
                    ]),
                    'amenities' => json_encode([
                        'amenity1' => substr($faker->word, 0, 255),
                        'amenity2' => substr($faker->word, 0, 255),
                        'amenity3' => substr($faker->word, 0, 255),
                        'amenity4' => substr($faker->word, 0, 255),
                    ]),
                    'agent_title' => substr($faker->name, 0, 255),
                    'agent_status' => $faker->randomElement(['Available', 'Busy', 'Online']),
                    'agent_languages' => json_encode($faker->randomElements(['English', 'Arabic', 'French', 'Spanish', 'German'], $faker->numberBetween(1, 3))),
                    'agent_call' => substr($faker->phoneNumber, 0, 20),
                    'agent_whatsapp' => substr($faker->phoneNumber, 0, 20),
                    'agent_photo' => $faker->imageUrl(200, 200, 'people', true),
                    'location_link' => substr($faker->url, 0, 255),
                    'qr_photo' => $faker->imageUrl(200, 200, 'business', true),
                    'reference' => $faker->uuid,
                    'dld_permit_number' => $faker->uuid,
                    'addresses' => json_encode([
                        'address1' => substr($faker->address, 0, 255),
                        'address2' => substr($faker->address, 0, 255),
                    ]),
                    'gallery_images' => json_encode([]), // Empty array for now
                    'tags' => json_encode([
                        'tag1' => substr($faker->word, 0, 255),
                        'tag2' => substr($faker->word, 0, 255),
                        'tag3' => substr($faker->word, 0, 255),
                    ]),
                    'slug' => $this->generateUniqueSlug($faker->sentence(3), $batch + $i),
                    'languages' => json_encode($faker->randomElements(['English', 'Arabic', 'French', 'Spanish', 'German'], $faker->numberBetween(1, 3))),
                    'alt_texts' => json_encode([
                        'main_photo' => substr($faker->sentence(3), 0, 255),
                        'gallery_photo' => substr($faker->sentence(3), 0, 255),
                        'agent_photo' => substr($faker->sentence(3), 0, 255),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert rental resale records
            RentalResale::insert($rentalResaleRecords);

            // Get the inserted records to create amounts
            $insertedRecords = RentalResale::orderBy('id', 'desc')->limit($batchSize)->get();

            foreach ($insertedRecords as $record) {
                $amountRecords[] = [
                    'rental_resale_id' => $record->id,
                    'amount' => $faker->randomFloat(2, 100000, 999999), // Max 999,999.99
                    'amount_dirhams' => $faker->randomFloat(2, 367000, 999999), // Max 999,999.99
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert amount records
            Amount::insert($amountRecords);

            // Show progress
            if (method_exists($this, 'command') && $this->command) {
                $this->command->info("Created " . min($batch + $batchSize, $this->totalRecords) . " of {$this->totalRecords} rental resale records");
            }
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully created {$this->totalRecords} rental resale records!");
        }
    }

    /**
     * Generate a unique slug for each rental resale
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
            $this->command->info("Removing all rental resale records...");
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        RentalResale::truncate();
        Amount::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("All rental resale records have been removed successfully!");
        }
    }

    /**
     * Remove specific number of records
     */
    public function removeSpecificCount(int $count)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing {$count} rental resale records...");
        }

        $deleted = RentalResale::limit($count)->delete();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully removed {$deleted} rental resale records!");
        }
    }
}
