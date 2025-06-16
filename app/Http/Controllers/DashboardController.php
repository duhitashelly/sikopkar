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
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des'
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

    public function getTotalBunga()
    {
        try {
            $totalBungaAccrued = 0;
            $bunga_per_bulan_rate = 0.0125; // 1.25%

            // Ambil semua pinjaman yang memiliki angsuran
            $pinjaman = Pinjaman::has('angsuran')->get();

            foreach ($pinjaman as $pinjam) {
                $sisa_pokok_saat_ini = $pinjam->jumlah; // Mulai dari jumlah pinjaman awal (pokok)
                
                // Ambil semua angsuran untuk pinjaman ini, diurutkan berdasarkan tanggal
                $angsuranDibayar = Angsuran::where('id_pinjaman', $pinjam->id_pinjaman)
                                        ->orderBy('tanggal') // Urutkan berdasarkan tanggal angsuran
                                        ->get();

                foreach ($angsuranDibayar as $angs) {
                    // Jika sisa pokok sudah habis, tidak ada bunga lagi yang terakumulasi
                    // dan tidak ada lagi pokok yang bisa dilunasi
                    if ($sisa_pokok_saat_ini <= 0) {
                        break; 
                    }

                    // --- Perhitungan Bunga Menurun per Angsuran ---
                    // 1. Hitung bunga yang terhutang untuk periode ini
                    //    Ini adalah bunga dari sisa pokok sebelum pembayaran angsuran ini
                    $bunga_terhutang_periode_ini = round ($sisa_pokok_saat_ini * $bunga_per_bulan_rate);
                    
                    // 2. Jumlah pembayaran angsuran aktual dari anggota
                    $pembayaran_angsuran_aktual = $angs->jumlah_angsuran;

                    // 3. Tentukan berapa bunga yang terbayar dari angsuran ini
                    //    Kita membayar bunga maksimal sebesar bunga_terhutang_periode_ini
                    //    dan tidak lebih dari jumlah pembayaran angsuran aktual
                    $bunga_yang_terbayar_dari_angsuran_ini = min($pembayaran_angsuran_aktual, $bunga_terhutang_periode_ini);
                    
                    // 4. Tambahkan bunga yang berhasil terbayar ke total akumulasi
                    $totalBungaAccrued += $bunga_yang_terbayar_dari_angsuran_ini;

                    // 5. Hitung berapa pokok yang terbayar dari angsuran ini
                    //    Sisa dari pembayaran angsuran setelah menutupi bunga akan mengurangi pokok
                    $pokok_yang_terbayar_dari_angsuran_ini = $pembayaran_angsuran_aktual - $bunga_yang_terbayar_dari_angsuran_ini;

                    // 6. Kurangi sisa pokok dengan jumlah pokok yang baru saja dilunasi
                    $sisa_pokok_saat_ini -= $pokok_yang_terbayar_dari_angsuran_ini;

                    // Pastikan sisa pokok tidak menjadi negatif (jika ada overpayment)
                    if ($sisa_pokok_saat_ini < 0) {
                        $sisa_pokok_saat_ini = 0;
                    }
                    // --- Akhir Perhitungan Bunga Menurun per Angsuran ---
                }
            }

            // TIDAK ADA LAGI PENAMBAHAN TOTAL POTONGAN ASURANSI DI SINI
            
            $formattedBunga = number_format($totalBungaAccrued, 0, ',', '.');

            return response()->json([
                'total_bunga' => $formattedBunga
            ]);

        } catch (\Exception $e) {
            \Log::error("Error in getTotalBunga: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Metode getTotalPotonganAsuransi() DIHAPUS jika Anda tidak ingin menampilkannya sama sekali
    // Jika Anda masih ingin menampilkannya di tempat lain, biarkan metode ini ada
    /*
    public function getTotalPotonganAsuransi()
    {
        try {
            $totalPotonganAsuransi = Pinjaman::sum(DB::raw('jumlah * 0.02'));
            $formattedAsuransi = number_format($totalPotonganAsuransi, 0, ',', '.');

            return response()->json([
                'total_potongan_asuransi' => $formattedAsuransi
            ]);
        } catch (\Exception $e) {
            \Log::error("Error in getTotalPotonganAsuransi: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    */
}
