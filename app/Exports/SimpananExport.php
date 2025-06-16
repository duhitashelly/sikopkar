<?php

namespace App\Exports;

use App\Models\Simpanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class SimpananExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Simpanan::with('anggota');

        // Filter berdasarkan jenis simpanan
        if ($this->request->filled('jenis_simpanan')) {
            // Jika nilai di request adalah 'pokok', 'wajib', 'sukarela',
            // Anda mungkin perlu memetakan ke nilai yang disimpan di DB
            $jenisSimpananMap = [
                'pokok' => 'Simpanan Pokok',
                'wajib' => 'Simpanan Wajib',
                'sukarela' => 'Simpanan Sukarela',
            ];
            // Pastikan mapping ini sesuai dengan nilai di DB
            if (isset($jenisSimpananMap[$this->request->jenis_simpanan])) {
                $query->where('jenis_simpanan', $jenisSimpananMap[$this->request->jenis_simpanan]);
            } else {
                // Jika tidak ada mapping, gunakan nilai request langsung (fallback)
                $query->where('jenis_simpanan', $this->request->jenis_simpanan);
            }
        }

        // Filter berdasarkan nama anggota (kemungkinan ini yang digunakan di form)
        if ($this->request->filled('nama')) {
            $query->whereHas('anggota', function ($q) {
                $q->where('nama', 'like', '%' . $this->request->nama . '%');
            });
        }

        // Filter berdasarkan ID anggota (jika ada di form Anda)
        if ($this->request->filled('id_anggota')) {
            $query->where('id_anggota', $this->request->id_anggota);
        }

        // PERBAIKAN DI SINI: Gunakan 'tanggal_dari' sesuai dengan nama input di form
        if ($this->request->filled('tanggal_dari') && $this->request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$this->request->tanggal_dari, $this->request->tanggal_sampai]);
        }

        return $query->get()->map(function ($item) {
            return [
                'ID Simpanan' => $item->id_simpanan,
                'Nama Anggota' => $item->anggota->nama ?? '-', // Pastikan relasi anggota ada
                'Jenis Simpanan' => ucfirst($item->jenis_simpanan),
                'Jumlah' => $item->jumlah,
                'Tanggal' => \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'), // Format tanggal
                'Status Anggota' => $item->anggota->status ?? '-', // Akses status melalui relasi anggota
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Simpanan',
            'Nama Anggota',
            'Jenis Simpanan',
            'Jumlah',
            'Tanggal',
            'Status Anggota', // Pastikan ini adalah kolom yang Anda inginkan
        ];
    }
}