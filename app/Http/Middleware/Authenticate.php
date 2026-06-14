<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Authenticate (custom)
 *
 * ============================================================
 * Override redirectTo() bawaan Laravel
 * ============================================================
 *
 * Secara default, jika user belum login dan mengakses route
 * yang dilindungi middleware "auth", Laravel akan redirect ke
 * route bernama "login" — yang TIDAK ADA di project ini
 * (project ini menggunakan route "admin.login").
 *
 * Tanpa override ini, Laravel akan melempar:
 *   "Route [login] not defined." (Error 500)
 * atau menyebabkan redirect ke URL yang salah / 404
 * seperti pada kasus akses /admin/dashboard tanpa sesi.
 *
 * Dengan override ini, setiap kali middleware "auth" memblokir
 * akses (misal /admin/dashboard atau /admin/mitras tanpa login),
 * user akan diarahkan dengan benar ke:
 *
 *      /admin/login
 *
 * dan setelah berhasil login, redirect()->intended() akan
 * mengembalikan user ke halaman yang awalnya ingin diakses.
 */
class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
}
