<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\Simpanan;
use PDF;
use Excel;
use App\Exports\AnggotaExport;
use App\Exports\PinjamanExport;
use App\Exports\PendapatanBungaExport;
use App\Exports\SimpananExport;
use App\Exports\SimpananAnggotaExport;
use Illuminate\Support\Str;

class LaporanController extends Controller
{

    const INTEREST_RATE = 0.0125; 

    public function index()
    {
        return view('laporan.index');
    }

    ## Laporan Anggota 

    public function anggota(Request $request)
    {
        $anggota = $this->filterAnggota($request);
        return view('laporan.anggota', compact('anggota'));
    }

    public function exportAnggotaPdf(Request $request)
    {
        $anggota = $this->filterAnggota($request);
        $pdf = PDF::loadView('laporan.anggota_pdf', compact('anggota'));
        return $pdf->download('laporan_anggota.pdf');
    }

    public function exportAnggotaExcel(Request $request)
    {
        return Excel::download(new AnggotaExport($request), 'laporan_anggota.xlsx');
    }

    private function filterAnggota(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        if ($request->filled('jenis_anggota')) {
            $query->where('jenis_anggota', $request->jenis_anggota);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_daftar', '>=', $request->tanggal_dari)
                  ->whereDate('tanggal_daftar', '<=', $request->tanggal_sampai);
        }

        return $query->orderBy('tanggal_daftar', 'desc')->get();
    }

    ## Laporan Pinjaman 

    public function pinjaman(Request $request)
    {
        $pinjamans = $this->filterPinjaman($request);
        return view('laporan.pinjaman', compact('pinjamans'));
    }

    public function exportPinjamanPdf(Request $request)
    {
        $pinjamans = $this->filterPinjaman($request);
        $pdf = PDF::loadView('laporan.pinjaman_pdf', compact('pinjamans'));
        return $pdf->download('laporan_pinjaman.pdf');
    }

    public function exportPinjamanExcel(Request $request)
    {
        return Excel::download(new PinjamanExport($request), 'laporan_pinjaman.xlsx');
    }

    private function filterPinjaman(Request $request)
    {
        $query = Pinjaman::with('anggota');

        if ($request->filled('nama')) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_pinjaman', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        }

        return $query->orderBy('tanggal_pinjaman', 'desc')->get();
    }

    ## **Laporan Simpanan **

    public function simpanan(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereHas('simpanan', function ($q) use ($request) {
                $q->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
            });
        }

