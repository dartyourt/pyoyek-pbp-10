<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all categories
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->command->info('No categories found. Please run the CategorySeeder first.');
            return;
        }

        // Sample products data
        $products = [
            // Food & Beverage Products
            [
                'name' => 'Kopi Arabica Gayo',
                'description' => 'Kopi premium dari Aceh dengan cita rasa khas pegunungan Gayo. Dipanen dan diolah secara tradisional.',
                'price' => 85000,
                'stock' => 25,
                'category_id' => $categories->where('name', 'Makanan & Minuman')->first()?->id ?? $categories->first()->id,
                'image_file' => 'kopi arabica gasyo.jpeg',
            ],
            [
                'name' => 'Sambal Bawang Homemade',
                'description' => 'Sambal bawang pedas buatan rumahan tanpa pengawet. Cocok untuk teman makan sehari-hari.',
                'price' => 35000,
                'stock' => 50,
                'category_id' => $categories->where('name', 'Makanan & Minuman')->first()?->id ?? $categories->first()->id,
                'image_file' => 'sambal bawang.jpeg',
            ],
            
            // Fashion Products
            [
                'name' => 'Batik Tulis Pekalongan',
                'description' => 'Batik tulis asli Pekalongan dengan motif klasik. Dibuat dengan pewarna alami dan teknik tradisional.',
                'price' => 450000,
                'stock' => 10,
                'category_id' => $categories->where('name', 'Fashion')->first()?->id ?? $categories->first()->id,
                'image_file' => 'batik tulis.jpeg',
            ],
            [
                'name' => 'Tas Anyaman Pandan',
                'description' => 'Tas anyaman dari daun pandan buatan pengrajin lokal. Ramah lingkungan dan tahan lama.',
                'price' => 175000,
                'stock' => 15,
                'category_id' => $categories->where('name', 'Fashion')->first()?->id ?? $categories->first()->id,
                'image_file' => 'tas anyaman.jpeg',
            ],
            
            // Craft Products
            [
                'name' => 'Patung Kayu Jati',
                'description' => 'Patung hiasan dari kayu jati asli. Diukir dengan detail halus oleh pengrajin berpengalaman.',
                'price' => 650000,
                'stock' => 5,
                'category_id' => $categories->where('name', 'Kerajinan Tangan')->first()?->id ?? $categories->first()->id,
                'image_file' => 'patungkayu.jpg',
            ],
            [
                'name' => 'Gantungan Kunci Resin',
                'description' => 'Gantungan kunci custom dari resin dengan beragam bentuk dan warna. Bisa disesuaikan dengan permintaan.',
                'price' => 25000,
                'stock' => 100,
                'category_id' => $categories->where('name', 'Kerajinan Tangan')->first()?->id ?? $categories->first()->id,
                'image_file' => 'gantungakunci.jpeg',
            ],
            
            // Home Decor Products
            [
                'name' => 'Lampu Hias Bambu',
                'description' => 'Lampu hias dari bambu dengan desain unik. Memberikan nuansa hangat pada ruangan.',
                'price' => 275000,
                'stock' => 8,
                'category_id' => $categories->where('name', 'Dekorasi Rumah')->first()?->id ?? $categories->first()->id,
                'image_file' => 'lampuhias.jpeg',
            ],
            [
                'name' => 'Hiasan Dinding Macramé',
                'description' => 'Hiasan dinding dari tali macramé dengan desain boho. Menambah kesan artistik pada ruangan.',
                'price' => 150000,
                'stock' => 12,
                'category_id' => $categories->where('name', 'Dekorasi Rumah')->first()?->id ?? $categories->first()->id,
                'image_file' => 'hiasan dinding macrame.jpeg',
            ],
        ];

        // Insert or update products
        foreach ($products as $productData) {
            // Handle image path
            if (isset($productData['image_file'])) {
                $productData['image_path'] = 'products/' . $productData['image_file'];
                unset($productData['image_file']);
            }
            
            // Use name to uniquely identify product
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        $this->command->info('Sample products seeded successfully!');
    }
}