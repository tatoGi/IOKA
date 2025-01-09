<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example static data for seeding
        DB::table('pages')->insert([
            [
                'title' => 'Home',
                'keywords' => 'home, main page',
                'slug' => 'home',
                'desc' => 'This is the home page description.',
                'parent_id' => null,
                'type_id' => null,
                'sort' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'About Us',
                'keywords' => 'about, company',
                'slug' => 'about-us',
                'desc' => 'Learn more about us.',
                'parent_id' => null,
                'type_id' => null,
                'sort' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Services',
                'keywords' => 'services, offerings',
                'slug' => 'services',
                'desc' => 'Explore our services.',
                'parent_id' => null,
                'type_id' => null,
                'sort' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Contact',
                'keywords' => 'contact, get in touch',
                'slug' => 'contact',
                'desc' => 'Get in touch with us.',
                'parent_id' => null,
                'type_id' => null,
                'sort' => 4,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
