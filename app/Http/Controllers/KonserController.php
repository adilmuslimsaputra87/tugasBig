<?php

namespace App\Http\Controllers;

use App\Models\Konser;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Traits\ApiResponse;

class KonserController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $konsers = Konser::with('artist')->get();
        return response()->json($konsers);
    }

    public function create()
    {
        $artists = Artist::all();
        return view('admin.konsers.create', compact('artists'));
    }

    /**
     * Store a newly created resource in storage (Form-based).
     */
    public function store(Request $request)
    {
        if ($request->has('price')) {
            $cleanPrice = str_replace('.', '', $request->price);
            $request->merge(['price' => $cleanPrice]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artists_id' => 'required|exists:artists,id',
            'genre' => 'nullable|string|max:100',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trailer' => 'nullable|file|mimes:mp4,avi,mov|max:10240',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,sold_out,cancelled',
            'type' => 'required|in:lokal,internasional',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('konsers', 'public');
            $validated['image'] = $path;
        }

        if ($request->hasFile('trailer')) {
            $path = $request->file('trailer')->store('konsers/trailers', 'public');
            $validated['trailer'] = $path;
        }
// dd($validated);
        $konser = Konser::create($validated);

        return redirect('/admin')->with('success', 'Konser berhasil ditambahkan!');
    }

    /**
     * Store via REST API
     */
    public function storeApi(Request $request)
    {
        try {
            if ($request->has('price')) {
                $cleanPrice = str_replace('.', '', $request->price);
                $request->merge(['price' => $cleanPrice]);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'artists_id' => 'required|exists:artists,id',
                'genre' => 'nullable|string|max:100',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'venue' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'status' => 'required|in:draft,published,sold_out,cancelled',
                'type' => 'required|in:lokal,internasional',
                'trailer' => 'nullable|file|mimes:mp4,avi,mov|max:10240',

            ]);

            $konser = Konser::create($validated);

            return $this->apiSuccess($konser->load('artist'), 'Konser berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menambahkan konser: ' . $e->getMessage(), 422);
        }
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
        $artists = Artist::all();
        return view('admin.konsers.edit', compact('konser', 'artists'));
    }

    /**
     * Update the specified resource in storage (Form-based).
     */
    public function update(Request $request, Konser $konser)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artists_id' => 'nullable|exists:artists,id',
            'genre' => 'nullable|string|max:100',
            'date' => 'required|date',
            'time' => 'required',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'trailer' => 'nullable|file|mimes:mp4,avi,mov',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,sold_out,cancelled',
            'type' => 'required|in:lokal,internasional',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('konsers', 'public');
            $validated['image'] = $path;
        }

        if ($request->hasFile('trailer')) {
            $path = $request->file('trailer')->store('konsers/trailers', 'public');
            $validated['trailer'] = $path;
        }

        // dd($validated); // Debugging line to inspect the validated data

        $konser->update($validated);

        return redirect('/admin')->with('success', 'Konser berhasil diperbarui!');
    }

    /**
     * Update via REST API
     */
    public function updateApi(Request $request, Konser $konser)
    {
        try {
            if ($request->has('price')) {
                $cleanPrice = str_replace('.', '', $request->price);
                $request->merge(['price' => $cleanPrice]);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'artists_id' => 'nullable|exists:artists,id',
                'genre' => 'nullable|string|max:100',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'venue' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'description' => 'nullable|string',
                'image' => 'nullable|string',
                'trailer' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'capacity' => 'required|integer|min:1',
                'status' => 'required|in:draft,published,sold_out,cancelled',
                'type' => 'required|in:lokal,internasional',
            ]);

            $konser->update($validated);

            return $this->apiSuccess($konser->load('artist'), 'Konser berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui konser: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Remove the specified resource from storage (Form-based).
     */
    public function destroy(Konser $konser)
    {
        $konser->delete();

        return response()->json([
            'message' => 'Konser berhasil dihapus'
        ]);
    }

    /**
     * Delete via REST API
     */
    public function destroyApi(Konser $konser)
    {
        try {
            $konser->delete();

            return $this->apiSuccess(null, 'Konser berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus konser: ' . $e->getMessage(), 422);
        }
    }

    /**
     * Get konsers by type
     */
    public function getByType($type)
    {
        $konsers = Konser::with('artist')->where('type', $type)->orderBy('date', 'asc')->get();
        return response()->json($konsers);
    }

    /**
     * Get published konsers
     */
    public function getPublished()
    {
        $konsers = Konser::with('artist')->where('status', 'published')->orderBy('date', 'asc')->get();
        return response()->json($konsers);
    }
}
