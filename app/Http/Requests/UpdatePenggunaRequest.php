<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $penggunaId = $this->route('pengguna')->id;

        return [
            'no_pengguna' => [
                'required', 'string', 'max:20',
                Rule::unique('users', 'no_pengguna')->ignore($penggunaId),
            ],
            'name'     => 'required|string|max:100',
            'email'    => [
                'required', 'email', 'max:150',
                Rule::unique('users', 'email')->ignore($penggunaId),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'no_hp'    => 'nullable|string|max:20',
            'alamat'   => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'no_pengguna.unique' => 'No pengguna sudah digunakan.',
            'email.unique'       => 'Email sudah digunakan.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
