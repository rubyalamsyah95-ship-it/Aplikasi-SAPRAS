<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AspirasiController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\UsersController; 
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// --- RUTE EMERGENCY (Hanya untuk buat admin pertama kali di server) ---
// Kamu bisa hapus rute ini setelah berhasil login pertama kali
Route::get('/debug-admin', function () {
    $user = \App\Models\User::updateOrCreate(
        ['username' => 'admin'],
        [
            'name' => 'Administrator SAPRAS',
            'email' => 'admin@mail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123'),
            'role' => 'admin',
            'nis' => '000000',
            'status_akun' => 'aktif'
        ]
    );
    return "Akun Admin Berhasil Dibuat. Username: admin | Password: 123";
});

// Semua Route di bawah ini harus LOGIN (auth)
Route::middleware(['auth'])->group(function () {
    
    // --- LOGIKA REDIRECT DASHBOARD UTAMA ---
    // Ini adalah rute 'jembatan' agar redirect()->intended('/dashboard') tidak error
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (empty($user->role)) {
            return "Login Berhasil, tapi User ini TIDAK PUNYA ROLE di database.";
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'siswa') {
            return redirect()->route('aspirasi.index');
        }

        return "Role '" . $user->role . "' tidak dikenali.";
    })->name('dashboard');

    // --- GROUPING ADMIN (Hanya bisa diakses Admin) ---
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [AspirasiController::class, 'adminIndex'])->name('admin.dashboard');
        
        Route::get('/riwayat', [AspirasiController::class, 'adminRiwayat'])->name('admin.riwayat');
        Route::post('/tanggapi/{id}', [AspirasiController::class, 'tanggapi'])->name('admin.tanggapi');
        
        // Fitur Kelola User (Siswa)
        Route::get('/siswa', [UsersController::class, 'index'])->name('admin.siswa.index');
        Route::post('/siswa/store', [UsersController::class, 'store'])->name('admin.siswa.store'); 
        Route::post('/siswa/import', [UsersController::class, 'import'])->name('admin.siswa.import'); 
        Route::post('/siswa/reset/{id}', [UsersController::class, 'resetPassword'])->name('admin.siswa.reset'); 
        Route::delete('/siswa/destroy/{id}', [UsersController::class, 'destroy'])->name('admin.siswa.destroy'); 
        
        // Fitur Chatbot & AI
        Route::get('/chatbot', [ChatbotController::class, 'index'])->name('admin.chatbot');
        Route::post('/chatbot/tanya', [ChatbotController::class, 'tanya'])->name('chatbot.tanya');
    });

    // --- GROUPING SISWA (Hanya bisa diakses Siswa) ---
    Route::middleware(['role:siswa'])->prefix('aspirasi')->group(function () {
        // Dashboard Siswa (Menggunakan Aspirasi Index)
        Route::get('/', [AspirasiController::class, 'index'])->name('aspirasi.index');
        
        Route::get('/riwayat', [AspirasiController::class, 'siswaRiwayat'])->name('aspirasi.riwayat');
        Route::get('/buat', [AspirasiController::class, 'create'])->name('aspirasi.create');
        Route::post('/simpan', [AspirasiController::class, 'store'])->name('aspirasi.store');
        
        // Konfirmasi Penyelesaian
        Route::post('/setujui/{id}', [AspirasiController::class, 'setujuiSelesai'])->name('aspirasi.setujui');
        Route::post('/tolak/{id}', [AspirasiController::class, 'tolakSelesai'])->name('aspirasi.tolak');
    });

    // --- PROFILE MANAGEMENT (Bisa diakses keduanya) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';