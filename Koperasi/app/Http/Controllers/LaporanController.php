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

class LaporanController extends Controller
{
    // Tampilan awal (misal dashboard laporan)
    public function index()
    {
        return view('laporan.index');
    }

    // ================== ANGGOTA ==================
    public function anggota(Request $request)
    {
        $anggota = collect(); // Inisialisasi koleksi kosong secara default

        // Hanya jalankan query jika kedua tanggal filter sudah diisi
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query = Anggota::query();

            if ($request->filled('nama')) {
                $query->where('nama', 'like', '%' . $request->nama . '%');
            }

            if ($request->filled('jenis_anggota')) {
                $query->where('jenis_anggota', $request->jenis_anggota);
            }

            $query->whereDate('tanggal_daftar', '>=', $request->tanggal_dari);
            $query->whereDate('tanggal_daftar', '<=', $request->tanggal_sampai);

            $anggota = $query->orderBy('tanggal_daftar', 'desc')->get();
        }

        return view('laporan.anggota', compact('anggota'));
    }

    public function exportAnggotaPdf(Request $request)
    {
        // Pastikan filter diterapkan juga saat export
        $anggota = $this->filterAnggota($request);
        $pdf = PDF::loadView('laporan.anggota_pdf', compact('anggota'));
        return $pdf->download('laporan_anggota.pdf');
    }

    public function exportAnggotaExcel(Request $request)
    {
        // Pastikan filter diterapkan juga saat export
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

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_daftar', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_daftar', '<=', $request->tanggal_sampai);
        }

        return $query->get();
    }

    // ================== PINJAMAN ==================
    public function pinjaman(Request $request)
    {
        $pinjamans = collect(); // Inisialisasi koleksi kosong secara default

        // Hanya jalankan query jika kedua tanggal filter sudah diisi
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query = Pinjaman::with('anggota');

            // Filter nama anggota
            if ($request->filled('nama')) {
                $query->whereHas('anggota', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->nama . '%');
                });
            }

            // Filter status pinjaman
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter tanggal dari sampai
            $query->whereBetween('tanggal_pinjaman', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);

            $pinjamans = $query->orderBy('tanggal_pinjaman', 'desc')->get();
        }

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

        if ($request->filled('tanggal_dari')) { // Changed from 'tahun' to 'tanggal_dari'
            $query->whereDate('tanggal_pinjaman', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) { // Added 'tanggal_sampai'
            $query->whereDate('tanggal_pinjaman', '<=', $request->tanggal_sampai);
        }


        return $query->get();
    }

    // ================== SIMPANAN ==================
    public function simpanan(Request $request)
    {
        $simpanan = collect(); // Inisialisasi koleksi kosong secara default

        // Hanya jalankan query jika kedua tanggal filter sudah diisi
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query = \App\Models\Simpanan::query();

            // Filter berdasarkan nama anggota
            if ($request->filled('nama')) {
                $query->whereHas('anggota', function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->nama . '%');
                });
            }

            // Filter berdasarkan jenis simpanan
            if ($request->filled('jenis_simpanan')) {
                $query->where('jenis_simpanan', $request->jenis_simpanan);
            }

            // Filter berdasarkan rentang tanggal
            $query->whereBetween('tanggal', [$request->tanggal_dari, $request->tanggal_sampai]);

            $simpanan = $query->with('anggota')->latest()->get();
        }

        return view('laporan.simpanan', compact('simpanan'));
    }


    public function exportSimpananPdf(Request $request)
    {
        $simpanan = $this->filterSimpanan($request);
        $pdf = PDF::loadView('laporan.simpanan_pdf', compact('simpanan'));
        return $pdf->download('laporan_simpanan.pdf');
    }

    public function exportSimpananExcel(Request $request)
    {
        return Excel::download(new SimpananExport($request), 'laporan_simpanan.xlsx');
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
            $query->where('jenis_simpanan', $jenisSimpananMap[$request->jenis_simpanan]);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        return $query->get();
    }

    // ================== PENDAPATAN BUNGA ==================

    public function pendapatanBunga(Request $request)
    {
        $pinjamans = collect(); // Inisialisasi koleksi kosong secara default

        // Hanya jalankan query jika kedua tanggal filter sudah diisi
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query = Pinjaman::with(['anggota', 'angsuran'])
                ->whereHas('angsuran')
                ->when($request->nama, function ($q) use ($request) {
                    $q->whereHas('anggota', function ($subQuery) use ($request) {
                        $subQuery->where('nama', 'like', '%' . $request->nama . '%');
                    });
                })
                ->whereDate('tanggal_pinjaman', '>=', $request->tanggal_dari)
                ->whereDate('tanggal_pinjaman', '<=', $request->tanggal_sampai)
                ->get();

            foreach ($query as $pinjaman) {
                $sisa = $pinjaman->jumlah;
                $total_bunga = 0;

                $angsuranLunas = $pinjaman->angsuran->sortBy('tanggal'); // urutkan berdasarkan tanggal

                foreach ($angsuranLunas as $angsuran) {
                    $bunga = $sisa * 0.0125;
                    $pokok = $angsuran->jumlah_angsuran - $bunga;
                    $total_bunga += $bunga;
                    $sisa -= $pokok;

                    if ($sisa <= 0) {
                        $sisa = 0;
                        break;
                    }
                }

                $pinjaman->sisa_pinjaman_hitung = $sisa;
                $pinjaman->total_bunga_hitung = $total_bunga;
            }
            $pinjamans = $query; // Assign the results to $pinjamans
        }

        return view('laporan.bunga', [
            'pinjamans' => $pinjamans
        ]);
    }

    public function exportPendapatanBungaPdf(Request $request)
    {
        $pinjamans = $this->filterBunga($request); // Menggunakan filterBunga yang sudah diperbarui
        $totalBunga = 0;
        foreach ($pinjamans as $pinjaman) {
            $totalBunga += round($pinjaman->total_bunga_hitung ?? 0);
        }
        $pdf = PDF::loadView('laporan.bunga_pdf', compact('pinjamans', 'totalBunga'));
        return $pdf->download('laporan_pendapatan_bunga.pdf');
    }

    public function exportBungaPdf(Request $request)
    {
        $pinjamans = $this->filterBunga($request);
        $totalBunga = 0;
        foreach ($pinjamans as $pinjaman) {
            $totalBunga += round($pinjaman->total_bunga_hitung ?? 0);
        }
        $pdf = PDF::loadView('laporan.bunga_pdf', compact('pinjamans', 'totalBunga'));
        return $pdf->download('laporan_bunga.pdf');
    }

    public function exportBungaExcel(Request $request)
    {
        $pinjamans = $this->filterBunga($request);
        return Excel::download(new PendapatanBungaExport($pinjamans), 'laporan_bunga.xlsx');
    }

    protected function filterBunga(Request $request)
    {
        $query = Pinjaman::with(['anggota', 'angsuran'])
            ->whereHas('angsuran'); // hanya pinjaman yang memiliki angsuran

        if ($request->filled('nama')) {
            $query->whereHas('anggota', function ($subQuery) use ($request) {
                $subQuery->where('nama', 'like', '%' . $request->nama . '%');
            });
        }

        // Changed from 'tahun' to date range for filtering
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjaman', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjaman', '<=', $request->tanggal_sampai);
        }

        $pinjamans = $query->get(); // Get the collection here

        foreach ($pinjamans as $pinjaman) {
            $sisa = $pinjaman->jumlah;
            $total_bunga = 0;

            $angsuranLunas = $pinjaman->angsuran->sortBy('tanggal'); // urutkan berdasarkan tanggal

            foreach ($angsuranLunas as $angsuran) {
                $bunga = $sisa * 0.0125;
                $pokok = $angsuran->jumlah_angsuran - $bunga;
                $total_bunga += $bunga;
                $sisa -= $pokok;

                if ($sisa <= 0) {
                    $sisa = 0;
                    break;
                }
            }

            $pinjaman->sisa_pinjaman_hitung = $sisa;
            $pinjaman->total_bunga_hitung = $total_bunga;
        }

        return $pinjamans;
    }

    private function getPinjamansWithBunga(Request $request)
    {
        $query = Pinjaman::with(['anggota', 'angsuran'])
            ->whereHas('angsuran')
            ->when($request->nama, function ($q) use ($request) {
                $q->whereHas('anggota', function ($subQuery) use ($request) {
                    $subQuery->where('nama', 'like', '%' . $request->nama . '%');
                });
            })
            // Changed from 'tahun' to date range for filtering
            ->when($request->tanggal_dari, function ($q) use ($request) {
                $q->whereDate('tanggal_pinjaman', '>=', $request->tanggal_dari);
            })
            ->when($request->tanggal_sampai, function ($q) use ($request) {
                $q->whereDate('tanggal_pinjaman', '<=', $request->tanggal_sampai);
            })
            ->get();

        foreach ($query as $pinjaman) {
            $sisa = $pinjaman->jumlah;
            $total_bunga = 0;
            $angsuranLunas = $pinjaman->angsuran->sortBy('tanggal');

            foreach ($angsuranLunas as $angsuran) {
                $bunga = $sisa * 0.0125;
                $pokok = $angsuran->jumlah_angsuran - $bunga;
                $total_bunga += $bunga;
                $sisa -= $pokok;

                if ($sisa <= 0) {
                    $sisa = 0;
                    break;
                }
            }

            $pinjaman->sisa_pinjaman_hitung = $sisa;
            $pinjaman->total_bunga_hitung = $total_bunga;
        }

        return $query;
    }
}