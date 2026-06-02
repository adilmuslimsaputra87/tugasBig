<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use Illuminate\Http\Request;
use App\Models\Artist;

class KonserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konsers = Konser::all();
        return response()->json($konsers);
    }

    public function create()
    {
        $artists = Artist::all();
        return view('admin.konsers.create', compact('artists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->has('price')) {
        $cleanPrice = str_replace('.', '', $request->price);
        $request->merge(['price' => $cleanPrice]);
    }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,sold_out,cancelled',
            'type' => 'required|in:lokal,internasional',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('konsers', 'public');
            $validated['image'] = $path;
        }

        // dd($validated);

       $konser = Konser::create($validated);

        // Mengalihkan halaman kembali ke daftar utama admin
        return redirect('/admin')->with('success', 'Konser berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Konser $konser)
    {
        return response()->json($konser);
    }

    public function edit(Konser $konser)
    {
        return view('admin.konsers.edit', compact('konser'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Konser $konser)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,sold_out,cancelled',
            'type' => 'required|in:lokal,internasional',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('konsers', 'public');
            $validated['image'] = $path;
        }

        $konser->update($validated);

        // Mengalihkan halaman kembali ke daftar utama admin
        return redirect('/admin')->with('success', 'Konser berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Konser $konser)
    {
        $konser->delete();

        return response()->json([
            'message' => 'Konser berhasil dihapus'
        ]);
    }

    /**
     * Get konsers by type
     */
    public function getByType($type)
    {
        $konsers = Konser::where('type', $type)->where('status', 'published')->get();
        return response()->json($konsers);
    }

    /**
     * Get published konsers
     */
    public function getPublished()
    {
        $konsers = Konser::where('status', 'published')->orderBy('date', 'asc')->get();
        return response()->json($konsers);
    }
}
