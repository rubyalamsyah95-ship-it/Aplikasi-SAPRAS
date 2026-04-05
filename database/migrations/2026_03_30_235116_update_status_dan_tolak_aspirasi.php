<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan penambahan kolom untuk fitur Verifikasi & Inbox.
     */
    public function up(): void
    {
        // Pastikan nama tabel adalah 'aspirasi' (tanpa 's') sesuai database kamu
        Schema::table('aspirasi', function (Blueprint $table) {
            // Kolom untuk menampung alasan jika siswa merasa perbaikan belum selesai
            $table->text('alasan_penolakan')->nullable()->after('pesan_admin');
            
            // Catatan: Jika kamu belum menambahkan kolom foto_selesai & tgl_selesai sebelumnya, 
            // kamu bisa menambahkannya di sini juga agar aman.
            if (!Schema::hasColumn('aspirasi', 'foto_selesai')) {
                $table->string('foto_selesai')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('aspirasi', 'pesan_admin')) {
                $table->text('pesan_admin')->nullable()->after('foto_selesai');
            }
            if (!Schema::hasColumn('aspirasi', 'tgl_selesai')) {
                $table->timestamp('tgl_selesai')->nullable()->after('pesan_admin');
            }
        });
    }

    /**
     * Batalkan perubahan jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('aspirasi', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'foto_selesai', 'pesan_admin', 'tgl_selesai']);
        });
    }
};