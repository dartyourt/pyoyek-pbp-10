<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Atmin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'Bukan Atmin',
                'password' => Hash::make('password'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        // Copy sample product images to storage
        $this->call(CopyProductImagesSeeder::class);

        // Copy UI images to storage
        $this->call(UiImagesSeeder::class);
        
        // Create Demo Categories and Products
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}