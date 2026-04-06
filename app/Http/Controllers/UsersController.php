<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
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

    public function import(Request $request)
    {
        $request->validate(['file_excel' => 'required|mimes:xlsx,xls']);
        try {
            Excel::import(new UsersImport, $request->file('file_excel'));
            return back()->with('success', 'Data siswa berhasil diimport!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimport data. Periksa format file.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|unique:users,nis',
            'email' => 'nullable|email|unique:users,email',
            'kelas' => 'required',
            'angkatan' => 'required|numeric',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->nis,
            'email' => $request->email,
            'password' => Hash::make($request->nis),
            'role' => 'siswa',
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'angkatan' => $request->angkatan,
            'status_akun' => 'aktif',
        ]);

        return back()->with('success', 'Murid ' . $request->name . ' berhasil ditambahkan!');
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate(['new_password' => 'required|min:4']);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->new_password)]);
        return back()->with('success', 'Password akun ' . $user->name . ' berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $namaSiswa = $user->name;
        $user->delete();
        return back()->with('success', 'Akun ' . $namaSiswa . ' telah dihapus.');
    }

    // FITUR BARU: Hapus Massal Per Angkatan
    public function destroyByAngkatan(Request $request)
    {
        $request->validate(['angkatan' => 'required|numeric']);
        $angkatan = $request->angkatan;

        $count = User::where('role', 'siswa')->where('angkatan', $angkatan)->count();

        if ($count == 0) {
            return back()->with('error', 'Tidak ada siswa ditemukan untuk angkatan ' . $angkatan);
        }

        User::where('role', 'siswa')->where('angkatan', $angkatan)->delete();

        return back()->with('success', "Berhasil menghapus $count akun siswa angkatan $angkatan.");
    }
}