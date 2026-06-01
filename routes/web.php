<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransaksiController;

Route::get('/dashboard',[UserController::class, 'dashboard'])->name('dashboard');

Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Konser Routes
Route::resource('konsers', KonserController::class);
Route::get('api/konsers/create', [KonserController::class, 'create']);
Route::get('api/konsers/{konser}/edit', [KonserController::class, 'edit']);
Route::get('api/konsers', [KonserController::class, 'index']);
Route::get('api/konsers/{konser}', [KonserController::class, 'show']);
Route::post('api/konsers', [KonserController::class, 'store']);
Route::put('api/konsers/{konser}', [KonserController::class, 'update']);
Route::delete('api/konsers/{konser}', [KonserController::class, 'destroy']);
Route::get('api/konsers/type/{type}', [KonserController::class, 'getByType']);
Route::get('api/konsers-published', [KonserController::class, 'getPublished']);

// Artist Routes
Route::resource('artists', ArtistController::class);
Route::get('api/artists/create', [ArtistController::class, 'create']);
Route::get('api/artists/{artist}/edit', [ArtistController::class, 'edit']);
Route::get('api/artists', [ArtistController::class, 'index']);
Route::post('api/artists', [ArtistController::class, 'store']);
Route::put('api/artists/{artist}', [ArtistController::class, 'update']);
Route::delete('api/artists/{artist}', [ArtistController::class, 'destroy']);

// Ticket Routes
Route::resource('tickets', TicketController::class);
Route::get('api/tickets/create', [TicketController::class, 'create']);
Route::get('api/tickets/{ticket}/edit', [TicketController::class, 'edit']);
Route::get('api/tickets', [TicketController::class, 'index']);
Route::post('api/tickets', [TicketController::class, 'store']);
Route::put('api/tickets/{ticket}', [TicketController::class, 'update']);
Route::delete('api/tickets/{ticket}', [TicketController::class, 'destroy']);

Route::get('api/tickets/konser/{konserId}', [TicketController::class, 'getHarga']);
Route::get('api/konsers/{konserId}/tickets', [TicketController::class, 'getByKonser']);

// User Routes
Route::resource('users', UserController::class);
Route::get('api/users', [UserController::class, 'index']);
Route::get('api/users/{user}/edit', [UserController::class, 'edit']);
Route::delete('api/users/{user}', [UserController::class, 'destroy']);

// Admin routes (for navigation)
Route::get('admin/konsers', function() {
    return view('welcome');
});
Route::get('admin/artists', function() {
    return view('welcome');
});
Route::get('admin/tickets', function() {
    return view('welcome');
});
Route::get('admin/users', function() {
    return view('welcome');
});

Route::get('/beli-tiket', [TransaksiController::class, 'index'])->name('beli-tiket');
Route::post('/beli-tiket', [TransaksiController::class, 'store'])->name('beli-tiket.store');

Route::get('/checkout', [TransaksiController::class, 'checkout']);
Route::post('/simpanTransaksi', [TransaksiController::class, 'simpanTransaksi'])->name('simpanTransaksi');

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout']);
