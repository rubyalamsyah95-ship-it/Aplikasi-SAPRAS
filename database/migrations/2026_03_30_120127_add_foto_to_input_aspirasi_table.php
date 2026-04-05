<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('input_aspirasi', function (Blueprint $table) {
            // Menambahkan kolom foto setelah kolom keterangan (opsional, agar urutan di DB rapi)
            $table->string('foto')->nullable()->after('keterangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('input_aspirasi', function (Blueprint $table) {
            // Menghapus kolom foto jika migrasi di-rollback
            $table->dropColumn('foto');
        });
    }
};