<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KonserController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root Route - Redirect to Dashboard
Route::get('/', function () {
    return redirect('/dashboard');
});

// Auth Routes (Login, Register, Logout)
Route::get('/login', function () {
    return redirect('/dashboard');
});
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Halaman Dashboard Utama Customer
Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

// ====== PUBLIC API ROUTES (READ-ONLY) ======
Route::prefix('api')->group(function () {

    Route::post('register', [UserController::class, 'register'])->name('api.register');

    // Konsers
    Route::get('konsers', [KonserController::class, 'index'])->name('api.konsers.index');
    Route::get('konsers/{konser}', [KonserController::class, 'show'])->name('api.konsers.show');
    Route::get('konsers/type/{type}', [KonserController::class, 'getByType'])->name('api.konsers.byType');
    Route::get('konsers-published', [KonserController::class, 'getPublished'])->name('api.konsers.published');

    // Artists
    Route::get('artists', [ArtistController::class, 'index'])->name('api.artists.index');

    // Tickets
    Route::get('tickets', [TicketController::class, 'index'])->name('api.tickets.index');
    Route::get('tickets/konser/{konserId}', [TicketController::class, 'getHarga'])->name('api.tickets.byKonser');
    Route::get('konsers/{konserId}/tickets', [TicketController::class, 'getByKonser'])->name('api.konsers.tickets');
});

// ====== PROTEKSI ROUTE YANG MEMBUTUHKAN LOGIN ======
Route::middleware(['auth'])->group(function () {

    // 2. Transaksi Tiket (Proses Checkout & Beli)
    Route::get('/beli-tiket', [TransaksiController::class, 'index'])->name('beli-tiket');
    Route::post('/beli-tiket', [TransaksiController::class, 'store'])->name('beli-tiket.store');
    Route::get('/checkout', [TransaksiController::class, 'checkout'])->name('checkout');
    Route::post('/simpanTransaksi', [TransaksiController::class, 'simpanTransaksi'])->name('simpanTransaksi');

    Route::post('/simpanProfile', [UserController::class, 'simpanProfile'])->name('simpanProfile');

    // API: Transactions (untuk user melihat transaksi mereka)
    Route::prefix('api')->group(function () {
        Route::get('transactions', [TransaksiController::class, 'index'])->name('api.transactions.index');
        Route::post('transactions', [TransaksiController::class, 'store'])->name('api.transactions.store');
    });

});

// ====== ROUTE ADMIN (MEMBUTUHKAN LOGIN & ROLE ADMIN) ======
Route::middleware(['auth', 'check.admin'])->group(function () {

    // 1. Route Utama Dashboard Admin
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // --- Konser Form Routes ---
    Route::get('/create', [KonserController::class, 'create'])->name('create');
    Route::get('admin/konsers/{konser}/edit', [KonserController::class, 'edit'])->name('admin.konsers.edit');
    Route::post('/simpanKonser', [KonserController::class, 'store'])->name('store');
    Route::put('admin/konsers/{konser}', [KonserController::class, 'update'])->name('admin.konsers.update');
    Route::delete('admin/konsers/{konser}', [KonserController::class, 'destroy'])->name('admin.konsers.destroy');

    // --- Artist Form Routes ---
    Route::get('admin/artists/create', [ArtistController::class, 'create'])->name('admin.artists.create');
    Route::get('admin/artists/{artist}/edit', [ArtistController::class, 'edit'])->name('admin.artists.edit');
    Route::post('/simpanArtis', [ArtistController::class, 'store'])->name('admin.artists.store');
    Route::put('admin/artists/{artist}', [ArtistController::class, 'update'])->name('admin.artists.update');
    Route::delete('admin/artists/{artist}', [ArtistController::class, 'destroy'])->name('admin.artists.destroy');

    // --- Ticket Form Routes ---
    Route::get('admin/tickets/create', [TicketController::class, 'create'])->name('admin.tickets.create');
    Route::get('admin/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('admin.tickets.edit');
    Route::put('admin/tickets/{ticket}', [TicketController::class, 'update'])->name('admin.tickets.update');
    Route::post('/simpanTiket', [TicketController::class, 'store'])->name('admin.tickets.store');
    Route::delete('admin/tickets/{ticket}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');

    // --- User Form Routes ---
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/simpanUser', [UserController::class, 'store'])->name('admin.users.store');

    // --- Media ---
    Route::get('admin/media/create', [MediaController::class, 'create'])->name('admin.media.create');
    Route::post('/simpanMedia', [MediaController::class, 'store'])->name('admin.media.store');
    Route::get('admin/media/{media}/edit', [MediaController::class, 'editMedia'])->name('admin.media.edit');
    Route::post('/simpanEditMedia/{media}', [MediaController::class, 'simpanEditMedia'])->name('admin.media.simpanEdit');
    Route::delete('admin/media/{media}/delete', [MediaController::class, 'destroy'])->name('admin.media.destroy');

    Route::get('/detailTransaksi/{id}', [TransaksiController::class, 'detailTransaksi'])->name('detailTransaksi');


    // ====== ADMIN API ROUTES (REST API CRUD) ======
    Route::prefix('api')->group(function () {
        // Konsers REST API
        Route::post('konsers', [KonserController::class, 'storeApi'])->name('api.konsers.store');
        Route::put('konsers/{konser}', [KonserController::class, 'updateApi'])->name('api.konsers.update');
        Route::delete('konsers/{konser}', [KonserController::class, 'destroyApi'])->name('api.konsers.destroy');

        // Artists REST API
        Route::post('artists', [ArtistController::class, 'storeApi'])->name('api.artists.store');
        Route::put('artists/{artist}', [ArtistController::class, 'updateApi'])->name('api.artists.update');
        Route::delete('artists/{artist}', [ArtistController::class, 'destroyApi'])->name('api.artists.destroy');

        // Tickets REST API
        Route::post('tickets', [TicketController::class, 'storeApi'])->name('api.tickets.store');
        Route::put('tickets/{ticket}', [TicketController::class, 'updateApi'])->name('api.tickets.update');

        // Media REST API
        Route::get('media', [MediaController::class, 'indexApi'])->name('api.media.index');
        Route::post('media', [MediaController::class, 'storeApi'])->name('api.media.store');
        Route::put('media/{media}', [MediaController::class, 'updateApi'])->name('api.media.update');
        Route::delete('media/{media}', [MediaController::class, 'destroyApi'])->name('api.media.destroy');

        // Users REST API
        Route::get('users', [UserController::class, 'indexApi'])->name('api.users.index');
        Route::delete('users/{user}', [UserController::class, 'destroyApi'])->name('api.users.destroy');

        // Transactions REST API (Admin)
        Route::get('transactions/{transaksi}', [TransaksiController::class, 'showApi'])->name('api.transactions.show');
        Route::put('transactions/{transaksi}', [TransaksiController::class, 'updateApi'])->name('api.transactions.update');

        // Dashboard Stats API
        Route::get('dashboard/stats', [AdminController::class, 'stats'])->name('api.admin.stats');
    });

});
