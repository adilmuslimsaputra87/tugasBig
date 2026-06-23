<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArtistController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $artists = Artist::all();
        return response()->json($artists);
    }

    public function create()
    {
        return view('admin.artists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'country' => 'required|in:indonesia,internasional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            // Upload ke Cloudinary dan masukkan ke folder 'artists'
            $cloudinaryImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'artists'
            ]);
            // Ambil URL secure https
            $validated['image'] = $cloudinaryImage->getSecurePath();
        }

        $artist = Artist::create($validated);

        return redirect('/admin')->with('success', 'Artis berhasil ditambahkan!');
    }

    /**
     * Store via REST API
     */
    public function storeApi(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'genre' => 'nullable|string|max:100',
                'country' => 'required|in:indonesia,internasional',
                'image' => 'nullable|string', // API menerima string/URL langsung
                'bio' => 'nullable|string',
                'instagram' => 'nullable|string|max:100',
            ]);

            $artist = Artist::create($validated);

            return $this->apiSuccess($artist, 'Artis berhasil ditambahkan', 201);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menambahkan artis: ' . $e->getMessage(), 422);
        }
    }

    public function show(Artist $artist)
    {
        return response()->json($artist);
    }

    public function edit(Artist $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'country' => 'required|in:indonesia,internasional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            // FIX: Sekarang proses update juga upload langsung ke Cloudinary
            $cloudinaryImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'artists'
            ]);
            $validated['image'] = $cloudinaryImage->getSecurePath();
        }

        $artist->update($validated);

        return redirect('/admin')->with('success', 'Artis berhasil diperbarui!');
    }

    /**
     * Update via REST API
     */
    public function updateApi(Request $request, Artist $artist)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'genre' => 'nullable|string|max:100',
                'country' => 'required|in:indonesia,internasional',
                'image' => 'nullable|string',
                'bio' => 'nullable|string',
                'instagram' => 'nullable|string|max:100',
            ]);

            $artist->update($validated);

            return $this->apiSuccess($artist, 'Artis berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui artis: ' . $e->getMessage(), 422);
        }
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return redirect('/admin/artists')->with('success', 'Artis berhasil dihapus!');
    }

    /**
     * Delete via REST API
     */
    public function destroyApi(Artist $artist)
    {
        try {
            $artist->delete();

            return $this->apiSuccess(null, 'Artis berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus artis: ' . $e->getMessage(), 422);
        }
    }
}
