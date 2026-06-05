<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        if ($stores->isEmpty()) return;

        $products = [
            // Warung Bu Sari
            ['store_index' => 0, 'name' => 'Nasi Ayam Geprek',       'category' => 'makanan', 'price' => 15000, 'stock' => 50,  'emoji' => '🍱', 'description' => 'Nasi putih dengan ayam geprek sambal level 1-5. Pedas gurih!'],
            ['store_index' => 0, 'name' => 'Nasi Rendang',            'category' => 'makanan', 'price' => 18000, 'stock' => 30,  'emoji' => '🍛', 'description' => 'Nasi dengan rendang daging sapi empuk bumbu khas Minang.'],
            ['store_index' => 0, 'name' => 'Es Teh Manis',            'category' => 'minuman', 'price' => 5000,  'stock' => 100, 'emoji' => '🥤', 'description' => 'Teh manis dingin segar, cocok menemani makan siang.'],
            ['store_index' => 0, 'name' => 'Indomie Goreng Spesial',  'category' => 'makanan', 'price' => 8000,  'stock' => 80,  'emoji' => '🍜', 'description' => 'Indomie goreng dengan telur, sayuran, dan kerupuk.'],

            // Lapak Jajanan
            ['store_index' => 1, 'name' => 'Martabak Mini',           'category' => 'makanan', 'price' => 20000, 'stock' => 20,  'emoji' => '🥞', 'description' => 'Martabak manis mini dengan pilihan topping coklat, keju, atau kacang.'],
            ['store_index' => 1, 'name' => 'Bakso Bakar',             'category' => 'makanan', 'price' => 10000, 'stock' => 40,  'emoji' => '🍢', 'description' => 'Bakso sapi dibakar dengan bumbu kecap pedas manis.'],
            ['store_index' => 1, 'name' => 'Gorengan Mix',            'category' => 'makanan', 'price' => 5000,  'stock' => 60,  'emoji' => '🧆', 'description' => 'Paket gorengan isi 5 — tempe, tahu, pisang, ubi, bakwan.'],

            // Kopi Kita
            ['store_index' => 2, 'name' => 'Kopi Susu Kekinian',      'category' => 'minuman', 'price' => 12000, 'stock' => 50,  'emoji' => '☕', 'description' => 'Kopi susu dengan espresso shot, susu segar, dan gula aren.'],
            ['store_index' => 2, 'name' => 'Jus Alpukat Segar',       'category' => 'minuman', 'price' => 15000, 'stock' => 25,  'emoji' => '🥑', 'description' => 'Jus alpukat murni dengan susu kental manis, tanpa air.'],
            ['store_index' => 2, 'name' => 'Matcha Latte',            'category' => 'minuman', 'price' => 14000, 'stock' => 30,  'emoji' => '🍵', 'description' => 'Matcha premium dengan susu oat, creamy dan tidak terlalu manis.'],
            ['store_index' => 2, 'name' => 'Es Coklat Oreo',          'category' => 'minuman', 'price' => 13000, 'stock' => 35,  'emoji' => '🍫', 'description' => 'Minuman coklat dingin dengan topping oreo crumble.'],
        ];

        foreach ($products as $data) {
            $store = $stores->get($data['store_index']);
            if (!$store) continue;

            Product::create([
                'store_id'    => $store->id,
                'name'        => $data['name'],
                'description' => $data['description'],
                'category'    => $data['category'],
                'price'       => $data['price'],
                'stock'       => $data['stock'],
                'emoji'       => $data['emoji'],
            ]);
        }
    }
}
