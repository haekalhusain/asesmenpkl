<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .date {
            text-align: right;
            color: #666;
            font-size: 11px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #1F77D5;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #333;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status-dipinjam {
            background-color: #fff3cd;
            color: #856404;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-dikembalikan {
            background-color: #d4edda;
            color: #155724;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-terlambat {
            background-color: #f8d7da;
            color: #721c24;
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #999;
            font-size: 10px;
        }
        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA PEMINJAMAN</h1>
        <p style="margin: 5px 0; color: #666;">Sistem Manajemen Peminjaman Barang</p>
    </div>

    <div class="date">
        <strong>Tanggal Cetak:</strong> {{ date('d F Y H:i:s') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 10%;">Kode</th>
                <th style="width: 12%;">Peminjam</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 12%;">Barang</th>
                <th style="width: 10%;">Tgl Pinjam</th>
                <th style="width: 10%;">Rencana Kembali</th>
                <th style="width: 10%;">Tgl Dikembalikan</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamans as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->kode_peminjaman }}</td>
                <td>{{ $item->pengguna->nama_peminjam ?? '-' }}</td>
                <td>{{ $item->pengguna->kelas ?? '-' }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->tanggal_pinjam?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                <td>
                    @php $st = $item->status instanceof \App\Enums\LoanStatus ? $item->status->value : $item->status; @endphp
                    @if($st === 'Dipinjam')
                        <span class="status-dipinjam">Dipinjam</span>
                    @elseif($st === 'Dikembalikan')
                        <span class="status-dikembalikan">Dikembalikan</span>
                    @else
                        <span class="status-terlambat">Terlambat</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; padding: 20px; color: #999;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>© {{ date('Y') }} - Sistem Manajemen Peminjaman Barang. Dokumen ini dibuat secara otomatis.</p>
    </div>
</body>
</html>
