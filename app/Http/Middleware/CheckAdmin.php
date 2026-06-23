<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Verifikasi user adalah admin
     * 
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Middleware 'auth' sudah mengecek login, jadi tinggal cek role
        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        // User login tapi bukan admin
        return redirect('/dashboard')
            ->with('notifError', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
    }
}
