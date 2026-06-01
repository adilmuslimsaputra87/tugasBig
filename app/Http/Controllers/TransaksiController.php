<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Ticket;

class TransaksiController extends Controller
{
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

    public function simpanTransaksi(Request $request)
    {
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

        $ticket = Ticket::findOrFail($validated['ticket_id']);
        $totalPrice = ($ticket->price * $validated['quantity']) + 10000;

        $transaction = Transaksi::create([
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
            'payment_date'   => now(),
        ]);

        return redirect('/dashboard')->with('success', 'Transaksi berhasil disimpan');
    }
}
