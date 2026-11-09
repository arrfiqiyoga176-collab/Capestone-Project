<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tempat',
        'nama_lokasi',
        'slug',
        'alamat_kawasan',
        'latitude',
        'longitude',
        'biaya',
        'deskripsi',
        'jam_operasional',
        'foto',
        'status',
        'kondisi_cuaca',
        'kecepatan_angin_knot',
        'arah_angin',
        'tinggi_gelombang_meter',
        'tingkat_risiko',
        'skor_fasilitas_c3',
        'skor_keamanan_c4',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'tinggi_gelombang_meter' => 'float',
        'kecepatan_angin_knot' => 'integer',
        'skor_fasilitas_c3' => 'integer',
        'skor_keamanan_c4' => 'integer',
    ];

    /**
     * Helper to get approximate numeric fee for calculations or sorting.
     */
    public function getBiayaNumericAttribute(): float
    {
        if (preg_match('/(?:Rp|~Rp)?\s*([0-9]+(?:\.[0-9]+)*)/i', $this->biaya ?? '', $matches)) {
            return (float) str_replace('.', '', $matches[1]);
        }
        return 0.0;
    }

    /**
     * Get the subjects associated with the location.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'location_subject')
            ->withPivot(['skor_relevansi_c1', 'topik_kajian'])
            ->withTimestamps();
    }
}
