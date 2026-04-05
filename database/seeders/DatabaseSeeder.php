<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the aplikasi's database.
     */
    public function run(): void
    {
        // 1. Bersihkan data lama agar fresh (menghindari error duplikat)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Kategori::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Panggil UserSeeder untuk membuat akun admin
        $this->call([
            UserSeeder::class,
        ]);

        // 3. Buat Kategori Fasilitas (Data master aplikasi)
        Kategori::create(['nama_kategori' => 'Fasilitas']);
        Kategori::create(['nama_kategori' => 'Kebersihan']);
        Kategori::create(['nama_kategori' => 'Keamanan']);
    }
}