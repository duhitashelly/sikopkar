<?php

namespace App\Exports;

use App\Models\Pinjaman;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class PinjamanExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Pinjaman::with('anggota');

        if ($this->request->filled('nama')) {
            $query->whereHas('anggota', function ($q) {
                $q->where('nama', 'like', '%' . $this->request->nama . '%');
            });
        }

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        // PERBAIKAN DI SINI: Gunakan 'tanggal_dari' sesuai dengan nama input di form
        if ($this->request->filled('tanggal_dari') && $this->request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_pinjaman', [$this->request->tanggal_dari, $this->request->tanggal_sampai]);
        }

        $pinjamans = $query->get();

        return view('laporan.pinjaman_excel', compact('pinjamans'));
    }
}