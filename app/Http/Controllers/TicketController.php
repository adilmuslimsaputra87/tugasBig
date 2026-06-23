<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Konser;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class TicketController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $tickets = Ticket::with('konser')->get();
        return response()->json($tickets);
    }

    public function create()
    {
        $konsers = Konser::with('artist')->get();
        return view('admin.tickets.create', compact('konsers'));
    }

    public function store(Request $request)
    {
        // Panggil helper pembersih titik harga
        $this->cleanPriceInputs($request);

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

    /**
     * Store via REST API
     */
    public function storeApi(Request $request)
    {
        try {
            $this->cleanPriceInputs($request);

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

            return $this->apiSuccess($ticket->load('konser'), 'Kategori tiket berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menambahkan tiket: ' . $e->getMessage(), 422);
        }
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket->load('konser'));
    }

    public function edit(Ticket $ticket)
    {
        // FIX: Samakan dengan create(), eager load artist-nya
        $konsers = Konser::with('artist')->get();
        return view('admin.tickets.edit', compact('ticket', 'konsers'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $this->cleanPriceInputs($request);

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

    /**
     * Update via REST API
     */
    public function updateApi(Request $request, Ticket $ticket)
    {
        try {
            $this->cleanPriceInputs($request);

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

            return $this->apiSuccess($ticket->load('konser'), 'Kategori tiket berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui tiket: ' . $e->getMessage(), 422);
        }
    }

    public function destroy(Ticket $ticket)
    {
        // FIX: Hapus baris findOrFail yang redundan. Langsung tembak delete.
        $ticket->delete();
        return redirect('/admin')->with('success', 'Tiket ' . $ticket->name . ' berhasil dihapus!');
    }

    /**
     * Delete via REST API
     */
    public function destroyApi(Ticket $ticket)
    {
        try {
            $ticket->delete();

            return $this->apiSuccess(null, 'Tiket berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus tiket: ' . $e->getMessage(), 422);
        }
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

    /**
     * Get ticket prices for a concert (alias for getByKonser)
     */
    public function getHarga($konserID)
    {
        return $this->getByKonser($konserID);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Membersihkan format titik pada input harga & harga promo
     */
    private function cleanPriceInputs(Request $request): void
    {
        if ($request->filled('price')) {
            $request->merge(['price' => str_replace('.', '', $request->price)]);
        }

        if ($request->filled('promo_price')) {
            $request->merge(['promo_price' => str_replace('.', '', $request->promo_price)]);
        }
    }
}
