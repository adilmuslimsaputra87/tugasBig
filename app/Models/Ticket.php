<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'konser_id',
        'name',
        'price',
        'stock',
        'sold',
        'description',
        'promo_price',
        'promo_valid_until',
        'max_purchase'
    ];

    protected $casts = [
        'price' => 'decimal:0',
        'promo_price' => 'decimal:0',
        'promo_valid_until' => 'date'
    ];

    public function konser()
    {
        return $this->belongsTo(Konser::class);
    }
}
