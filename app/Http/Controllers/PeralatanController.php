<?php

namespace App\Http\Controllers;

use App\Models\Peralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PeralatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peralatan::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) =>
                $q2->where('nama_peralatan', 'like', "%$q%")
                   ->orWhere('kode_peralatan', 'like', "%$q%")
                   ->orWhere('kategori', 'like', "%$q%")
            );
        }

        $peralatans = $query->latest()->paginate(10);
        return view('peralatan.index', compact('peralatans'));
    }

    public function create()
    {
        return view('peralatan.create');
    }

    public function store(Request $request)
    {
        // ── Validasi (dipindah dari StorePeralatanRequest) ─────────────────
        $data = $request->validate([
            'kode_peralatan' => 'required|string|max:30|unique:peralatans,kode_peralatan',
            'nama_peralatan' => 'required|string|max:150',
            'kategori'       => 'nullable|string|max:100',
            'stok'           => 'required|integer|min:0',
            'kondisi'        => 'required|in:baik,rusak_ringan,rusak_berat',
            'deskripsi'      => 'nullable|string|max:1000',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_peralatan.required' => 'Kode peralatan wajib diisi.',
            'kode_peralatan.unique'   => 'Kode peralatan sudah digunakan.',
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'stok.min'                => 'Stok tidak boleh negatif.',
            'kondisi.in'              => 'Kondisi tidak valid.',
            'foto.image'              => 'File harus berupa gambar.',
            'foto.max'                => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('peralatan', 'public');
        }

        Peralatan::create($data);

        return redirect()->route('peralatan.index')
                         ->with('success', 'Peralatan berhasil ditambahkan.');
    }

    public function show(Peralatan $peralatan)
    {
        $peralatan->load('peminjamans');
        return view('peralatan.show', compact('peralatan'));
    }

    public function edit(Peralatan $peralatan)
    {
        return view('peralatan.edit', compact('peralatan'));
    }

    public function update(Request $request, Peralatan $peralatan)
    {
        // ── Validasi (dipindah dari UpdatePeralatanRequest) ────────────────
        $data = $request->validate([
            'kode_peralatan' => [
                'required', 'string', 'max:30',
                Rule::unique('peralatans', 'kode_peralatan')->ignore($peralatan->id),
            ],
            'nama_peralatan' => 'required|string|max:150',
            'kategori'       => 'nullable|string|max:100',
            'stok'           => 'required|integer|min:0',
            'kondisi'        => 'required|in:baik,rusak_ringan,rusak_berat',
            'deskripsi'      => 'nullable|string|max:1000',
            'foto'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'kode_peralatan.unique'   => 'Kode peralatan sudah digunakan.',
            'nama_peralatan.required' => 'Nama peralatan wajib diisi.',
            'stok.min'                => 'Stok tidak boleh negatif.',
            'kondisi.in'              => 'Kondisi tidak valid.',
            'foto.image'              => 'File harus berupa gambar.',
            'foto.max'                => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($peralatan->foto) {
                Storage::disk('public')->delete($peralatan->foto);
            }
            $data['foto'] = $request->file('foto')->store('peralatan', 'public');
        } else {
            unset($data['foto']);
        }

        $peralatan->update($data);

        return redirect()->route('peralatan.index')
                         ->with('success', 'Data peralatan berhasil diperbarui.');
    }

    public function destroy(Peralatan $peralatan)
    {
        if ($peralatan->foto) {
            Storage::disk('public')->delete($peralatan->foto);
        }

        $peralatan->delete();

        return redirect()->route('peralatan.index')
                         ->with('success', 'Peralatan berhasil dihapus.');
    }
}
