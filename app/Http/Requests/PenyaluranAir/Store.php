<?php

namespace App\Http\Requests\PenyaluranAir;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'wilayah_id'         => 'nullable|exists:wilayahs,id',
            'sumber_air'         => 'required|string|max:255',
            'volume_liter'       => 'nullable|integer|min:1',
            'tanggal_distribusi' => 'required|date',
            'status'             => 'required|in:terdistribusi,belum terdistribusi',
            'keterangan'         => 'nullable|string',
        ];
    }
}
