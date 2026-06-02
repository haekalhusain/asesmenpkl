<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeralatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->route('peralatan') = instance Peralatan dari route model binding
        $peralatanId = $this->route('peralatan')->id;

        return [
            'kode_peralatan' => [
                'required', 'string', 'max:30',
                Rule::unique('peralatans', 'kode_peralatan')->ignore($peralatanId),
            ],
            'nama_peralatan' => 'required|string|max:150',
            'kategori'       => 'nullable|string|max:100',
            'stok'           => 'required|integer|min:0',
            'kondisi'        => 'required|in:baik,rusak_ringan,rusak_berat',
            'deskripsi'      => 'nullable|string|max:1000',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_peralatan.unique'   => 'Kode peralatan sudah digunakan.',
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'stok.min'                => 'Stok tidak boleh negatif.',
            'kondisi.in'              => 'Kondisi tidak valid.',
            'foto.image'              => 'File harus berupa gambar.',
            'foto.max'                => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
