<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'users_id',
        'tickets_id',
        'quantity',
        'total_price',
        'payment_status',
    ];
}
