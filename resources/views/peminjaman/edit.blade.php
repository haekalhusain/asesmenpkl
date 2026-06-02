@extends('layouts.app')
@section('title', 'Edit Peminjaman')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Peminjaman</h1>
    <a href="{{ route('peminjaman.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('peminjaman.update', $peminjaman) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- Kode Peminjaman (readonly) --}}
        <div class="form-group">
            <label class="form-label" for="kode_peminjaman">Kode Peminjaman</label>
            <input type="text"
                   id="kode_peminjaman"
                   class="form-control"
                   value="{{ $peminjaman->kode_peminjaman }}"
                   readonly
                   style="opacity:0.5; cursor:not-allowed;">
        </div>

        {{-- Peminjam --}}
        <div class="form-group">
            <label class="form-label" for="pengguna_id">Peminjam</label>
            <select id="pengguna_id"
                    name="pengguna_id"
                    class="form-control {{ $errors->has('pengguna_id') ? 'is-invalid' : '' }}">
                @foreach($penggunas as $pengguna)
                    <option value="{{ $pengguna->id }}"
                            {{ old('pengguna_id', $peminjaman->pengguna_id) == $pengguna->id ? 'selected' : '' }}>
                        {{ $pengguna->name }} ({{ $pengguna->no_pengguna }})
                    </option>
                @endforeach
            </select>
            @error('pengguna_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Peralatan --}}
        <div class="form-group">
            <label class="form-label" for="peralatan_id">Peralatan</label>
            <select id="peralatan_id"
                    name="peralatan_id"
                    class="form-control {{ $errors->has('peralatan_id') ? 'is-invalid' : '' }}">
                @foreach($peralatans as $peralatan)
                    <option value="{{ $peralatan->id }}"
                            {{ old('peralatan_id', $peminjaman->peralatan_id) == $peralatan->id ? 'selected' : '' }}>
                        {{ $peralatan->nama_peralatan }}
                    </option>
                @endforeach
            </select>
            @error('peralatan_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Jumlah --}}
        <div class="form-group">
            <label class="form-label" for="jumlah">Jumlah</label>
            <input type="number"
                   id="jumlah"
                   name="jumlah"
                   class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                   value="{{ old('jumlah', $peminjaman->jumlah) }}"
                   min="1">
            @error('jumlah')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tanggal Pinjam --}}
        <div class="form-group">
            <label class="form-label" for="tanggal_pinjam">Tanggal Pinjam</label>
            <input type="date"
                   id="tanggal_pinjam"
                   name="tanggal_pinjam"
                   class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam) }}">
            @error('tanggal_pinjam')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Rencana Tanggal Kembali --}}
        <div class="form-group">
            <label class="form-label" for="tanggal_rencana_kembali">Rencana Tanggal Kembali</label>
            <input type="date"
                   id="tanggal_rencana_kembali"
                   name="tanggal_rencana_kembali"
                   class="form-control {{ $errors->has('tanggal_rencana_kembali') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali) }}">
            @error('tanggal_rencana_kembali')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Tanggal Kembali Aktual --}}
        <div class="form-group">
            <label class="form-label" for="tanggal_kembali">
                Tanggal Kembali Aktual
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">
                    (isi jika sudah dikembalikan)
                </span>
            </label>
            <input type="date"
                   id="tanggal_kembali"
                   name="tanggal_kembali"
                   class="form-control {{ $errors->has('tanggal_kembali') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali) }}">
            @error('tanggal_kembali')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Status --}}
        <div class="form-group">
            <label class="form-label" for="status">Status</label>
            <select id="status"
                    name="status"
                    class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}">
                <option value="dipinjam"     {{ old('status', $peminjaman->status->value ?? $peminjaman->status) === 'dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ old('status', $peminjaman->status->value ?? $peminjaman->status) === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="terlambat"    {{ old('status', $peminjaman->status->value ?? $peminjaman->status) === 'terlambat'    ? 'selected' : '' }}>Terlambat</option>
            </select>
            @error('status')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Keterangan --}}
        <div class="form-group">
            <label class="form-label" for="keterangan">Keterangan</label>
            <textarea id="keterangan"
                      name="keterangan"
                      class="form-control"
                      rows="3">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection
