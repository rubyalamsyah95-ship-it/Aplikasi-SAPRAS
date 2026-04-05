<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel Kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id('id_kategori'); // Primary Key sesuai Controller
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Tabel Input Aspirasi (Laporan dari Siswa)
        Schema::create('input_aspirasi', function (Blueprint $table) {
            $table->id('id_pelaporan'); // Primary Key sesuai Controller
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_kategori')->constrained('kategori', 'id_kategori');
            $table->string('lokasi');
            $table->text('keterangan');
            $table->timestamp('tgl_pelaporan');
            $table->string('status')->default('Pending');
            $table->timestamps();
        });

        // Tabel Aspirasi (Tanggapan/Status dari Admin)
        Schema::create('aspirasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelaporan')->constrained('input_aspirasi', 'id_pelaporan')->onDelete('cascade');
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('aspirasi');
        Schema::dropIfExists('input_aspirasi');
        Schema::dropIfExists('kategori');
    }
};