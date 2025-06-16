<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Anggota;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Models\Angsuran;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function getDashboardData()
    {
        $activeMembersCount = Anggota::where('status', 'aktif')->count();
        $totalSimpanan = Simpanan::sum('jumlah');
        $totalPinjaman = Pinjaman::sum('jumlah');
        $totalAngsuran = Angsuran::sum('jumlah');

        return response()->json([
            'activeMembersCount' => $activeMembersCount,
            'totalSimpanan' => $totalSimpanan,
            'totalPinjaman' => $totalPinjaman,
            'totalAngsuran' => $totalAngsuran,
        ]);
    }

    public function getChartData()
    {
        $tahun = date('Y'); // tahun sekarang, bisa diganti sesuai kebutuhan

        // Array nama bulan (singkat agar sesuai di chart)
        $bulanNama = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei',
            6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt',
            11 => 'Nov', 12 => 'Des'
        ];

        // Fungsi bantu inisialisasi array 12 bulan dengan nilai 0
        $initMonthlyArray = function () {
            return array_fill(1, 12, 0);
        };

        // Ambil data simpanan per bulan untuk tahun ini
        $simpananDataRaw = DB::table('simpanan')
            ->selectRaw('MONTH(tanggal) as month, SUM(jumlah) as total')
            ->whereYear('tanggal', $tahun)
            ->groupBy('month')
            ->pluck('total', 'month'); // key = month, value = total

        // Ambil data pinjaman per bulan tahun ini
        $pinjamanDataRaw = DB::table('pinjaman')
            ->selectRaw('MONTH(tanggal_pinjaman) as month, SUM(jumlah) as total')
            ->whereYear('tanggal_pinjaman', $tahun)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Ambil data anggota baru per bulan tahun ini
        $anggotaDataRaw = DB::table('anggota')
            ->selectRaw('MONTH(tanggal_daftar) as month, COUNT(*) as total')
            ->whereYear('tanggal_daftar', $tahun)
            ->groupBy('month')
            ->pluck('total', 'month');

        // Inisialisasi array kosong 12 bulan dengan 0
        $simpananData = $initMonthlyArray();
        $pinjamanData = $initMonthlyArray();
        $anggotaData = $initMonthlyArray();

        // Isi data simpanan dengan data dari DB (jika ada)
        foreach ($simpananDataRaw as $month => $total) {
            $simpananData[$month] = (float) $total;
        }
        // Isi data pinjaman
        foreach ($pinjamanDataRaw as $month => $total) {
            $pinjamanData[$month] = (float) $total;
        }
        // Isi data anggota
        foreach ($anggotaDataRaw as $month => $total) {
            $anggotaData[$month] = (int) $total;
        }

        // Format data agar jadi array objek seperti frontend harapkan
        $formatData = function ($dataArray) use ($bulanNama) {
            $result = [];
            for ($i = 1; $i <= 12; $i++) {
                $result[] = [
                    'month' => $bulanNama[$i],
                    'total' => $dataArray[$i] ?? 0
                ];
            }
            return $result;
        };

        // Kas Masuk (tidak diubah)
        $kasMasuk = DB::table('simpanan')
        ->join('anggota', 'simpanan.id_anggota', '=', 'anggota.id_anggota') // ganti sesuai nama kolom
        ->select('anggota.nama', DB::raw('SUM(simpanan.jumlah) as total'))
        ->groupBy('simpanan.id_anggota', 'anggota.nama')
        ->get();

        // Donut chart data
        $donutData = [
            'anggota' => Anggota::count(),
            'pendapatan' => Simpanan::sum('jumlah'),
            'angsuran' => Angsuran::sum('jumlah_angsuran'),
            'pinjaman' => Pinjaman::sum('jumlah')
        ];

        return response()->json([
            'labels' => array_values($bulanNama), // ['Jan', 'Feb', ..., 'Des']
            'simpanan' => $formatData($simpananData),
            'pinjaman' => $formatData($pinjamanData),
            'anggota' => $formatData($anggotaData),
            'kas_masuk' => $kasMasuk,
            'donut' => $donutData,
        ]);
    }

public function getKreditMacet()
{
    try {
        $kreditMacet = DB::table('pinjaman')
            ->join('anggota', 'pinjaman.id_anggota', '=', 'anggota.id_anggota')
            ->select('anggota.nama', DB::raw('SUM(pinjaman.jumlah) as total'))
            ->where('anggota.status', 'tidak aktif')
            ->where('pinjaman.status', 'Belum Lunas')
            ->groupBy('anggota.nama')
            ->havingRaw('total > 0')
            ->get();

            $formatted = $kreditMacet->map(function ($item) {
            $item->total = 'Rp ' . number_format($item->total, 0, ',', '.');
            return $item;
        });
        return response()->json($kreditMacet);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}