<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama agar fresh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        Kategori::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Panggil UserSeeder untuk membuat akun
        $this->call([
            UserSeeder::class,
        ]);

        // Buat Kategori langsung di sini (karena datanya sedikit)
        Kategori::create(['nama_kategori' => 'Fasilitas']);
        Kategori::create(['nama_kategori' => 'Kebersihan']);
        Kategori::create(['nama_kategori' => 'Keamanan']);
    }
}