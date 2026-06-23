<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Konser;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;

class AdminController extends Controller
{
    use ApiResponse;

    public function dashboard()
    {
        // 1. FIX RELASI: Menghitung jumlah konser per artis lewat relasi 'konsers'
        // Jika di model Artist fungsinya bernama 'konser' (singular), ubah menjadi 'konser'
        $artists = Artist::withCount('konsers')->get();

        $konser = Konser::all();
        $tickets = Ticket::all();
        $users = User::all();

        // 2. OPTIMISASI N+1: Ambil data transaksi sekaligus data user & tiket terkait dalam 1 query
        $transaksi = Transaksi::with(['user', 'ticket'])->get();

        // 3. FIX LOGIKA BISNIS: Pendapatan hanya dihitung dari transaksi yang BENAR-BENAR lunas ('confirmed')
        $totalPendapatan = Transaksi::where('payment_status', 'confirmed')->sum('total_price') ?? 0;

        // Lempar data ke view admin.blade.php
        return view('admin', compact('artists', 'konser', 'tickets', 'users', 'transaksi', 'totalPendapatan'));
    }

    /**
     * Get dashboard statistics via REST API
     */
    public function stats()
    {
        $stats = [
            'total_artists' => Artist::count(),
            'total_konsers' => Konser::count(),
            'total_tickets' => Ticket::count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_transactions' => Transaksi::count(),

            // FIX LOGIKA BISNIS: Sinkronisasi revenue API hanya dari transaksi lunas
            'total_revenue' => Transaksi::where('payment_status', 'confirmed')->sum('total_price') ?? 0,

            'pending_payments' => Transaksi::where('payment_status', 'pending')->count(),
            'confirmed_payments' => Transaksi::where('payment_status', 'confirmed')->count(),
        ];

        return $this->apiSuccess($stats, 'Dashboard statistics retrieved', 200);
    }
}
