<?php

namespace App\Exports;

use App\Models\Anggota;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class AnggotaExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Anggota::query();

        if ($this->request->filled('nama')) {
            $query->where('nama', 'like', '%' . $this->request->nama . '%');
        }

        // Sepertinya Anda tidak punya filter 'status' di form laporan anggota.
        // Jika tidak ada di form, baris ini bisa dihapus atau diabaikan saja.
        // if ($this->request->filled('status')) {
        //     $query->where('status', $this->request->status);
        // }

        if ($this->request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $this->request->jenis_anggota);
        }

        // PERBAIKAN DI SINI: Gunakan 'tanggal_dari' sesuai dengan nama input di form
        if ($this->request->filled('tanggal_dari') && $this->request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_daftar', [$this->request->tanggal_dari, $this->request->tanggal_sampai]);
        }

        $anggota = $query->get();

        return view('laporan.anggota_excel', compact('anggota'));
    }
}