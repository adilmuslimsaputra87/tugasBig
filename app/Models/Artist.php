<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $table = 'artists';

    protected $fillable = [
        'name',
        'genre',
        'country',
        'image',
        'bio',
        'instagram',
    ];
    public function konsers(): HasMany
    {
        // Sesuaikan 'artist_id' dengan nama kolom foreign key yang ada di tabel konsers kamu
        return $this->hasMany(Konser::class, 'artist_id');
    }
}
