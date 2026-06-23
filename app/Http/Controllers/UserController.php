<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Artist;
use App\Models\Konser;
use App\Models\Transaksi;
use App\Models\Ticket;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function dashboard()
    {
        $konsers = Konser::all();
        $artists = Artist::all();
        $tiket = Ticket::all();

        // FIX BUG 3: Hanya ambil transaksi milik user yang sedang login agar tidak bocor
        $transaksi = Auth::check()
            ? Transaksi::where('users_id', Auth::id())->with('ticket.konser')->get()
            : collect();

        return view('welcome', compact('konsers', 'transaksi', 'artists', 'tiket'));
    }

    public function index()
    {
        // Pastikan hanya admin (Web Guard) yang bisa masuk halaman manajemen user
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('admin', compact('users'));
    }

    /**
     * Get users via REST API (admin only)
     */
    public function indexApi()
    {
        // FIX BUG 1: Proteksi API dari user biasa atau guest
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return $this->apiError('Unauthorized: Admin access required', 403);
        }

        $users = User::select('id', 'first_name', 'last_name', 'email', 'phone', 'role', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                ];
            });

        return response()->json($users);
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect('/admin')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function register(Request $request)
    {
        // FIX BUG 2: Tambahkan unique:users,email agar tidak crash saat email ganda
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $validated['status'] = 'active';

        try {
            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    public function show(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($user);
    }

    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect('/admin')->with('success', 'Data user berhasil diperbarui!');
    }

    public function simpanProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // FIX BUG 4: Jangan paksa ganti role lewat validasi agar admin tidak turun jabatan
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect('/')->with('success', 'Profile berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $user->delete();
        return redirect('/admin')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Delete via REST API
     */
    public function destroyApi(User $user)
    {
        try {
            // FIX BUG 1: Tambahkan gerbang keamanan Admin pada API destroy
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return $this->apiError('Unauthorized: Admin access required', 403);
            }

            $user->delete();
            return $this->apiSuccess(null, 'User berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus user: ' . $e->getMessage(), 422);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return $this->apiSuccess($user, 'Login berhasil', 200);
            }

            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Login berhasil');
            } else {
                return redirect('/dashboard')->with('success', 'Login berhasil');
            }
        }

        if ($request->expectsJson()) {
            return $this->apiError('gagal masuk derr', 401);
        }

        return back()
            ->withErrors(['email' => 'gagal masuk derr'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return $this->apiSuccess(null, 'Logout berhasil', 200);
        }

        return redirect('/dashboard')->with('success', 'Logout berhasil');
    }
}
