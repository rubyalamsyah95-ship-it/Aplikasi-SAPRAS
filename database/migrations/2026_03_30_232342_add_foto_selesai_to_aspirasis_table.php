<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aspirasi', function (Blueprint $table) {
            $table->string('foto_selesai')->nullable(); // Foto bukti dari admin
            $table->text('pesan_admin')->nullable();    // Pesan perbaikan
            $table->timestamp('tgl_selesai')->nullable(); // Tanggal otomatis
        });
    }

    public function down(): void
    {
        Schema::table('aspirasi', function (Blueprint $table) {
            $table->dropColumn(['foto_selesai', 'pesan_admin', 'tgl_selesai']);
        });
    }
};