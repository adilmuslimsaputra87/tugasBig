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
        // 1. OPTIMISASI: Load data Artist beserta jumlah konsernya langsung dari query
        // (Asumsi: nama relasi di model Artist adalah 'konsers' atau 'konser'. Jika berbeda, sesuaikan namanya)
        $artists = Artist::withCount('konsers')->get();

        $konser = Konser::all();
        $tickets = Ticket::all();
        $users = User::all();

        // 2. OPTIMISASI: Jika di halaman admin nanti menampilkan detail transaksi beserta nama User / nama Tiket,
        // sebaiknya gunakan eager loading 'with' agar query ke database tidak berulang-ulang (N+1 Query).
        // Contoh: Transaksi::with(['user', 'ticket'])->get();
        $transaksi = Transaksi::all();

        // 3. OPTIMISASI: Beri fallback ?? 0. Jika tabel transaksi masih kosong, nilainya tidak null melainkan 0.
        $totalPendapatan = Transaksi::sum('total_price') ?? 0;

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
            'total_revenue' => Transaksi::sum('total_price') ?? 0,
            'pending_payments' => Transaksi::where('payment_status', 'pending')->count(),
            'confirmed_payments' => Transaksi::where('payment_status', 'confirmed')->count(),
        ];

        return $this->apiSuccess($stats, 'Dashboard statistics retrieved', 200);
    }
}
