<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. HANYA DATA ADMIN
        User::updateOrCreate(
            ['username' => 'admin'], 
            [
                'name' => 'Administrator',
                'email' => 'admin@mail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ]
        );
        
        // Data siswa dihapus dari sini agar database bersih 
        // dan siap diisi melalui fitur Import Excel oleh Admin.
    }
}