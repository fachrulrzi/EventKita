<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::firstOrCreate(
            ['email' => 'admin@test.com'], // <-- Kondisi untuk mencari user
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // <-- Ganti dengan password yang aman!
                'role' => 'admin',
            ]
        );
    }
}