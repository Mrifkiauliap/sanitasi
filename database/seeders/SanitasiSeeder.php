<?php

namespace Database\Seeders;

use App\Models\Sanitasi;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class SanitasiSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahs = Wilayah::where('status', 'terdampak')->get();

        $productNames = [
            'Sabun Batang',
            'Cairan Disinfektan',
            'Hand Sanitizer',
            'Tisu Basah',
            'Masker Medis',
            'Klorin Tablet',
        ];

        $lokasiPrefix = [
            'RT 01/RW 01', 'RT 02/RW 01',
            'RT 01/RW 02', 'RT 03/RW 02',
            'Dekat Masjid', 'Dekat Sekolah',
        ];

        $data = [];

        foreach ($wilayahs as $wilayah) {
            $count    = rand(2, 3);
            $usedProducts = [];

            for ($i = 0; $i < $count; $i++) {
                // Pastikan nama produk yang berbeda per wilayah (jika mungkin)
                do {
                    $nama = $productNames[array_rand($productNames)];
                } while (in_array($nama, $usedProducts) && count($usedProducts) < count($productNames));
                $usedProducts[] = $nama;

                $jumlah = rand(10, 100);

                $data[] = [
                    'wilayah_id' => $wilayah->id,
                    'nama'       => $nama,
                    'jumlah'     => $jumlah,
                    'lokasi'     => $lokasiPrefix[array_rand($lokasiPrefix)] . ', ' . $wilayah->nama,
                    'keterangan' => 'Stok produk sanitasi untuk bantuan wilayah.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Sanitasi::insert($data);
    }
}
