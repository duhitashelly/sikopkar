<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
    
        // Jika pengguna belum login, arahkan ke halaman login
        if (!$user) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Ambil nama route saat ini
        $routeName = $request->route() ? $request->route()->getName() : null;
    
        // Jika nama route tidak ditemukan, izinkan melanjutkan tanpa pembatasan
        if (!$routeName) {
            return $next($request);
        }
    
        // akses batas kepala koperasi ke anggota.
        if (str_starts_with($routeName, 'anggota.') && $user->isKepalaKoperasi()) {
            return redirect('/home')->with('error', 'Kepala Koperasi tidak dapat mengakses halaman anggota.');
        }

        // akses batas kepala koperasi ke pengguna.
        if (str_starts_with($routeName, 'pengguna.') && $user->isKepalaKoperasi()) {
            return redirect('/home')->with('error', 'Kepala Koperasi tidak dapat mengakses halaman pengguna.');
        }

        // akses batas kepala koperasi ke simpanan.
        if (str_starts_with($routeName, 'simpanan.') && $user->isKepalaKoperasi()) {
            return redirect('/home')->with('error', 'Kepala Koperasi tidak dapat mengakses halaman simpanan.');
        }

        // akses batas kepala koperasi ke pinjaman.
        if (str_starts_with($routeName, 'pinjaman.') && $user->isKepalaKoperasi()) {
            return redirect('/home')->with('error', 'Kepala Koperasi tidak dapat mengakses halaman pinjaman.');
        }

        // akses batas kepala koperasi ke angsuran.
        if (str_starts_with($routeName, 'angsuran.') && $user->isKepalaKoperasi()) {
            return redirect('/home')->with('error', 'Kepala Koperasi tidak dapat mengakses halaman pinjaman.');
        }

        return $next($request);
    }
}
