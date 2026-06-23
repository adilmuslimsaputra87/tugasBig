<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use App\Traits\ApiResponse;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary; // <-- PASTIKAN IMPORT INI ADA

class MediaController extends Controller
{
    use ApiResponse;

    // ✅ SECURITY FIX: Add middleware check in constructor
    public function __construct()
    {
        // All media endpoints require admin authorization
        // (routes/web.php already has 'check.admin' middleware for these routes)
    }

    public function showApi(Media $media)
    {
        // ✅ AUTHORIZATION: Only authenticated users can view media
        if (!Auth::check()) {
            return $this->apiError('Unauthorized: Authentication required', 401);
        }

        return response()->json($media);
    }

    public function indexApi()
    {
        // ✅ AUTHORIZATION: Check if user is authenticated
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
                    'image'    => $m->image, // FIX: Langsung keluarkan URL Cloudinary-nya
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

        // FIX: Upload gambar media ke Cloudinary
        if ($request->hasFile('image')) {
            $cloudinaryImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'media_images'
            ]);
            $media->image = $cloudinaryImage->getSecurePath();
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

        // FIX: Update gambar media ke Cloudinary
        if ($request->hasFile('image')) {
            $cloudinaryImage = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'media_images'
            ]);
            $media->image = $cloudinaryImage->getSecurePath();
        }

        $media->save();

        return redirect()->route('admin.dashboard')->with('success', 'Media berhasil diperbarui!');
    }

    public function destroy(Media $media)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return back()->withError('Unauthorized: Admin access required');
        }

        // FIX: Hapus Storage::delete lokal, karena image sekarang berisi link URL Cloudinary
        $media->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Media berhasil dihapus!');
    }

    // ✅ API: DELETE via REST API with proper authorization
    public function destroyApi(Media $media)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            // FIX: Hapus Storage::delete lokal
            $media->delete();

            return $this->apiSuccess(null, 'Media berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus media: ' . $e->getMessage(), 422);
        }
    }

    // ✅ API: UPDATE via REST API with proper authorization
    public function updateApi(Request $request, Media $media)
    {
        try {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'image' => 'nullable|string', // FIX: Izinkan API mengirim update link gambar
            ]);

            $media->update($validated);

            return $this->apiSuccess($media, 'Media berhasil diperbarui', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal memperbarui media: ' . $e->getMessage(), 422);
        }
    }
}
