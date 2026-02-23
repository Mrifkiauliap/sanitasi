<?php

namespace App\Http\Requests\LaporanKondisi;

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
            'wilayah_id'       => 'required|exists:wilayahs,id',
            'tanggal_inspeksi' => 'required|date',
            'catatan'          => 'nullable|string',
        ];
    }
}
