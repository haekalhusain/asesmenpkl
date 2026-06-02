@extends('layouts.app')
@section('title', 'Edit Peminjaman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-neutral">Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('peminjaman.update', $peminjaman) }}">
        @csrf @method('PUT')

        <div class="form-group">
            <label class="form-label">Kode Peminjaman</label>
            <input type="text" name="kode_peminjaman" class="form-control"
                   value="{{ $peminjaman->kode_peminjaman }}" readonly
                   style="opacity:0.5; cursor:not-allowed;">
        </div>

        <div class="form-group">
            <label class="form-label">Peminjam</label>
            <select name="pengguna_id"
                    class="form-control {{ $errors->has('pengguna_id') ? 'is-invalid' : '' }}">
                @foreach($penggunas as $pengguna)
                    <option value="{{ $pengguna->id }}"
                            {{ old('pengguna_id', $peminjaman->pengguna_id) == $pengguna->id ? 'selected' : '' }}>
                        {{ $pengguna->name }} ({{ $pengguna->no_pengguna }})
                    </option>
                @endforeach
            </select>
            @error('pengguna_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Peralatan</label>
            <select name="peralatan_id"
                    class="form-control {{ $errors->has('peralatan_id') ? 'is-invalid' : '' }}">
                @foreach($peralatans as $peralatan)
                    <option value="{{ $peralatan->id }}"
                            {{ old('peralatan_id', $peminjaman->peralatan_id) == $peralatan->id ? 'selected' : '' }}>
                        {{ $peralatan->nama_peralatan }}
                    </option>
                @endforeach
            </select>
            @error('peralatan_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control"
                   value="{{ old('jumlah', $peminjaman->jumlah) }}" min="1">
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control"
                   value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Rencana Tanggal Kembali</label>
            <input type="date" name="tanggal_rencana_kembali" class="form-control"
                   value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali) }}">
        </div>

        <div class="form-group">
            <label class="form-label">
                Tanggal Kembali Aktual
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">
                    (isi jika sudah dikembalikan)
                </span>
            </label>
            <input type="date" name="tanggal_kembali" class="form-control"
                   value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}">
        </div>

        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="dipinjam"     {{ old('status', $peminjaman->status) == 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ old('status', $peminjaman->status) == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat"    {{ old('status', $peminjaman->status) == 'terlambat'    ? 'selected' : '' }}>Terlambat</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control"
                      rows="3">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>
@endsection
