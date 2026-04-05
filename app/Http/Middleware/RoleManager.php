<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Jika role user tidak sesuai dengan yang dibutuhkan route
        if ($userRole !== $role) {
            // Redirect siswa ke dashboard mereka jika mencoba akses admin
            if ($userRole === 'siswa') {
                return redirect()->route('aspirasi.index')->with('error', 'Anda tidak memiliki akses ke halaman Admin!');
            }
            // Redirect admin ke dashboard mereka jika mencoba akses area siswa (opsional)
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
