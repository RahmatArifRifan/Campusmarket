<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $sellers = User::where('role', 'seller')->get();

        $storeData = [
            [
                'name'            => 'Warung Bu Sari',
                'description'     => 'Warung makan rumahan dengan menu nasi ayam, lauk pauk, dan minuman segar. Buka setiap hari!',
                'logo_emoji'      => '🍱',
                'category'        => 'Makanan & Minuman',
                'location'        => 'Kantin Gedung A, Lantai 1',
                'operating_hours' => 'Senin–Sabtu, 07.00–16.00',
            ],
            [
                'name'            => 'Lapak Jajanan Kampus',
                'description'     => 'Aneka jajanan kampus — martabak, bakso bakar, gorengan, dan cemilan kekinian.',
                'logo_emoji'      => '🥞',
                'category'        => 'Makanan',
                'location'        => 'Depan Gedung B',
                'operating_hours' => 'Senin–Jumat, 09.00–17.00',
            ],
            [
                'name'            => 'Kopi Kita',
                'description'     => 'Kopi susu kekinian, jus buah segar, dan minuman dingin. Harga mahasiswa!',
                'logo_emoji'      => '☕',
                'category'        => 'Minuman',
                'location'        => 'Lobby Perpustakaan',
                'operating_hours' => 'Senin–Jumat, 08.00–18.00',
            ],
        ];

        foreach ($sellers as $i => $seller) {
            if (isset($storeData[$i])) {
                Store::create(array_merge($storeData[$i], ['user_id' => $seller->id]));
            }
        }
    }
}
