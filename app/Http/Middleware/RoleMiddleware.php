<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Pengecekan untuk Super Admin (Guard: superadmin)
        if ($role === 'super_admin' && !Auth::guard('superadmin')->check()) {
            return redirect()->route('superadmin.login')
                ->withErrors(['email' => 'Anda tidak memiliki akses. Harap login sebagai Super Admin.']);
        }

        // 2. Pengecekan untuk Mitra / Pemilik Kost (Guard: mitra)
        if ($role === 'admin_kost' && !Auth::guard('mitra')->check()) {
            return redirect()->route('mitra.login')
                ->withErrors(['email' => 'Anda tidak memiliki akses. Harap login sebagai Mitra Pemilik Kost.']);
        }

        // 3. Pengecekan untuk Pencari Kost (Guard: web)
        if ($role === 'pencari_kost' && !Auth::guard('web')->check()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Silakan login terlebih dahulu untuk mengakses halaman ini.']);
        }

        // Jika lolos pengecekan guard sesuai rolenya, izinkan akses ke halaman
        return $next($request);
    }
}