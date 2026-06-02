<?php

namespace App\Http\Requests;

use App\Models\Peralatan;
use Illuminate\Foundation\Http\FormRequest;

class StorePeminjamanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Jika request datang dari admin, pengguna_id wajib dipilih manual.
        // Jika dari user biasa, pengguna_id diambil dari Auth di controller
        // sehingga tidak perlu ada di form (nullable).
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';

        return [
            'kode_peminjaman'         => $isAdmin
                                            ? 'required|string|max:50|unique:peminjamans,kode_peminjaman'
                                            : 'nullable|string|max:50',
            'pengguna_id'             => $isAdmin ? 'required|exists:users,id' : 'nullable',
            'peralatan_id'            => 'required|exists:peralatans,id',
            'jumlah'                  => 'required|integer|min:1',
            'tanggal_pinjam'          => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'keterangan'              => 'nullable|string|max:500',
        ];
    }

    /**
     * Validasi stok SETELAH rules() lolos.
     * Ini adalah lapisan kedua — Service juga mengecek ulang di dalam transaction.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $peralatanId = $this->input('peralatan_id');
            $jumlah      = (int) $this->input('jumlah', 1);

            if (!$peralatanId) {
                return;
            }

            $peralatan = Peralatan::find($peralatanId);

            if (!$peralatan) {
                return;
            }

            if ($peralatan->stok <= 0) {
                $validator->errors()->add(
                    'peralatan_id',
                    "Peralatan \"{$peralatan->nama_peralatan}\" stoknya sudah habis."
                );
            } elseif (!$peralatan->hasStock($jumlah)) {
                $validator->errors()->add(
                    'jumlah',
                    "Stok tidak mencukupi. Stok tersedia: {$peralatan->stok} unit."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'kode_peminjaman.required'           => 'Kode peminjaman wajib diisi.',
            'kode_peminjaman.unique'              => 'Kode peminjaman sudah digunakan.',
            'pengguna_id.required'                => 'Peminjam wajib dipilih.',
            'peralatan_id.required'               => 'Peralatan wajib dipilih.',
            'jumlah.required'                     => 'Jumlah wajib diisi.',
            'jumlah.min'                          => 'Jumlah minimal 1.',
            'tanggal_pinjam.required'             => 'Tanggal pinjam wajib diisi.',
            'tanggal_rencana_kembali.after_or_equal' => 'Rencana kembali tidak boleh sebelum tanggal pinjam.',
        ];
    }
}
