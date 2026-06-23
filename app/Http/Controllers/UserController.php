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
        $transaksi = Transaksi::all();
        $artists = Artist::all();
        $tiket = Ticket::all();

        return view('welcome', compact('konsers', 'transaksi', 'artists', 'tiket'));
    }

    public function index()
    {
        $users = User::all();
        return view('admin', compact('users'));
    }

    /**
     * Get users via REST API (admin only)
     */
    public function indexApi()
    {
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
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
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
        // $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name']; // Menjaga sinkronisasi kolom 'name' saat menambahkan user baru

        // dd($validated);

        $user = User::create($validated);

        return redirect('/admin')->with('success', 'User baru berhasil ditambahkan!');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $validated['status'] = 'active';
        // $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name']; // Menjaga sinkronisasi kolom 'name' saat menambahkan user baru

        // dd($validated);
        try {
            $user = User::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => $user
            ], 201); // Status code 201: Created

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
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
        $user = auth()->user();
        // dd($request->all());
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'user',
            'status' => 'active',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // dd($validated);

        User::where('id', $user->id)->update($validated);
        return redirect('/')->with('success', 'Profile berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin')->with('success', 'User berhasil dihapus!');
    }

    /**
     * Delete via REST API
     */
    public function destroyApi(User $user)
    {
        try {
            $user->delete();

            return $this->apiSuccess(null, 'User berhasil dihapus', 200);
        } catch (\Exception $e) {
            return $this->apiError('Gagal menghapus user: ' . $e->getMessage(), 422);
        }
    }

public function login(Request $request)
    {
        // 1. Validasi Inputan
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2. Cari User Berdasarkan Email
        $user = User::where('email', $credentials['email'])->first();

        // 3. Cek Password & Proses Login Berhasil
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();

            // Respon jika sukses (API Request)
            if ($request->expectsJson()) {
                return $this->apiSuccess($user, 'Login berhasil', 200);
            }

            // Respon jika sukses (Web Biasa) berdasarkan Role
            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Login berhasil');
            } else {
                return redirect('/dashboard')->with('success', 'Login berhasil');
            }
        }

        // ========================================================
        // JIKA LOGIN GAGAL (Kredensial salah / User tidak ditemukan)
        // ========================================================

        // 4. Respon jika gagal (API Request)
        if ($request->expectsJson()) {
            return $this->apiError('gagal masuk derr', 401);
        }

        // 5. Respon jika gagal (Web Biasa)
        // Mengubah pesan agar sesuai dengan penangkap notifikasi JavaScript kamu
        return back()
            ->withErrors(['email' => 'gagal masuk derr'])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Respon jika logout sukses (API Request)
        if ($request->expectsJson()) {
            return $this->apiSuccess(null, 'Logout berhasil', 200);
        }

        // Respon jika logout sukses (Web Biasa)
        return redirect('/dashboard')->with('success', 'Logout berhasil');
    }
}
