<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKondisi extends Model
{
    protected $table = 'laporan_kondisi';

    protected $fillable = [
        'wilayah_id',
        'petugas_id',
        'tanggal_inspeksi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_inspeksi' => 'date',
    ];

    //  Relasi

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
