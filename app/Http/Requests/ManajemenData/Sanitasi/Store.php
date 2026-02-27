<?php

namespace App\Http\Requests\ManajemenData\Sanitasi;

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
            'wilayah_id' => 'nullable|exists:wilayahs,id',
            'nama'       => 'required|string|max:255',
            'jumlah'     => 'nullable|integer|min:0',
            'lokasi'     => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ];
    }
}
