<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RedirectIfAuthenticated
 *
 * ============================================================
 * SESI ADMIN — Cegah akses ulang ke halaman login
 * ============================================================
 *
 * Middleware ini memastikan: JIKA admin SUDAH LOGIN (memiliki
 * sesi aktif), maka mengakses /admin/login akan otomatis
 * di-redirect ke /admin/dashboard — TIDAK menampilkan form
 * login lagi.
 *
 * Admin HANYA bisa kembali ke halaman login setelah:
 *   1. Klik tombol "Logout" (menghancurkan session), ATAU
 *   2. Session expired/invalid.
 *
 * Middleware ini setara dengan middleware "guest" bawaan Laravel,
 * namun dibuat eksplisit di sini agar mudah dipahami & disesuaikan
 * (misalnya jika nanti ada multi-guard).
 */
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
