<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'Admin Sanitasi',
            'username' => 'admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('admin321'),
            'status'   => 'active',
        ]);

        // $this->call([
        //     WilayahSeeder::class,
        //     SanitasiSeeder::class,
        //     PenyaluranAirSeeder::class,
        //     LaporanKondisiSeeder::class,
        // ]);
    }
}
