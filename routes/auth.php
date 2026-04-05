<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    /**
     * HALAMAN LOGIN
     * Registrasi ditiadakan karena akun dibuat langsung oleh Admin melalui database/seeder.
     */
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    /**
     * UPDATE PASSWORD
     * Fitur agar user bisa mengganti password mereka sendiri setelah login.
     */
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    /**
     * LOGOUT
     * Mengakhiri sesi user dan kembali ke halaman login.
     */
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});