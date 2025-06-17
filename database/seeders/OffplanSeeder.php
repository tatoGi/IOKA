<?php

namespace Database\Seeders;

use App\Models\Offplan;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffplanSeeder extends Seeder
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

        // Clear existing offplans
        Offplan::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create offplans in batches for better performance
        $batchSize = 100;

        for ($batch = 0; $batch < $this->totalRecords; $batch += $batchSize) {
            $records = [];

            for ($i = 0; $i < $batchSize && ($batch + $i) < $this->totalRecords; $i++) {
                $records[] = [
                    'title' => substr($faker->sentence(3), 0, 255), // Max 255 chars
                    'slug' => $this->generateUniqueSlug($faker->sentence(3), $batch + $i),
                    'subtitle' => substr($faker->sentence(6), 0, 255), // Max 255 chars
                    'amount' => $faker->randomFloat(2, 100000, 1000000),
                    'amount_dirhams' => $faker->randomFloat(2, 367000, 3670000),
                    'description' => $faker->paragraph(5),
                    'features' => json_encode($faker->words(5)),
                    'near_by' => json_encode([
                        ['title' => substr($faker->city, 0, 255), 'distance' => $faker->randomFloat(2, 1, 20)],
                        ['title' => substr($faker->city, 0, 255), 'distance' => $faker->randomFloat(2, 1, 20)],
                        ['title' => substr($faker->city, 0, 255), 'distance' => $faker->randomFloat(2, 1, 20)],
                    ]),
                    'amenities' => json_encode($faker->words(8)),
                    'map_location' => substr($faker->address, 0, 255), // Max 255 chars
                    'property_type' => $faker->randomElement(['Apartment', 'Villa', 'Townhouse', 'Land', 'Full Building', 'Commercial']),
                    'bathroom' => $faker->numberBetween(1, 5),
                    'bedroom' => $faker->numberBetween(1, 5),
                    'garage' => $faker->numberBetween(1, 3),
                    'sq_ft' => $faker->numberBetween(1000, 5000),
                    'qr_title' => substr($faker->sentence(2), 0, 255), // Max 255 chars
                    'qr_text' => $faker->paragraph(2),
                    'download_brochure' => substr($faker->url, 0, 255), // Max 255 chars
                    'agent_title' => substr($faker->name, 0, 255), // Max 255 chars
                    'agent_status' => $faker->randomElement(['Available', 'Busy', 'Online']),
                    'agent_telephone' => substr($faker->phoneNumber, 0, 20), // Max 20 chars
                    'agent_whatsapp' => substr($faker->phoneNumber, 0, 20), // Max 20 chars
                    'agent_linkedin' => substr($faker->url, 0, 255), // Max 255 chars
                    'agent_email' => substr($faker->email, 0, 255), // Max 255 chars
                    'agent_languages' => json_encode($faker->randomElements(['English', 'Arabic', 'French', 'Spanish', 'German'], $faker->numberBetween(1, 3))),
                    'location' => substr($faker->city, 0, 255), // Max 255 chars
                    'alt_texts' => json_encode([
                        'main_photo' => substr($faker->sentence(3), 0, 255),
                        'banner_photo' => substr($faker->sentence(3), 0, 255),
                        'qr_photo' => substr($faker->sentence(3), 0, 255),
                        'agent_image' => substr($faker->sentence(3), 0, 255),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert batch
            Offplan::insert($records);

            // Show progress
            if (method_exists($this, 'command') && $this->command) {
                $this->command->info("Created " . min($batch + $batchSize, $this->totalRecords) . " of {$this->totalRecords} offplans");
            }
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully created {$this->totalRecords} offplans!");
        }
    }

    /**
     * Remove all seeder records (delete all offplans)
     */
    public function removeSeederRecords()
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing all offplans...");
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing offplans
        Offplan::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("All offplans have been removed successfully!");
        }
    }

    /**
     * Remove specific number of records
     */
    public function removeSpecificCount(int $count)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing {$count} offplans...");
        }

        $deleted = Offplan::limit($count)->delete();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully removed {$deleted} offplans!");
        }
    }

    /**
     * Remove records by criteria
     */
    public function removeByCriteria(array $criteria)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing offplans by criteria...");
        }

        $query = Offplan::query();

        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        $deleted = $query->delete();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully removed {$deleted} offplans!");
        }
    }

    /**
     * Set custom data (replace all existing data with your own)
     */
    public function setCustomData(array $customData)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Setting custom offplan data...");
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing offplans
        Offplan::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert custom data
        foreach ($customData as $data) {
            // Ensure required fields are present
            $data['created_at'] = $data['created_at'] ?? now();
            $data['updated_at'] = $data['updated_at'] ?? now();

            // Generate slug if not provided
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], uniqid());
            }

            Offplan::create($data);
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully set " . count($customData) . " custom offplans!");
        }
    }

    /**
     * Add custom data (keep existing data and add new ones)
     */
    public function addCustomData(array $customData)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Adding custom offplan data...");
        }

        foreach ($customData as $data) {
            // Ensure required fields are present
            $data['created_at'] = $data['created_at'] ?? now();
            $data['updated_at'] = $data['updated_at'] ?? now();

            // Generate slug if not provided
            if (!isset($data['slug']) && isset($data['title'])) {
                $data['slug'] = $this->generateUniqueSlug($data['title'], uniqid());
            }

            Offplan::create($data);
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully added " . count($customData) . " custom offplans!");
        }
    }

    /**
     * Update existing records with custom data
     */
    public function updateWithCustomData(array $updates, array $criteria = [])
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Updating offplans with custom data...");
        }

        $query = Offplan::query();

        // Apply criteria if provided
        foreach ($criteria as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        $updated = $query->update($updates);

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully updated {$updated} offplans!");
        }
    }

    /**
     * Generate a unique slug for each offplan
     */
    private function generateUniqueSlug(string $title, $index): string
    {
        $slug = str_replace(' ', '-', strtolower($title));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = substr($slug, 0, 200); // Leave room for index
        return $slug . '-' . $index;
    }
}
