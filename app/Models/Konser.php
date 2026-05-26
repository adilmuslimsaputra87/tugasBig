<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konser extends Model
{
    use HasFactory;

    protected $table = 'konsers';

    protected $fillable = [
        'title',
        'artist',
        'genre',
        'date',
        'time',
        'venue',
        'city',
        'description',
        'image',
        'price',
        'capacity',
        'status',
        'type'
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:0',
    ];
}
