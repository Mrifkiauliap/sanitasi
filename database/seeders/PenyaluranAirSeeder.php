<?php

namespace Database\Seeders;

use App\Models\PenyaluranAir;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class PenyaluranAirSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahs = Wilayah::all();

        $sumberAirList = ['PDAM', 'Tandon Portable', 'Sumur Bor', 'Mobil Tangki', 'IPA (Instalasi Pengolah Air)'];
        $statusList    = ['terdistribusi', 'belum terdistribusi'];

        $data = [];

        foreach ($wilayahs as $wilayah) {
            // Setiap wilayah punya 3-7 riwayat distribusi untuk data lebih padat
            $count = rand(3, 7);

            for ($i = 0; $i < $count; $i++) {
                $status = $statusList[array_rand($statusList)];

                $keterangan = match ($status) {
                    'terdistribusi'     => 'Air berhasil didistribusikan ke ' . $wilayah->nama . '.',
                    'belum terdistribusi' => 'Menunggu armada pengiriman tersedia.',
                    default             => null,
                };

                // Sebar tanggal ke 6 bulan terakhir agar chart terlihat bagus
                $data[] = [
                    'wilayah_id'         => $wilayah->id,
                    'sumber_air'         => $sumberAirList[array_rand($sumberAirList)],
                    'volume_liter'       => rand(2, 40) * 250, // 500 s/d 10000 liter
                    'tanggal_distribusi' => now()->subDays(rand(0, 180))->format('Y-m-d'),
                    'status'             => $status,
                    'keterangan'         => $keterangan,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }
        }

        PenyaluranAir::insert($data);
    }
}
