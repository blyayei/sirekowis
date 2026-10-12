<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitases';

    protected $fillable = [
        'nama_fasilitas',
        'icon',
    ];

    public function wisatas(): BelongsToMany
    {
        return $this->belongsToMany(Wisata::class, 'wisata_fasilitas', 'fasilitas_id', 'wisata_id')->withTimestamps();
    }
}
