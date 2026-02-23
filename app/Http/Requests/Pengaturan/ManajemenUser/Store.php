<?php

namespace App\Http\Requests\Pengaturan\ManajemenUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => 'required|string|min:8|confirmed',
            'image'    => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,webp'],
            'status'   => 'required|in:active,inactive',
        ];
    }
}
