<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\PinjamanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AngsuranController;
use App\Http\Controllers\LaporanController;
use App\Models\Angsuran;

Route::get('/', function () {
    return redirect()->route('login');
});

#Memanggil Profile
Route::get('/profile', action: [ProfileController::class, 'profile'])->name('profile');

#dashboard
Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);
Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData']);
Route::get('/anggota/active-count', [AnggotaController::class, 'getActiveMembersCount']);
Route::get('/simpanan/total', [SimpananController::class, 'getTotalSimpanan']);
Route::get('/angsuran/total', [AngsuranController::class, 'getTotalAngsuran']);
Route::get('/pinjaman/total', [PinjamanController::class, 'getTotalPinjaman']);
Route::get('/dashboard/kredit-macet', [DashboardController::class, 'getkreditMacet']);
Route::get('/dashboard/total-bunga', [DashboardController::class, 'getTotalBunga']);


#Pinjaman
Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
Route::get('/pinjaman/simulasi', [PinjamanController::class, 'simulasi'])->name('pinjaman.simulasi');
Route::post('/pinjaman/hasil', [PinjamanController::class, 'hasil'])->name('pinjaman.hasil');
Route::get('/pinjaman/cek-status/{id_anggota}', [PinjamanController::class, 'cekStatusPinjaman'])->name('pinjaman.cek-status');
Route::get('/pinjaman/{id}', [PinjamanController::class, 'getPinjamanDetails']);
// Route untuk mengambil data pinjaman anggota

Route::get('/pinjaman/{id_anggota}/data', [PinjamanController::class, 'getPinjamanData'])->name('pinjaman.getData');


