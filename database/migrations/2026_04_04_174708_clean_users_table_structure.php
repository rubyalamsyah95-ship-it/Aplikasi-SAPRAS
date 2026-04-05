<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. MENGHAPUS KOLOM YANG TIDAK BERGUNA
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            // 2. MENGUBAH EMAIL MENJADI OPTIONAL (NULLABLE)
            // Agar Admin tidak wajib mengisi email saat import 300 siswa
            $table->string('email')->nullable()->change();

            // 3. MENAMBAHKAN KOLOM BARU UNTUK MANAJEMEN SEKOLAH
            if (!Schema::hasColumn('users', 'angkatan')) {
                $table->year('angkatan')->nullable()->after('kelas');
            }
            if (!Schema::hasColumn('users', 'status_akun')) {
                $table->enum('status_akun', ['aktif', 'nonaktif'])->default('aktif')->after('angkatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['angkatan', 'status_akun']);
        });
    }
};