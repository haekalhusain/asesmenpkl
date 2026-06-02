<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeralatanRequest;
use App\Http\Requests\UpdatePeralatanRequest;
use App\Models\Peralatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(StorePeralatanRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                                    ->store('peralatan', 'public');
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

    public function update(UpdatePeralatanRequest $request, Peralatan $peralatan)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($peralatan->foto) {
                Storage::disk('public')->delete($peralatan->foto);
            }
            $data['foto'] = $request->file('foto')
                                    ->store('peralatan', 'public');
        } else {
            // Jangan hapus foto lama jika tidak ada upload baru
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
