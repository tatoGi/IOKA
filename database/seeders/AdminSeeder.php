<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;  // Adjust to your admin model if it's named differently

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'IOKA_admin',
            'password' => Hash::make('IOKA!2024Secure@Admin'),  // Strong password
            'role' => 'superadmin',  // Adjust if you have role management
        ]);
    }
}
