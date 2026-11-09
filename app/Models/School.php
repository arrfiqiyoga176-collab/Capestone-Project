<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_sekolah',
        'alamat',
        'latitude',
        'longitude',
        'id_jenjang',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    protected $appends = ['name', 'address', 'jenjang_label'];

    public function getNameAttribute(): string
    {
        return $this->nama_sekolah;
    }

    public function getAddressAttribute(): string
    {
        return (string)($this->alamat ?? '');
    }

    /**
     * Get label for educational level.
     */
    public function getJenjangLabelAttribute(): string
    {
        return match ($this->id_jenjang) {
            'JJ001' => 'SD / MI',
            'JJ002' => 'SMP / MTs',
            'JJ003' => 'SMA / SMK / MA',
            default => 'Sekolah',
        };
    }

    /**
     * Get badge color classes for educational level.
     */
    public function getJenjangBadgeClassAttribute(): string
    {
        return match ($this->id_jenjang) {
            'JJ001' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'JJ002' => 'bg-sky-50 text-sky-700 border-sky-200',
            'JJ003' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            default => 'bg-slate-50 text-slate-700 border-slate-200',
        };
    }
}
