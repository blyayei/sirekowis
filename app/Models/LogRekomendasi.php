<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogRekomendasi extends Model
{
    use HasFactory;

    protected $table = 'log_rekomendasis';

    protected $fillable = [
        'wisata_asal_id',
        'wisata_rekomendasi_id',
        'similarity_score',
        'keyword_pencarian',
        'ip_address',
    ];

    protected $casts = [
        'similarity_score' => 'float',
    ];

    public function wisataAsal(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'wisata_asal_id');
    }

    public function wisataRekomendasi(): BelongsTo
    {
        return $this->belongsTo(Wisata::class, 'wisata_rekomendasi_id');
    }
}
