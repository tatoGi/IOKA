<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $cities = [
            'Dubai',
            'Abu Dhabi',
            'Sharjah',
            'Ajman',
            'Al Ain',
            'Fujairah',
            'Ras Al Khaimah',
            'Umm Al Quwain',
            'Jebel Ali',
            'Dubai Marina',
        ];

        foreach ($cities as $city) {
            Location::create(['title' => $city]);
        }
    }
}
