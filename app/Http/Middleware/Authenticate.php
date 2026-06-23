<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $requestPath = $request->path();
        $message = 'Anda harus login terlebih dahulu.';

        // Tentukan pesan notifikasi berdasarkan halaman yang diakses
        if (str_contains($requestPath, ['beli-tiket', 'checkout', 'simpanTransaksi'])) {
            $message = 'Anda harus login untuk membeli tiket. Silakan login atau daftar akun baru.';
        } elseif (str_contains($requestPath, 'admin')) {
            $message = 'Area admin memerlukan login. Silakan login dengan akun admin Anda.';
        }

        return redirect('/dashboard')->with('notifLogin', $message);
    }
}
