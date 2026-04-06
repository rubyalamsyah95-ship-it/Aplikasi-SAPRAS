<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputAspirasi extends Model
{
    use HasFactory;

    // Sesuaikan ini dengan nama tabel di phpMyAdmin (aspirasi atau input_aspirasi)
    protected $table = 'input_aspirasi'; 
    protected $primaryKey = 'id_pelaporan';

    protected $fillable = [
        'id_user',
        'id_kategori',
        'lokasi',
        'keterangan',
        'status',
        'foto',
        'foto_selesai', 
        'pesan_admin',
        'tgl_pelaporan', 
        'tgl_selesai', 
        'alasan_penolakan'
    ];

    // Tambahkan ini agar tgl_selesai terbaca sebagai objek tanggal otomatis
    protected $casts = [
        'tgl_selesai' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function kategori()
    {
        // Menghubungkan ke model Kategori
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }
}