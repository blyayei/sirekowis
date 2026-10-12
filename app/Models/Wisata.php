<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Wisata extends Model
{
    use HasFactory;

    protected $table = 'wisatas';

    protected $fillable = [
        'kategori_id',
        'nama_wisata',
        'slug',
        'kecamatan',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'harga_tiket',
        'rating_google',
        'jumlah_review_google',
        'jam_operasional',
        'foto_utama',
        'status',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'harga_tiket' => 'integer',
        'rating_google' => 'float',
        'jumlah_review_google' => 'integer',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($wisata) {
            if (empty($wisata->slug)) {
                $wisata->slug = Str::slug($wisata->nama_wisata);
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function fasilitases(): BelongsToMany
    {
        return $this->belongsToMany(Fasilitas::class, 'wisata_fasilitas', 'wisata_id', 'fasilitas_id')->withTimestamps();
    }

    public function logsAsal(): HasMany
    {
        return $this->hasMany(LogRekomendasi::class, 'wisata_asal_id');
    }

    public function logsRekomendasi(): HasMany
    {
        return $this->hasMany(LogRekomendasi::class, 'wisata_rekomendasi_id');
    }

    public function getFormattedHargaAttribute(): string
    {
        return $this->harga_tiket == 0 ? 'Gratis' : 'Rp ' . number_format($this->harga_tiket, 0, ',', '.');
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto_utama && file_exists(public_path('storage/' . $this->foto_utama))) {
            return asset('storage/' . $this->foto_utama);
        }
        if ($this->foto_utama && (str_starts_with($this->foto_utama, 'http://') || str_starts_with($this->foto_utama, 'https://'))) {
            return $this->foto_utama;
        }
        if ($this->foto_utama && file_exists(public_path($this->foto_utama))) {
            return asset($this->foto_utama);
        }
        return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80';
    }
}
