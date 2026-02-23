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
            'jenis'      => 'required|string|max:255',
            'status'     => 'required|in:baik,rusak,tidak ada',
            'lokasi'     => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ];
    }
}
