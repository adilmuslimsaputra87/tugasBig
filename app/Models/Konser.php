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
        'artists_id',
        'genre',
        'date',
        'time',
        'venue',
        'city',
        'description',
        'image',
        'trailer',
        'price',
        'capacity',
        'status',
        'type'
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:0',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artists_id'); // sesuaikan foreign key-nya
    }
}
