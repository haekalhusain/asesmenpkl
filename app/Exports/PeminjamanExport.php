<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PHPExcel\Style\Font;
use PhpOffice\PHPExcel\Style\Alignment;
use PhpOffice\PHPExcel\Style\Border;

class PeminjamanExport implements FromCollection, WithHeadings, WithStyles
{
    protected $search;
    protected $status;

    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Peminjaman::with(['pengguna', 'barang']);

        if ($this->search) {
            $q = $this->search;
            $query->where(function ($q2) use ($q) {
                $q2->whereHas('pengguna', fn($q3) => $q3->where('nama_peminjam', 'like', "%$q%"))
                   ->orWhereHas('barang', fn($q3) =>
                       $q3->where('nama_barang', 'like', "%$q%")
                          ->orWhere('kategori_barang', 'like', "%$q%")
                   )
                   ->orWhere('kode_peminjaman', 'like', "%$q%");
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get()->map(function ($item, $key) {
            $st = $item->status instanceof \App\Enums\LoanStatus ? $item->status->value : $item->status;
            return [
                'No'                   => $key + 1,
                'Kode Peminjaman'      => $item->kode_peminjaman,
                'Peminjam'             => $item->pengguna->nama_peminjam ?? '-',
                'Kelas'                => $item->pengguna->kelas ?? '-',
                'Barang'               => $item->barang->nama_barang ?? '-',
                'Kategori'             => $item->barang->kategori_barang ?? '-',
                'Jumlah'               => $item->jumlah,
                'Tanggal Pinjam'       => $item->tanggal_pinjam?->format('d/m/Y') ?? '-',
                'Rencana Kembali'      => $item->tanggal_rencana_kembali?->format('d/m/Y') ?? '-',
                'Tanggal Dikembalikan' => $item->tanggal_kembali?->format('d/m/Y') ?? '-',
                'Status'               => $st,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Peminjaman',
            'Peminjam',
            'Kelas',
            'Barang',
            'Kategori',
            'Jumlah',
            'Tanggal Pinjam',
            'Rencana Kembali',
            'Tanggal Dikembalikan',
            'Status',
        ];
    }

    public function styles($sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1F77D5']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }
}
