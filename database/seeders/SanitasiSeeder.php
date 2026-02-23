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

        $jenisList = [
            'MCK Umum',
            'Jamban Keluarga',
            'IPAL Komunal',
            'Sumur Gali',
            'Bak Penampungan Air',
        ];

        $statusList = ['baik', 'rusak', 'tidak ada'];

        $lokasiPrefix = [
            'RT 01/RW 01', 'RT 02/RW 01',
            'RT 01/RW 02', 'RT 03/RW 02',
            'Dekat Masjid', 'Dekat Sekolah',
        ];

        $data = [];

        foreach ($wilayahs as $wilayah) {
            $count    = rand(2, 3);
            $usedJenis = [];

            for ($i = 0; $i < $count; $i++) {
                // Pastikan jenis yang berbeda per wilayah (jika mungkin)
                do {
                    $jenis = $jenisList[array_rand($jenisList)];
                } while (in_array($jenis, $usedJenis) && count($usedJenis) < count($jenisList));
                $usedJenis[] = $jenis;

                $status = $statusList[array_rand($statusList)];

                $keterangan = match ($status) {
                    'baik'      => 'Fasilitas dalam kondisi terawat dan dapat digunakan dengan baik.',
                    'rusak'     => 'Dinding bocor dan kloset tidak berfungsi, perlu renovasi segera.',
                    'tidak ada' => 'Fasilitas belum tersedia di lokasi ini, perlu pembangunan baru.',
                    default     => null,
                };

                $data[] = [
                    'wilayah_id' => $wilayah->id,
                    'jenis'      => $jenis,
                    'status'     => $status,
                    'lokasi'     => $lokasiPrefix[array_rand($lokasiPrefix)] . ', ' . $wilayah->nama,
                    'keterangan' => $keterangan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Sanitasi::insert($data);
    }
}