#Simpanan
Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
Route::get('/simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create');
Route::post('/simpanan/store', [SimpananController::class, 'store'])->name('simpanan.store');
Route::get('/simpanan/edit/{id}', [SimpananController::class, 'edit'])->name('simpanan.edit');
Route::put('/simpanan/update/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
Route::delete('/simpanan/destroy/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');

#anggota
Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
Route::get('/anggota/{id_anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
Route::put('/anggota/{id_anggota}', [AnggotaController::class, 'update'])->name('anggota.update');
Route::delete('/anggota/{id_anggota}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
Route::get('/laporan/anggota', [AnggotaController::class, 'laporan'])->name('laporan.anggota');


#Laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/anggota', [LaporanController::class, 'anggota'])->name('laporan.anggota');
Route::get('/laporan/anggota/pdf', [LaporanController::class, 'exportAnggotaPdf'])->name('laporan.anggota.pdf');
Route::get('/laporan/anggota/excel', [LaporanController::class, 'exportAnggotaExcel'])->name('laporan.anggota.excel');

Route::get('/laporan/simpanan', [LaporanController::class, 'simpanan'])->name('laporan.simpanan');
Route::get('/laporan/simpanan/pdf', [LaporanController::class, 'exportSimpananPdf'])->name('laporan.simpanan.pdf');
Route::get('/laporan/simpanan/excel', [LaporanController::class, 'exportSimpananExcel'])->name('laporan.simpanan.excel');
// Rute untuk menampilkan detail simpanan per anggota
Route::get('/laporan/simpanan/{id}/detail', [LaporanController::class, 'detailSimpananAnggota'])->name('laporan.simpanan.detail');
// Rute untuk mengunduh laporan simpanan per anggota dalam format PDF
Route::get('/laporan/simpanan/{id}/unduh-pdf', [LaporanController::class, 'unduhSimpananAnggotaPdf'])->name('laporan.simpanan.unduhPdf');


Route::get('/laporan/pinjaman', [LaporanController::class, 'pinjaman'])->name('laporan.pinjaman');
Route::get('/laporan/pinjaman/pdf', [LaporanController::class, 'exportPinjamanPdf'])->name('laporan.pinjaman.pdf');
Route::get('/laporan/pinjaman/excel', [LaporanController::class, 'exportPinjamanExcel'])->name('laporan.pinjaman.excel');
Route::get('/laporan/pinjaman', [LaporanController::class, 'pinjaman'])->name('laporan.pinjaman');
Route::get('/laporan/bunga', [LaporanController::class, 'pendapatanBunga'])->name('laporan.bunga');
    Route::get('/laporan/bunga/pdf', [LaporanController::class, 'exportBungaPdf'])->name('laporan.bunga.pdf');
    Route::get('/laporan/bunga/excel', [LaporanController::class, 'exportBungaExcel'])->name('laporan.bunga.excel');
#Angsuran
Route::prefix('angsuran')->group(function () {
    Route::get('/', [AngsuranController::class, 'index'])->name('angsuran.index');
    Route::get('/create', [AngsuranController::class, 'create'])->name('angsuran.create');
    Route::post('/store', [AngsuranController::class, 'store'])->name('angsuran.store');
});
Route::get('/angsuran/cek-bulan', [AngsuranController::class, 'cekAngsuranBulan']);
Route::get('/angsuran/{id_pinjaman}/{id_anggota}/detail', [AngsuranController::class, 'detail'])->name('angsuran.detail');


#Pengguna
Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');


#Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');
Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/pinjaman/{id_anggota}/data', [PinjamanController::class, 'getPinjamanAktif']);
Route::get('/angsuran/cek-bulan', [AngsuranController::class, 'cekAngsuranBulan']);
#Route::get('/angsuran/jumlah/{id_pinjaman}', function ($id_pinjaman) {
 #   $jumlah = Angsuran::where('id_pinjaman', $id_pinjaman)->count();
  #  return response()->json(['jumlah' => $jumlah]);
#});

Route::get('/angsuran/jumlah/{id_pinjaman}', function ($id_pinjaman) {
    // Hitung jumlah angsuran unik per bulan (jika ingin pastikan tidak double hitung)
    $jumlah = Angsuran::where('id_pinjaman', $id_pinjaman)
        ->select(DB::raw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan'))
        ->distinct()
        ->count();

    return response()->json(['jumlah' => $jumlah]);
});



#Middleware

Route::middleware([CheckRole::class])->group(function () {
    # Dashboard
    Route::get('/home', [DashboardController::class, 'index'])->name('dashboard');

    # Pinjaman
    Route::get('/pinjaman', [PinjamanController::class, 'index'])->name('pinjaman.index');
    Route::get('/pinjaman/create', [PinjamanController::class, 'create'])->name('pinjaman.create');
    Route::post('/pinjaman', [PinjamanController::class, 'store'])->name('pinjaman.store');
    Route::get('/pinjaman/simulasi', [PinjamanController::class, 'simulasi'])->name('pinjaman.simulasi');
    Route::post('/pinjaman/hasil', [PinjamanController::class, 'hasil'])->name('pinjaman.hasil');
    Route::get('/pinjaman/cek-status/{id_anggota}', [PinjamanController::class, 'cekStatusPinjaman'])->name('pinjaman.cek-status');
    Route::get('/pinjaman/{id_anggota}/data', [PinjamanController::class, 'getPinjamanAktif'])->name('pinjaman.getData');
    Route::get('/pinjaman/{id}', [PinjamanController::class, 'getPinjamanDetails']);

    # Anggota
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id_anggota}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{id_anggota}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{id_anggota}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

    #Pengguna
    Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
    Route::get('/pengguna/create', [PenggunaController::class, 'create'])->name('pengguna.create');
    Route::post('/pengguna', [PenggunaController::class, 'store'])->name('pengguna.store');
    Route::get('/pengguna/{id}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
    Route::put('/pengguna/{id}', [PenggunaController::class, 'update'])->name('pengguna.update');
    Route::delete('/pengguna/{id}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');

    # Simpanan
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('/simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create');
    Route::post('/simpanan/store', [SimpananController::class, 'store'])->name('simpanan.store');
    Route::get('/simpanan/edit/{id}', [SimpananController::class, 'edit'])->name('simpanan.edit');
    Route::put('/simpanan/update/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
    Route::delete('/simpanan/destroy/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');

    # Angsuran
    Route::get('/angsuran', [AngsuranController::class, 'index'])->name('angsuran.index');
    Route::get('/angsuran/create', [AngsuranController::class, 'create'])->name('angsuran.create');
    Route::post('/angsuran/store', [AngsuranController::class, 'store'])->name('angsuran.store');
    Route::get('/angsuran/cek-bulan', [AngsuranController::class, 'cekAngsuranBulan']);
    Route::get('/angsuran/jumlah/{id_pinjaman}', function ($id_pinjaman) {
        $jumlah = \App\Models\Angsuran::where('id_pinjaman', $id_pinjaman)
            ->select(DB::raw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan'))
            ->distinct()
            ->count();
        return response()->json(['jumlah' => $jumlah]);
    });
});

