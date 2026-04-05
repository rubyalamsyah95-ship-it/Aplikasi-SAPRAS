<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // 1. BUAT ATAU UPDATE DATA ADMIN
        User::updateOrCreate(
            ['username' => 'admin'], // Cari berdasarkan username
            [
                'name' => 'Administrator',
                'email' => 'admin@mail.com',
                'password' => Hash::make('123'), // Password kamu adalah 123
                'role' => 'admin',
            ]
        );
        
        // Catatan: Data siswa akan diisi melalui fitur Import Excel di aplikasi.
    }
}