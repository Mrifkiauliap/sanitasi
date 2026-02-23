<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilayah extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama',
        'kecamatan',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    //  Relasi

    public function sanitasi()
    {
        return $this->hasMany(Sanitasi::class);
    }

    public function penyaluranAir()
    {
        return $this->hasMany(PenyaluranAir::class);
    }

    public function laporanKondisi()
    {
        return $this->hasMany(LaporanKondisi::class);
    }

    //  Helper

    public function isTerdampak(): bool
    {
        return $this->status === 'terdampak';
    }

    public function isAman(): bool
    {
        return $this->status === 'tidak terdampak';
    }
}
