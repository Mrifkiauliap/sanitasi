<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sanitasi extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wilayah_id',
        'nama',
        'jumlah',
        'lokasi',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
    ];

    //  Relasi

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }
}
