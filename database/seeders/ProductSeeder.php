<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkDummy = [
            [
                'name' => 'Kipas Angin Maspion',
                'description' => 'Kipas angin listrik ukuran sedang',
                'price' => 250000,
                'stock' => 20,
                'category' => 'Elektronik',
            ],
            [
                'name' => 'Kaos Polos Hitam',
                'description' => 'Kaos katun combed 30s',
                'price' => 60000,
                'stock' => 50,
                'category' => 'Pakaian',
            ],
            [
                'name' => 'Indomie Goreng',
                'description' => 'Mi instan favorit',
                'price' => 3500,
                'stock' => 200,
                'category' => 'Makanan',
            ],
            [
                'name' => 'Sapu Ijuk',
                'description' => 'Sapu tradisional untuk rumah',
                'price' => 15000,
                'stock' => 30,
                'category' => 'Peralatan Rumah',
            ],
            [
                'name' => 'Buku Tulis Sidu',
                'description' => 'Buku tulis 38 lembar',
                'price' => 5000,
                'stock' => 100,
                'category' => 'Buku',
            ],
        ];

        foreach ($produkDummy as $item) {
            $category = Category::where('name', $item['category'])->first();

            Product::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'price' => $item['price'],
                'stock' => $item['stock'],
                'category_id' => $category->id,
            ]);
        }
    }
}
