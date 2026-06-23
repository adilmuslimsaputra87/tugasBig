<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Ticket;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // <-- Tambahkan ini untuk handle validasi tanggal promo

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

                // 1. Ambil data tiket dan KUNCI baris database (lockForUpdate)
                $ticket = Ticket::lockForUpdate()->findOrFail($validated['ticket_id']);

                // 2. Cek apakah sisa stok saat ini mencukupi kuantitas pembelian
                if ($ticket->stock < $validated['quantity']) {
                    throw new \Exception('Maaf, stok tiket tidak mencukupi atau sudah habis.');
                }

                // FIX BUG 2: Cek apakah tiket sedang dalam masa promo yang valid
                $hargaAktif = $ticket->price;
                if ($ticket->promo_price && (!$ticket->promo_valid_until || Carbon::now()->lte(Carbon::parse($ticket->promo_valid_until)))) {
                    $hargaAktif = $ticket->promo_price;
                }

                // 3. Hitung total harga menggunakan harga yang aktif (normal / promo)
                $totalPrice = ($hargaAktif * $validated['quantity']) + 10000;

                // 4. Kurangi stok tiket di database saat ini juga
                $ticket->decrement('stock', $validated['quantity']);

                // 5. Simpan data transaksi ke database
                return Transaksi::create([
                    'users_id'       => Auth::id(), // Konsisten pakai Auth::id()
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
                    'sold'           => true,
                    'payment_date'   => Carbon::now(),
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
            // FIX BUG 3: Proteksi defense-in-depth khusus Admin
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            $validated = $request->validate([
                'payment_status' => 'required|in:pending,confirmed,rejected',
            ]);

            // FIX BUG 1: DB Transaction untuk pengembalian stok jika status berubah jadi 'rejected'
            DB::transaction(function () use ($transaksi, $validated) {
                $statusLama = $transaksi->payment_status;
                $statusBaru = $validated['payment_status'];

                // Jika status berubah dari TIDAK rejected menjadi REJECTED, kembalikan stok tiket
                if ($statusLama !== 'rejected' && $statusBaru === 'rejected') {
                    $ticket = Ticket::lockForUpdate()->find($transaksi->tickets_id);
                    if ($ticket) {
                        $ticket->increment('stock', $transaksi->quantity);
                    }
                }
                // Antisipasi jika admin salah klik (dari rejected mau dibalikin ke confirmed/pending lagi)
                // Stok harus dikurangi kembali setelah memastikan stoknya masih tersedia
                elseif ($statusLama === 'rejected' && $statusBaru !== 'rejected') {
                    $ticket = Ticket::lockForUpdate()->find($transaksi->tickets_id);
                    if ($ticket && $ticket->stock >= $transaksi->quantity) {
                        $ticket->decrement('stock', $transaksi->quantity);
                    } else {
                        throw new \Exception('Gagal mengubah status. Stok tiket saat ini sudah tidak mencukupi untuk memulihkan transaksi.');
                    }
                }

                $transaksi->update($validated);
            });

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
