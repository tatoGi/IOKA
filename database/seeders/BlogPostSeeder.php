<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogPostSeeder extends Seeder
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
        BlogPost::truncate();
        Tag::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create some common tags first
        $commonTags = [
            'Real Estate', 'Investment', 'Dubai', 'Property', 'Market Trends',
            'Luxury', 'Apartment', 'Villa', 'Development', 'Architecture',
            'Interior Design', 'Location', 'Amenities', 'Finance', 'Tips',
            'Guide', 'News', 'Analysis', 'Forecast', 'Tips & Tricks'
        ];

        $tags = [];
        foreach ($commonTags as $tagName) {
            $tags[] = Tag::create(['name' => $tagName]);
        }

        // Create blog posts in batches for better performance
        $batchSize = 100;

        for ($batch = 0; $batch < $this->totalRecords; $batch += $batchSize) {
            $records = [];

            for ($i = 0; $i < $batchSize && ($batch + $i) < $this->totalRecords; $i++) {
                $title = substr($faker->sentence(4), 0, 255);
                $slug = $this->generateUniqueSlug($title, $batch + $i);

                $records[] = [
                    'title' => $title,
                    'slug' => $slug,
                                        'body' => $this->generateBlogContent($faker),
                    'show_on_main_page' => $faker->boolean(20), // 20% chance to show on main page
                    'date' => $faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                    'image' => null, // Will be handled separately if needed
                    'image_alt' => substr($faker->sentence(3), 0, 255),
                    'banner_image' => null, // Will be handled separately if needed
                    'banner_image_alt' => substr($faker->sentence(3), 0, 255),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert batch
            BlogPost::insert($records);

            // Show progress
            if (method_exists($this, 'command') && $this->command) {
                $this->command->info("Created " . min($batch + $batchSize, $this->totalRecords) . " of {$this->totalRecords} blog posts");
            }
        }

        // Attach tags to blog posts
        $this->attachTagsToBlogPosts($tags, $faker);

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully created {$this->totalRecords} blog posts!");
        }
    }

    /**
     * Generate realistic blog content
     */
    private function generateBlogContent($faker): string
    {
        $paragraphs = [];

        // Introduction paragraph
        $paragraphs[] = $faker->paragraph(3);

        // Main content paragraphs (3-6 paragraphs)
        $numParagraphs = $faker->numberBetween(3, 6);
        for ($i = 0; $i < $numParagraphs; $i++) {
            $paragraphs[] = $faker->paragraph(4);
        }

        // Conclusion paragraph
        $paragraphs[] = $faker->paragraph(2);

        return implode("\n\n", $paragraphs);
    }

    /**
     * Generate a unique slug for each blog post
     */
    private function generateUniqueSlug(string $title, int $index): string
    {
        $slug = str_replace(' ', '-', strtolower($title));
        $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
        $slug = substr($slug, 0, 200); // Leave room for index
        return $slug . '-' . $index;
    }

    /**
     * Attach tags to blog posts
     */
    private function attachTagsToBlogPosts(array $tags, $faker): void
    {
        $blogPosts = BlogPost::all();

        foreach ($blogPosts as $blogPost) {
            // Attach 2-5 random tags to each blog post
            $randomTags = $faker->randomElements($tags, $faker->numberBetween(2, 5));
            $tagIds = array_map(function($tag) {
                return $tag->id;
            }, $randomTags);

            $blogPost->tags()->attach($tagIds);
        }

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Attached tags to blog posts");
        }
    }

    /**
     * Remove all seeder records
     */
    public function removeSeederRecords()
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing all blog posts...");
        }

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data
        BlogPost::truncate();
        Tag::truncate();

        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("All blog posts have been removed successfully!");
        }
    }

    /**
     * Remove specific number of records
     */
    public function removeSpecificCount(int $count)
    {
        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Removing {$count} blog posts...");
        }

        $deleted = BlogPost::limit($count)->delete();

        if (method_exists($this, 'command') && $this->command) {
            $this->command->info("Successfully removed {$deleted} blog posts!");
        }
    }
}
