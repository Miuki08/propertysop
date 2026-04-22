<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@support.io'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123#'), 
                'role' => 'admin',
            ],
        );

        // Mechanic
        User::updateOrCreate(
            ['email' => 'mechanic@support.io'],
            [
                'name' => 'Mechanic',
                'password' => Hash::make('adminer#21'),
                'role' => 'mechanic',
            ],
        );
    }
}