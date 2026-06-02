<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pengguna_id'             => 'required|exists:users,id',
            'peralatan_id'            => 'required|exists:peralatans,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'tanggal_kembali'         => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status'                  => 'required|in:dipinjam,dikembalikan,terlambat',
            'keterangan'              => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'pengguna_id.required'    => 'Peminjam wajib dipilih.',
            'peralatan_id.required'   => 'Peralatan wajib dipilih.',
            'jumlah.min'              => 'Jumlah minimal 1.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'status.in'               => 'Status tidak valid.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ];
    }
}
