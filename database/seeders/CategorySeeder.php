<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Makanan',
                'description' => 'Berbagai jenis makanan UMKM',
            ],
            [
                'name' => 'Minuman',
                'description' => 'Berbagai jenis minuman UMKM',
            ],
            [
                'name' => 'Kerajinan',
                'description' => 'Produk kerajinan tangan UMKM',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Produk fashion dan pakaian UMKM',
            ],
            [
                'name' => 'Aksesoris',
                'description' => 'Berbagai jenis aksesoris UMKM',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}