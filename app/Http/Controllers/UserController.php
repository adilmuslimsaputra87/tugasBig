<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Artist;
use App\Models\Konser;
use App\Models\Transaksi;

class UserController extends Controller
{
    public function dashboard()
    {
        $konsers = Konser::all();
        $transaksi = Transaksi::all();
        $artists = Artist::all();

        return view('welcome', compact('konsers', 'transaksi', 'artists'));
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
        $user = User::create($validated);

        return redirect('/admin')->with('success', 'User baru berhasil ditambahkan!');
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
    public function destroy(User $user)
    {
        $user->delete();
        return redirect('/admin')->with('success', 'User berhasil dihapus!');
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $validated['status'] = 'active';

        $user = User::create($validated);

        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil');
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
            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Login berhasil');
            } else {
                return redirect('/dashboard')->with('success', 'Login berhasil');
            }
        }

        return back()->withError(['message' => 'Login gagal'], 401);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dashboard')->with('success', 'Logout berhasil');
    }
}
