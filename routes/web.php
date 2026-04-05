<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- KODE PENYELAMATAN (Hapus setelah berhasil login) ---
Route::get('/debug-admin', function () {
    try {
        $user = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator SAPRAS',
                'email' => 'admin@mail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
                'nis' => '000000', // Sesuaikan jika tabel user kamu punya kolom NIS
            ]
        );
        return "SUKSES: Akun Admin Berhasil Dibuat/Diperbarui. <br> Username: <b>admin</b> <br> Password: <b>123</b> <br><br> <a href='/login'>Klik di sini untuk Login</a>";
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});
// -------------------------------------------------------

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';