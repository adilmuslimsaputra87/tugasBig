<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Ticket;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    use ApiResponse;

    /**
     * Get transactions for the authenticated user
     */
    public function index()
    {
        $transactions = Transaksi::where('users_id', Auth::id())
            ->with('ticket.konser')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($transactions);
    }

    public function checkout(Request $request)
    {
        $ticketId = $request->query('ticketId');
        $qty = max(1, intval($request->query('qty', 1)));
        $ticket = null;

        if ($ticketId) {
            $ticket = Ticket::with('konser')->find($ticketId);
        }

        return view('checkout', compact('ticket', 'qty'));
    }

    /**
     * Store transaction via form
     */
    public function store(Request $request)
    {
        return $this->simpanTransaksi($request);
    }

    /**
     * Save transaction (Form-based and API)
     */
    public function simpanTransaksi(Request $request)
{
    try {
        $validated = $request->validate([
            'ticket_id'         => 'required|exists:tickets,id',
            'nama_depan'        => 'required|string|max:255',
            'nama_belakang'     => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'nomor_hp'          => 'required|string|max:20',
            'nik'               => 'nullable|string|max:20',
            'quantity'          => 'required|integer|min:1',
            'metode_pembayaran' => 'required|in:bca,gopay,qris',
            'kode_promo'        => 'nullable|string|max:50',
        ]);

        // Gunakan DB Transaction agar jika salah satu proses gagal, database dibatalkan otomatis
        $transaction = DB::transaction(function () use ($validated) {

            // 1. Ambil data tiket dan KUNCI baris database (lockForUpdate) untuk menghindari race condition
            $ticket = Ticket::lockForUpdate()->findOrFail($validated['ticket_id']);

            // 2. Cek apakah sisa stok saat ini mencukupi kuantitas pembelian
            if ($ticket->stock < $validated['quantity']) {
                throw new \Exception('Maaf, stok tiket tidak mencukupi atau sudah habis.');
            }

            // 3. Hitung total harga
            $totalPrice = ($ticket->price * $validated['quantity']) + 10000;

            // 4. Kurangi stok tiket di database saat ini juga
            $ticket->decrement('stock', $validated['quantity']);

            // 5. Simpan data transaksi ke database
            return Transaksi::create([
                'users_id'       => auth()->id(),
                'tickets_id'     => $validated['ticket_id'],
                'first_name'     => $validated['nama_depan'],
                'last_name'      => $validated['nama_belakang'],
                'email'          => $validated['email'],
                'phone_number'   => $validated['nomor_hp'],
                'nik'            => $validated['nik'],
                'quantity'       => $validated['quantity'],
                'total_price'    => $totalPrice,
                'payment_method' => $validated['metode_pembayaran'],
                'payment_status' => 'pending',
                'sold'           => true, // Sudah pasti true karena stok lolos validasi di atas
                'payment_date'   => now(),
            ]);
        });

        // Return JSON jika request berupa API
        if ($request->expectsJson()) {
            return $this->apiSuccess($transaction->load('ticket.konser'), 'Transaksi berhasil disimpan', 201);
        }

        return redirect('/dashboard')->with('success', 'Transaksi berhasil disimpan');

    } catch (\Exception $e) {
        if ($request->expectsJson()) {
            return $this->apiError('Gagal menyimpan transaksi: ' . $e->getMessage(), 422);
        }
        return back()->with('error', $e->getMessage())->withInput();
    }
}

    /**
     * Update transaction status via REST API (admin only)
     */
    public function updateApi(Request $request, Transaksi $transaksi)
    {
        try {
            $validated = $request->validate([
                'payment_status' => 'required|in:pending,confirmed,rejected',
            ]);

            $transaksi->update($validated);

            return $this->apiSuccess($transaksi->load('ticket.konser'), 'Status transaksi berhasil diperbarui', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui status transaksi: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Get transaction details via REST API
     */
    public function showApi(Transaksi $transaksi)
    {
        return $this->apiSuccess($transaksi->load('ticket.konser'), 'Detail transaksi', 200);
    }

    public function detailTransaksi($id)
    {
        $transaction = Transaksi::with('ticket.konser')->findOrFail($id);

        // Pastikan user hanya bisa melihat transaksi miliknya sendiri
        if ($transaction->users_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('detailTransaksi', compact('transaction'));
    }
}
