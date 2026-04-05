<?php

namespace App\Http\Controllers;

use App\Models\InputAspirasi; 
use App\Models\Kategori; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AspirasiController extends Controller
{
    // --- DASHBOARD & CRUD SISWA ---
    public function index()
    {
        // Menampilkan aspirasi milik user yang sedang login dan belum selesai
        $aspirasis = InputAspirasi::with('kategori')
            ->where('id_user', Auth::id())
            ->where('status', '!=', 'Selesai') 
            ->latest()
            ->get();
            
        return view('siswa.dashboard', compact('aspirasis'));
    }

    public function create()
    {
        $kategoris = Kategori::all(); 
        return view('siswa.buat_aspirasi', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required',
            'lokasi'      => 'required',
            'keterangan'  => 'required',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $namaFoto = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/aspirasi', $namaFoto);
        }

        InputAspirasi::create([
            'id_user'     => Auth::id(),
            'id_kategori' => $request->id_kategori,
            'lokasi'      => $request->lokasi,
            'keterangan'  => $request->keterangan,
            'status'      => 'Pending',
            'foto'        => $namaFoto,
        ]);

        return redirect()->route('aspirasi.index')->with('success', 'Aspirasi berhasil dikirim!');
    }

    // --- LOGIKA VERIFIKASI SISWA (SETUJU/TOLAK) ---
    public function setujuiSelesai($id)
    {
        $aspirasi = InputAspirasi::where('id_pelaporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $aspirasi->update([
            'status' => 'Selesai',
            'tgl_selesai' => now(),
            'alasan_penolakan' => null // Bersihkan alasan karena sudah beres
        ]);

        return redirect()->route('aspirasi.riwayat')->with('success', 'Terima kasih! Laporan telah masuk ke riwayat.');
    }

    public function tolakSelesai(Request $request, $id)
    {
        // Validasi input 'alasan' dari form modal siswa
        $request->validate([
            'alasan' => 'required|string|min:5'
        ]);

        $aspirasi = InputAspirasi::where('id_pelaporan', $id)
            ->where('id_user', Auth::id())
            ->firstOrFail();

        $aspirasi->update([
            'status' => 'Diproses', // Kembalikan ke status diproses agar admin perbaiki
            'alasan_penolakan' => $request->alasan
        ]);

        return redirect()->route('aspirasi.index')->with('success', 'Laporan dikembalikan ke Admin dengan alasan penolakan.');
    }

    // --- ADMIN AREA ---
    public function adminIndex()
    {
        $laporan = InputAspirasi::with(['user', 'kategori'])
            ->where('status', '!=', 'Selesai')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('laporan'));
    }

    public function tanggapi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'foto_selesai' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pesan_admin' => 'nullable|string',
        ]);

        $aspirasi = InputAspirasi::findOrFail($id);
        
        // Jika admin pilih selesai, status teknisnya adalah Menunggu Verifikasi (dari siswa)
        $statusBaru = ($request->status == 'Selesai') ? 'Menunggu Verifikasi' : $request->status;

        $updateData = [
            'status' => $statusBaru,
            'pesan_admin' => $request->pesan_admin,
        ];

        // Jika status diubah ke Selesai/Menunggu Verifikasi, kita set tgl_selesai
        if ($request->status == 'Selesai') {
            $updateData['tgl_selesai'] = now();
            // Catatan: alasan_penolakan JANGAN di-null-kan di sini 
            // agar admin masih bisa baca alasan siswa saat proses perbaikan.
            // Baru di-null-kan saat siswa klik SETUJU.
        }

        if ($request->hasFile('foto_selesai')) {
            // Hapus foto lama jika ada
            if ($aspirasi->foto_selesai) {
                Storage::delete('public/bukti_selesai/' . $aspirasi->foto_selesai);
            }
            
            $file = $request->file('foto_selesai');
            $namaFotoSelesai = time() . '_bukti_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_selesai', $namaFotoSelesai);
            $updateData['foto_selesai'] = $namaFotoSelesai;
        }

        $aspirasi->update($updateData);

        return redirect()->route('admin.dashboard')->with('success', 'Tanggapan berhasil disimpan.');
    }

    // --- RIWAYAT AREA ---
    public function adminRiwayat()
    {
        $riwayat = InputAspirasi::with(['user', 'kategori'])
                    ->where('status', 'Selesai')
                    ->latest('updated_at')
                    ->get();
        return view('admin.riwayat', compact('riwayat'));
    }

    public function siswaRiwayat()
    {
        $riwayat = InputAspirasi::with('kategori')
                    ->where('id_user', Auth::id())
                    ->where('status', 'Selesai')
                    ->latest('updated_at')
                    ->get();
        return view('siswa.riwayat', compact('riwayat'));
    }
}