<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     * Sesuaikan dengan field yang ada di migration kamu.
     */
    protected $fillable = [
        'users_id',
        'tickets_id',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'nik',
        'quantity',
        'total_price',
        'promo_code',
        'payment_method',
        'payment_status',
        'payment_date',
    ];

    /**
     * CASTING DATA
     * Mengubah tipe data field secara otomatis saat dipanggil
     */
    protected $casts = [
        'payment_date' => 'datetime',
        'quantity' => 'integer',
        'total_price' => 'integer',
    ];

    /**
     * RELASI: Transaksi ini milik (BelongsTo) seorang User
     */
    public function user(): BelongsTo
    {
        // Karena nama kolommu 'users_id', kita harus sebutkan sebagai argumen kedua
        return $this->belongsTo(User::class, 'users_id');
    }

    /**
     * RELASI: Transaksi ini milik (BelongsTo) sebuah Tiket
     */
    public function ticket(): BelongsTo
    {
        // Karena nama kolommu 'tickets_id', kita sebutkan sebagai argumen kedua
        return $this->belongsTo(Ticket::class, 'tickets_id');
    }
}
