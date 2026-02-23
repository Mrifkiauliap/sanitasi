<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyaluranAir extends Model
{
    use SoftDeletes;

    protected $table = 'penyaluran_air';

    protected $fillable = [
        'wilayah_id',
        'sumber_air',
        'volume_liter',
        'tanggal_distribusi',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
        'volume_liter'       => 'integer',
    ];

    //  Relasi

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    //  Helper

    public function isTerdistribusi(): bool
    {
        return $this->status === 'terdistribusi';
    }

    public function isBelumTerdistribusi(): bool
    {
        return $this->status === 'belum terdistribusi';
    }
}
