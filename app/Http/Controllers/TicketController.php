<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Konser;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('konser')->get();
        return response()->json($tickets);
    }

    public function create()
    {
        $konsers = Konser::all();
        return view('admin.tickets.create', compact('konsers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'promo_price' => 'nullable|numeric|min:0',
            'promo_valid_until' => 'nullable|date',
            'max_purchase' => 'required|integer|min:1|max:100',
        ]);

       $ticket = Ticket::create($validated);

        return redirect('/admin')->with('success', 'Kategori tiket berhasil ditambahkan!');
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load('konser'));
    }

    public function edit(Ticket $ticket)
    {
        $konsers = Konser::all();
        return view('admin.tickets.edit', compact('ticket', 'konsers'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'konser_id' => 'required|exists:konsers,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'promo_price' => 'nullable|numeric|min:0',
            'promo_valid_until' => 'nullable|date',
            'max_purchase' => 'required|integer|min:1|max:100',
        ]);

     $ticket->update($validated);

        return redirect('/admin')->with('success', 'Kategori tiket berhasil diperbarui!');
    }

public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        // Ganti ke ini jika tombol hapus Anda menggunakan Form HTML biasa
        return redirect('/admin/tickets')->with('success', 'Tiket berhasil dihapus!');
    }
    public function getByKonser($konserID)
    {
        $konserEksis = Konser::find($konserID);

    if (!$konserEksis) {
        return response()->json([
            'message' => 'Konser tidak ditemukan'
        ], 404);
    }

    $tickets = Ticket::where('konser_id', $konserID)->get();

    return response()->json($tickets);
    }
}
