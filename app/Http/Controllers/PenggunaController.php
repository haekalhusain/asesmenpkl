<?php

namespace App\Http\Controllers;

use App\Models\Peminjam;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjam::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) =>
                $q2->where('nama_peminjam', 'like', "%$q%")
                   ->orWhere('kelas', 'like', "%$q%")
                   ->orWhere('jurusan', 'like', "%$q%")
                   ->orWhere('no_hp', 'like', "%$q%")
            );
        }

        $penggunas = $query->latest()->paginate(10);
        return view('pengguna.index', compact('penggunas'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_peminjam' => 'required|string|max:150',
            'kelas'         => 'required|string|max:50',
            'jurusan'       => 'nullable|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
        ]);

        Peminjam::create($data);

        return redirect()->route('pengguna.index')
                         ->with('success', 'Peminjam berhasil ditambahkan.');
    }

    public function show(Peminjam $pengguna)
    {
        $pengguna->load('peminjaman.barang');
        return view('pengguna.show', compact('pengguna'));
    }

    public function edit(Peminjam $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, Peminjam $pengguna)
    {
        $data = $request->validate([
            'nama_peminjam' => 'required|string|max:150',
            'kelas'         => 'required|string|max:50',
            'jurusan'       => 'nullable|string|max:100',
            'no_hp'         => 'nullable|string|max:20',
        ], [
            'nama_peminjam.required' => 'Nama peminjam wajib diisi.',
            'kelas.required'         => 'Kelas wajib diisi.',
        ]);

        $pengguna->update($data);

        return redirect()->route('pengguna.index')
                         ->with('success', 'Data peminjam berhasil diperbarui.');
    }

    public function destroy(Peminjam $pengguna)
    {
        $pengguna->delete();

        return redirect()->route('pengguna.index')
                         ->with('success', 'Peminjam berhasil dihapus.');
    }
}
