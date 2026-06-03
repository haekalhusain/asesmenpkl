@extends('layouts.user')
@section('title', 'Edit Peminjaman')

@section('content')

<div class="page-header">
    <h1 class="page-title">Edit Peminjaman</h1>
    <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">&#8592; Kembali</a>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('user.peminjaman.update', $peminjaman) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- Kode Peminjaman (readonly) --}}
        <div class="form-group">
            <label class="form-label">Kode Peminjaman</label>
            <input type="text"
                   class="form-control"
                   value="{{ $peminjaman->kode_peminjaman }}"
                   readonly
                   style="opacity:0.5; cursor:not-allowed;">
        </div>

        {{-- Barang (readonly) --}}
        <div class="form-group">
            <label class="form-label">Barang</label>
            <input type="text"
                   class="form-control"
                   value="{{ $peminjaman->barang->nama_barang ?? '-' }}"
                   readonly
                   style="opacity:0.5; cursor:not-allowed;">
        </div>

        {{-- Jumlah (readonly) --}}
        <div class="form-group">
            <label class="form-label">Jumlah</label>
            <input type="text"
                   class="form-control"
                   value="{{ $peminjaman->jumlah }} unit"
                   readonly
                   style="opacity:0.5; cursor:not-allowed;">
        </div>

        {{-- Tanggal Pinjam --}}
        <div class="form-group">
            <label class="form-label" for="tanggal_pinjam">Tanggal Pinjam</label>
            <input type="date"
                   id="tanggal_pinjam"
                   name="tanggal_pinjam"
                   class="form-control {{ $errors->has('tanggal_pinjam') ? 'is-invalid' : '' }}"
                   value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam?->format('Y-m-d')) }}">
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
                   value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali?->format('Y-m-d')) }}">
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
                   value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali?->format('Y-m-d')) }}">
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
                @php $currentStatus = $peminjaman->status instanceof \App\Enums\LoanStatus ? $peminjaman->status->value : $peminjaman->status; @endphp
                <option value="Dipinjam"     {{ old('status', $currentStatus) === 'Dipinjam'     ? 'selected' : '' }}>Dipinjam</option>
                <option value="Dikembalikan" {{ old('status', $currentStatus) === 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
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
            <a href="{{ route('user.peminjaman.index') }}" class="btn btn-neutral">Batal</a>
        </div>
    </form>
</div>

@endsection
