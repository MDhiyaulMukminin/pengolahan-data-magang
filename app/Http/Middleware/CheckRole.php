<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan import ini
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user belum login
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        // Cek apakah role user sesuai
        $userRole = Auth::user()->role->nama; // Ubah 'nama' ke 'name' sesuai migration

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}