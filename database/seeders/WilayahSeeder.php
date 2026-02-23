<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $wilayahs = [
            // Langsa Baro
            ['nama' => 'Gampong Blang', 'kecamatan' => 'Langsa Baro', 'status' => 'terdampak', 'deskripsi' => 'Wilayah padat penduduk dengan akses sanitasi terbatas.'],
            ['nama' => 'Gampong Seuriget', 'kecamatan' => 'Langsa Baro', 'status' => 'terdampak', 'deskripsi' => 'MCK umum dalam kondisi rusak, perlu perbaikan segera.'],
            ['nama' => 'Gampong Meurandeh', 'kecamatan' => 'Langsa Baro', 'status' => 'tidak terdampak', 'deskripsi' => null],
            ['nama' => 'Gampong Geudubang Aceh', 'kecamatan' => 'Langsa Baro', 'status' => 'terdampak', 'deskripsi' => 'Drainase tersumbat setelah banjir terakhir.'],
            ['nama' => 'Gampong Paya Bujok Tunong', 'kecamatan' => 'Langsa Baro', 'status' => 'tidak terdampak', 'deskripsi' => null],

            // Langsa Kota
            ['nama' => 'Gampong Teungoh', 'kecamatan' => 'Langsa Kota', 'status' => 'terdampak', 'deskripsi' => 'Banjir musiman menyebabkan pencemaran sumber air.'],
            ['nama' => 'Gampong Kota Lama', 'kecamatan' => 'Langsa Kota', 'status' => 'tidak terdampak', 'deskripsi' => null],
            ['nama' => 'Gampong Sidorejo', 'kecamatan' => 'Langsa Kota', 'status' => 'terdampak', 'deskripsi' => 'Kepadatan tinggi, drainase buruk.'],
            ['nama' => 'Gampong Daulat', 'kecamatan' => 'Langsa Kota', 'status' => 'terdampak', 'deskripsi' => 'Kekurangan suplai air PDAM di blok tertentu.'],

            // Langsa Timur
            ['nama' => 'Gampong Alue Beurawe', 'kecamatan' => 'Langsa Timur', 'status' => 'terdampak', 'deskripsi' => 'Kekurangan akses air bersih di musim kemarau.'],
            ['nama' => 'Gampong Matang Seulimeng', 'kecamatan' => 'Langsa Timur', 'status' => 'tidak terdampak', 'deskripsi' => null],
            ['nama' => 'Gampong Buket Meatuah', 'kecamatan' => 'Langsa Timur', 'status' => 'terdampak', 'deskripsi' => 'Sumber air sumur payau, butuh suplai air tawar.'],

            // Langsa Lama
            ['nama' => 'Gampong Pondok Kelapa', 'kecamatan' => 'Langsa Lama', 'status' => 'terdampak', 'deskripsi' => 'Septik tank tidak memenuhi standar kesehatan.'],
            ['nama' => 'Gampong Baroh Langsa Lama', 'kecamatan' => 'Langsa Lama', 'status' => 'tidak terdampak', 'deskripsi' => null],
            ['nama' => 'Gampong Merandeh Dayah', 'kecamatan' => 'Langsa Lama', 'status' => 'terdampak', 'deskripsi' => 'MCK pesantren overload.'],

            // Birem Bayeun
            ['nama' => 'Gampong Buket Rata', 'kecamatan' => 'Birem Bayeun', 'status' => 'terdampak', 'deskripsi' => 'Wilayah terpencil, minimnya fasilitas sanitasi dasar.'],
            ['nama' => 'Gampong Suka Jadi', 'kecamatan' => 'Birem Bayeun', 'status' => 'tidak terdampak', 'deskripsi' => null],
            ['nama' => 'Gampong Alue Gading', 'kecamatan' => 'Birem Bayeun', 'status' => 'terdampak', 'deskripsi' => 'Akses jalan sulit, distribusi air terhambat.'],
        ];

        foreach ($wilayahs as $data) {
            Wilayah::create($data);
        }
    }
}
