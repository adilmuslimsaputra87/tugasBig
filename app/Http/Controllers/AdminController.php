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
        // Middleware 'check.admin' sudah menangani verifikasi login & role admin
        $artists = Artist::all();
        $konser = Konser::all();
        $tickets = Ticket::all();
        $users = User::all();
        $transaksi = Transaksi::all();

        // Hitung total pendapatan dari pembelian tiket
        $totalPendapatan = Transaksi::sum('total_price');

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
