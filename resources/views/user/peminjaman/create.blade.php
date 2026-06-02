@extends('layouts.user')
@section('title', 'Tambah Peminjaman')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Peminjaman</h1>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('user.peminjaman.store') }}">
        @csrf

        <div class="form-group">
            <label class="form-label">Peralatan</label>
            <select name="peralatan_id"
                    class="form-control {{ $errors->has('peralatan_id') ? 'is-invalid' : '' }}">
                <option value="">-- Pilih Peralatan --</option>
                @foreach($peralatans as $peralatan)
                    <option value="{{ $peralatan->id }}"
                        {{ old('peralatan_id') == $peralatan->id ? 'selected' : '' }}>
                        {{ $peralatan->nama_peralatan }}
                        ({{ $peralatan->kode_peralatan }})
                        — Stok: {{ $peralatan->stok }}
                    </option>
                @endforeach
            </select>
            @error('peralatan_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah"
                   class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                   value="{{ old('jumlah', 1) }}" min="1">
            @error('jumlah')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam"
                   class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
            @error('tanggal_pinjam')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Rencana Tanggal Kembali</label>
            <input type="date" name="tanggal_rencana_kembali"
                   class="form-control {{ $errors->has('tanggal_rencana_kembali') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_rencana_kembali') }}">
            @error('tanggal_rencana_kembali')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Keterangan <span style="color:var(--text-muted);font-weight:400;">(opsional)</span></label>
            <textarea name="keterangan" class="form-control" rows="3"
                      placeholder="Keperluan peminjaman, dll.">{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>
@endsection
