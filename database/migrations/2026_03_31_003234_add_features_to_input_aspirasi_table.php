<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan penambahan kolom ke tabel input_aspirasi.
     */
    public function up(): void
    {
        Schema::table('input_aspirasi', function (Blueprint $table) {
            // Kita tambahkan kolom hanya jika belum ada di tabel input_aspirasi
            if (!Schema::hasColumn('input_aspirasi', 'pesan_admin')) {
                $table->text('pesan_admin')->nullable();
            }
            if (!Schema::hasColumn('input_aspirasi', 'foto_selesai')) {
                $table->string('foto_selesai')->nullable();
            }
            if (!Schema::hasColumn('input_aspirasi', 'tgl_selesai')) {
                $table->timestamp('tgl_selesai')->nullable();
            }
            if (!Schema::hasColumn('input_aspirasi', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')->nullable();
            }
        });
    }

    /**
     * Batalkan perubahan (Rollback).
     */
    public function down(): void
    {
        Schema::table('input_aspirasi', function (Blueprint $table) {
            $table->dropColumn(['pesan_admin', 'foto_selesai', 'tgl_selesai', 'alasan_penolakan']);
        });
    }
};