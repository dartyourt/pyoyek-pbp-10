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
                'name' => 'Makanan & Minuman',
                'description' => 'Berbagai jenis makanan dan minuman UMKM',
            ],
            [
                'name' => 'Fashion',
                'description' => 'Produk fashion dan pakaian UMKM',
            ],
            [
                'name' => 'Kerajinan Tangan',
                'description' => 'Produk kerajinan tangan UMKM',
            ],
            [
                'name' => 'Dekorasi Rumah',
                'description' => 'Produk dekorasi dan aksesoris rumah UMKM',
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