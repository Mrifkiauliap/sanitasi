<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanitasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wilayah_id',
        'jenis',
        'status',
        'lokasi',
        'keterangan',
    ];

    //  Relasi

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    //  Helper

    public function isBaik(): bool
    {
        return $this->status === 'baik';
    }

    public function isRusak(): bool
    {
        return $this->status === 'rusak';
    }

    public function isTidakAda(): bool
    {
        return $this->status === 'tidak ada';
    }
}
