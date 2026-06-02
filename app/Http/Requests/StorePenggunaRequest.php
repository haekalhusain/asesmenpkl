<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenggunaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'no_pengguna' => 'required|string|max:20|unique:users,no_pengguna',
            'name'        => 'required|string|max:100',
            'email'       => 'required|email|max:150|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'no_pengguna.required'  => 'No pengguna wajib diisi.',
            'no_pengguna.unique'    => 'No pengguna sudah terdaftar.',
            'name.required'         => 'Nama wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ];
    }
}
