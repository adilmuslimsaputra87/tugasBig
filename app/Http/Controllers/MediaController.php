<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle Storage
use App\Traits\ApiResponse;

class MediaController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        // All media endpoints require admin authorization
    }

    public function showApi(Media $media)
    {
        if (!Auth::check()) {
            return $this->apiError('Unauthorized: Authentication required', 401);
        }

        // Return URL Supabase Storage di API Single View jika dibutuhkan
        if ($media->image) {
            $media->image_url = Storage::url($media->image);
        }

        return response()->json($media);
    }

    public function indexApi()
    {
        if (!Auth::check()) {
            return $this->apiError('Unauthorized: Authentication required', 401);
        }

        $media = Media::select('id', 'name', 'location', 'image')
            ->get()
            ->map(function ($m) {
                return [
                    'id'       => $m->id,
                    'name'     => $m->name,
                    'location' => $m->location,
                    // ✅ FIX: Gunakan Storage::url() untuk mendapatkan link publik dari Supabase
                    'image'    => $m->image ? Storage::url($m->image) : null,
                ];
            });

        return response()->json($media);
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->withError('Unauthorized: Admin access required');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $media = new Media();
        $media->name = $request->name;
        $media->location = $request->location;

        if ($request->hasFile('image')) {
            // ✅ FIX: Upload langsung ke folder 'media_images' di Supabase Storage
            // Jika di .env FILESYSTEM_DISK=supabase, cukup store('media_images')
            // Di bawah ini ditulis eksplisit agar aman terarah ke disk supabase
            $imagePath = $request->file('image')->store('media_images', 'supabase');
            $media->image = $imagePath; // Menyimpan path: "media_images/nama_file.jpg"
        }

        $media->save();

        return redirect()->route('admin.dashboard')->with('success', 'Media berhasil ditambahkan!');
    }

    public function editMedia(Media $media)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->withError('Unauthorized: Admin access required');
        }

        return view('admin.media.edit', compact('media'));
    }

    public function simpanEditMedia(Request $request, Media $media)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->withError('Unauthorized: Admin access required');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $media->name = $request->name;
        $media->location = $request->location;

        if ($request->hasFile('image')) {
            // ✅ FIX: Hapus gambar lama dari cloud Supabase Storage jika ada
            if ($media->image) {
                Storage::disk('supabase')->delete($media->image);
            }
            // Upload gambar baru ke Supabase
            $imagePath = $request->file('image')->store('media_images', 'supabase');
            $media->image = $imagePath;
        }

        $media->save();

        return redirect()->route('admin.dashboard')->with('success', 'Media berhasil diperbarui!');
    }

    public function destroy(Media $media)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->withError('Unauthorized: Admin access required');
        }

        // ✅ FIX: Hapus file dari Supabase Storage sebelum hapus baris database
        if ($media->image) {
            Storage::disk('supabase')->delete($media->image);
        }

        $media->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Media berhasil dihapus!');
    }

    // ✅ API: DELETE via REST API
    public function destroyApi(Media $media)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            // ✅ FIX: Hapus file dari Supabase Storage
            if ($media->image) {
                Storage::disk('supabase')->delete($media->image);
            }

            $media->delete();

            return $this->apiSuccess(null, 'Media berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus media: ' . $e->getMessage(), 422);
        }
    }

    // ✅ API: UPDATE via REST API
    public function updateApi(Request $request, Media $media)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
            ]);

            $media->update($validated);

            return $this->apiSuccess($media, 'Media berhasil diperbarui', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui media: ' . $e->getMessage(), 422);
        }
    }
}
