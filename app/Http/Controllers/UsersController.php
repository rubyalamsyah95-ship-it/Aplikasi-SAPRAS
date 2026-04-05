<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Menampilkan daftar siswa dengan fitur pencarian.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $siswas = User::where('role', 'siswa')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('nis', 'like', "%$search%")
                      ->orWhere('kelas', 'like', "%$search%")
                      ->orWhere('angkatan', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            })
            ->orderBy('angkatan', 'desc')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.kelola-siswa', compact('siswas'));
    }

    /**
     * Memproses Import Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file_excel'));
            return back()->with('success', 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data. Pastikan format file benar atau tidak ada email/NIS duplikat.');
        }
    }

    /**
     * Menambah satu murid secara manual (Murid Pindahan/Baru).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'nis'      => 'required|unique:users,nis',
            'email'    => 'nullable|email|unique:users,email',
            'kelas'    => 'required',
            'angkatan' => 'required|numeric',
        ]);

        User::create([
            'name'        => $request->name,
            'username'    => $request->nis, // Username default menggunakan NIS
            'email'       => $request->email,
            'password'    => Hash::make($request->nis), // Password default awal adalah NIS
            'role'        => 'siswa',
            'nis'         => $request->nis,
            'kelas'       => $request->kelas,
            'angkatan'    => $request->angkatan,
            'status_akun' => 'aktif',
        ]);

        return back()->with('success', 'Murid ' . $request->name . ' berhasil ditambahkan!');
    }

    /**
     * Update/Reset password siswa dengan input manual dari Admin.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|min:4'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password akun ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus akun siswa.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $namaSiswa = $user->name;
        $user->delete();

        return back()->with('success', 'Akun ' . $namaSiswa . ' telah dihapus dari sistem.');
    }
}