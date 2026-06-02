@extends('layouts.user')
@section('title', 'Tambah Peminjaman')

@section('content')

<div class="page-header">
    <h1 class="page-title">Tambah Peminjaman</h1>
    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('user.peminjaman.store') }}" novalidate>
        @csrf

        {{-- Peralatan --}}
        <div class="form-group">
            <label class="form-label" for="peralatan_id">Peralatan</label>
            <select id="peralatan_id"
                    name="peralatan_id"
                    class="form-control {{ $errors->has('peralatan_id') ? 'is-invalid' : '' }}">
                <option value="">-- Pilih Peralatan --</option>
                @foreach($peralatans as $peralatan)
                    <option value="{{ $peralatan->id }}"
                            {{ old('peralatan_id') == $peralatan->id ? 'selected' : '' }}>
                        {{ $peralatan->nama_peralatan }}
                        ({{ $peralatan->kode_peralatan }})
                        &mdash; Stok: {{ $peralatan->stok }}
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
                   value="{{ old('jumlah', 1) }}"
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
                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}">
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
                   value="{{ old('tanggal_rencana_kembali') }}">
            @error('tanggal_rencana_kembali')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        {{-- Keterangan --}}
        <div class="form-group">
            <label class="form-label" for="keterangan">
                Keterangan
                <span style="color:var(--text-muted); font-weight:400; text-transform:none;">(opsional)</span>
            </label>
            <textarea id="keterangan"
                      name="keterangan"
                      class="form-control"
                      rows="3"
                      placeholder="Keperluan peminjaman, dll.">{{ old('keterangan') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection
