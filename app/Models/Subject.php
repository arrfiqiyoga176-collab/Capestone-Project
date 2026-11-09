<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'id_jenjang',
        'ikon_svg',
        'warna_teks',
        'warna_bg_soft',
        'deskripsi',
    ];

    protected $appends = ['jenjang_label'];

    /**
     * Get label for educational level.
     */
    public function getJenjangLabelAttribute(): string
    {
        return match ($this->id_jenjang) {
            'JJ001' => 'SD / MI',
            'JJ002' => 'SMP / MTs',
            'JJ003' => 'SMA / SMK / MA',
            default => 'Umum',
        };
    }

    /**
     * Get the locations associated with the subject.
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class, 'location_subject')
            ->withPivot(['skor_relevansi_c1', 'topik_kajian'])
            ->withTimestamps();
    }
}
