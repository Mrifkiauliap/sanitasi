<?php

namespace Database\Seeders;

use App\Models\LaporanKondisi;
use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class LaporanKondisiSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahs = Wilayah::all();
        $users    = User::all()->pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        $catatanList = [
            'Kondisi sanitasi secara umum cukup baik, tidak ada kerusakan signifikan.',
            'Ditemukan kerusakan pada MCK umum, perlu perbaikan segera.',
            'Sumber air bersih mengalami kekeringan di musim kemarau, perlu penanganan darurat.',
            'IPAL komunal tidak berfungsi optimal, lumpur menumpuk dan perlu dikuras.',
            'Warga melaporkan bau tidak sedap dari drainase, perlu pemeriksaan lanjutan.',
            'Fasilitas sanitasi dalam kondisi baik setelah renovasi bulan lalu.',
            'Tingkat pencemaran sumber air meningkat pasca banjir, perlu uji kualitas.',
            'Program PHBS sudah berjalan, kebersihan lingkungan meningkat.',
        ];

        $data = [];

        foreach ($wilayahs as $wilayah) {
            // Setiap wilayah punya 4-8 laporan inspeksi agar data lebih variatif
            $count = rand(4, 8);

            for ($i = 0; $i < $count; $i++) {
                $data[] = [
                    'wilayah_id'       => $wilayah->id,
                    'petugas_id'       => $users[array_rand($users)],
                    'tanggal_inspeksi' => now()->subDays(rand(0, 150))->format('Y-m-d'),
                    'catatan'          => $catatanList[array_rand($catatanList)],
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        LaporanKondisi::insert($data);
    }
}