        $anggotaWithSimpanan = $query->withSum(['simpanan' => function ($q) use ($request) {
            if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
                $q->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
            }
        }], 'jumlah')
            ->orderBy('nama')
            ->get();

        return view('laporan.simpanan', compact('anggotaWithSimpanan'));
    }

    public function detailSimpananAnggota($id)
    {
        $anggota = Anggota::with(['simpanan' => function($query) {
            $query->orderBy('tanggal', 'asc'); 
        }])->findOrFail($id);
        return view('laporan.simpanan_detail', compact('anggota'));
    }

    public function unduhSimpananAnggotaPdf($id)
    {
        $anggota = Anggota::with(['simpanan' => function($query) {
            $query->orderBy('tanggal', 'asc');
        }])->findOrFail($id);
        $pdf = PDF::loadView('laporan.simpanan_anggota_pdf', compact('anggota'));
        return $pdf->download('laporan_simpanan_' . Str::slug($anggota->nama) . '.pdf');
    }

    public function unduhSimpananAnggotaExcel($id)
    {
        $anggota = Anggota::with(['simpanan' => function($query) {
            $query->orderBy('tanggal', 'asc');
        }])->findOrFail($id);
        return Excel::download(new SimpananAnggotaExport($anggota), 'laporan_simpanan_' . Str::slug($anggota->nama) . '.xlsx');
    }

    public function exportSimpananPdf(Request $request)
    {
        $simpanan = $this->filterSimpanan($request);
        $pdf = PDF::loadView('laporan.simpanan_pdf', compact('simpanan'));
        return $pdf->download('laporan_simpanan_all.pdf'); 
    }

    public function exportSimpananExcel(Request $request)
    {
        return Excel::download(new SimpananExport($request), 'laporan_simpanan_all.xlsx'); 
    }

    private function filterSimpanan(Request $request)
    {
        $query = Simpanan::with('anggota');

        if ($request->filled('nama')) {
            $query->whereHas('anggota', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama . '%');
            });
        }
        if ($request->filled('jenis_simpanan')) {
            $jenisSimpananMap = [
                'pokok' => 'Simpanan Pokok',
                'wajib' => 'Simpanan Wajib',
                'sukarela' => 'Simpanan Sukarela',
            ];
            if (isset($jenisSimpananMap[$request->jenis_simpanan])) {
                $query->where('jenis_simpanan', $jenisSimpananMap[$request->jenis_simpanan]);
            }
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);
        }

        return $query->latest()->get();
    }

    // Laporan Pendapatan Bunga 

    public function pendapatanBunga(Request $request)
    {
        $pinjamans = Pinjaman::with('anggota')
            ->when($request->filled('tanggal_dari') && $request->filled('tanggal_sampai'), function ($query) use ($request) {
                $query->whereBetween('tanggal_pinjaman', [$request->tanggal_dari, $request->tanggal_sampai]);
            })
            ->orderBy('tanggal_pinjaman', 'desc')
            ->get();

        $this->calculateInterestDetails($pinjamans);

        return view('laporan.bunga', ['pinjamans' => $pinjamans]);
    }

    public function exportBungaPdf(Request $request)
    {
        $pinjamans = $this->getFilteredLoansForInterest($request);
        $this->calculateInterestDetails($pinjamans);

        $totalBunga = $pinjamans->sum('total_bunga_hitung');

        $pdf = PDF::loadView('laporan.bunga_pdf', compact('pinjamans', 'totalBunga'));
        return $pdf->download('laporan_pendapatan_bunga.pdf');
    }

    public function exportBungaExcel(Request $request)
    {
        $pinjamans = $this->getFilteredLoansForInterest($request);
        $this->calculateInterestDetails($pinjamans);

        return Excel::download(new PendapatanBungaExport($pinjamans), 'laporan_bunga.xlsx');
    }

    private function getFilteredLoansForInterest(Request $request)
    {
        $query = Pinjaman::with(['anggota', 'angsuran'])
            ->whereHas('angsuran'); 

        if ($request->filled('nama')) {
            $query->whereHas('anggota', function ($subQuery) use ($request) {
                $subQuery->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjaman', '>=', $request->tanggal_dari)
                  ->whereDate('tanggal_pinjaman', '<=', $request->tanggal_sampai);
        }

        return $query->get();
    }

    private function calculateInterestDetails($pinjamans)
    {
        foreach ($pinjamans as $pinjaman) {
            $sisa = $pinjaman->jumlah;
            $total_bunga = 0;
            $total_angsuran_dibayar = 0;

            $angsuranLunas = $pinjaman->angsuran->sortBy('tanggal');
            $jumlah_angsuran_dibayar = $angsuranLunas->count();

            foreach ($angsuranLunas as $angsuran) {
                $total_angsuran_dibayar += $angsuran->jumlah_angsuran;

                $bunga = round($sisa * self::INTEREST_RATE);
                $pokok = round($angsuran->jumlah_angsuran - $bunga);
                $total_bunga += $bunga;
                $sisa -= $pokok;

                if ($sisa <= 0) {
                    $sisa = 0;
                    break;
                }
            }

            $pinjaman->sisa_pinjaman_hitung = $sisa;
            $pinjaman->total_bunga_hitung = $total_bunga;
            $pinjaman->total_angsuran_dibayar = $total_angsuran_dibayar; // <== ini penting
            $pinjaman->sisa_tenor = max(0, $pinjaman->tenor - $jumlah_angsuran_dibayar);
        }

        return $pinjamans;
    }


}