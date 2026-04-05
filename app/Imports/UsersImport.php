<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Lewati jika NIS kosong
        if (empty($row['nis'])) {
            return null;
        }

        // 2. Cek apakah NIS atau Email sudah terdaftar di database
        // Kita cari user yang NIS-nya sama ATAU Email-nya sama
        $existingUser = User::where('nis', $row['nis'])
                            ->orWhere('email', $row['email'])
                            ->first();

        // 3. Jika ditemukan (berarti NIS atau Email duplikat), JANGAN masukkan data
        if ($existingUser) {
            return null; 
        }

        // 4. Jika benar-benar baru, baru buat akun
        return new User([
            'name'         => $row['name'] ?? $row['nama'] ?? 'Tanpa Nama',
            'username'     => $row['nis'],
            'email'        => $row['email'] ?? null,
            'password'     => Hash::make($row['nis']),
            'role'         => 'siswa',
            'nis'          => $row['nis'],
            'kelas'        => $row['kelas'] ?? '-',
            'angkatan'     => $row['angkatan'] ?? date('Y'),
            'status_akun'  => 'aktif',
        ]);
    }
}