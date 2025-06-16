<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;

class PendapatanBungaExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected $pinjamans;
    protected $totalBunga = 0;
    protected $rowNumber = 0; // tambahkan ini

    public function __construct($pinjamans)
    {
        $this->pinjamans = $pinjamans;
        foreach ($pinjamans as $pinjaman) {
            $this->totalBunga += $pinjaman->total_bunga_hitung;
        }
    }

    public function collection()
    {
        return collect($this->pinjamans);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Jumlah Pinjaman',
            'Tenor (bulan)',
            'Tanggal Pinjaman',
            'Status',
            'Total Bunga (Rp)'
        ];
    }

    public function map($pinjaman): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $pinjaman->anggota->nama ?? '-',
            'Rp ' . number_format($pinjaman->jumlah, 0, ',', '.'),
            $pinjaman->tenor,
            \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d/m/Y'),
            ucfirst($pinjaman->status),
            'Rp ' . number_format($pinjaman->total_bunga_hitung, 0, ',', '.'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $rowCount = count($this->pinjamans) + 2;

        $sheet->setCellValue('F' . $rowCount, 'Total Bunga Keseluruhan');
        $sheet->setCellValue('G' . $rowCount, 'Rp ' . number_format($this->totalBunga, 0, ',', '.'));

        return [
            1 => ['font' => ['bold' => true]],
            $rowCount => ['font' => ['bold' => true]],
            'A1:G' . $rowCount => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}
