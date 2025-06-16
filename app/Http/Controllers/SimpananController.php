<?php

namespace App\Http\Controllers;

use App\Models\Simpanan;
use App\Models\Anggota;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    public function index()
    {
        // Eager-load 'anggota' dan urutkan berdasarkan tanggal terbaru
        $dataSimpanan = Simpanan::with('anggota')
                                ->orderBy('tanggal', 'desc')
                                ->get();

        return view('simpanan.index', compact('dataSimpanan'));
    }

    public function create()
    {
        // Ambil anggota yang statusnya aktif saja
        $anggotas = Anggota::where('status', 'aktif')->get();

        // Buat ID simpanan baru dengan format S001, S002, dst
        $newId = 'S' . str_pad(Simpanan::count() + 1, 3, '0', STR_PAD_LEFT);

        return view('simpanan.create', compact('anggotas', 'newId'));
    }


    public function store(Request $request)
    {
        // Bersihkan titik ribuan dari input jumlah
        $request->merge([
            'jumlah' => str_replace('.', '', $request->jumlah),
        ]);

        // Validasi setelah jumlah dibersihkan
        $validated = $request->validate([
            'id_simpanan'    => 'required|unique:simpanan,id_simpanan',
            'id_anggota'     => 'required|exists:anggota,id_anggota',
            'jenis_simpanan' => 'required|string',
            'jumlah'         => 'required|numeric',
            'tanggal'        => 'required|date',
        ]);

        Simpanan::create($validated);

        return redirect()->route('simpanan.index')
                        ->with('success', 'Data Simpanan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $simpanan  = Simpanan::findOrFail($id);
        $anggotas  = Anggota::all();
        return view('simpanan.edit', compact('simpanan', 'anggotas'));
    }

    public function update(Request $request, $id)
{
    // Bersihkan titik ribuan dari input jumlah
    $request->merge([
        'jumlah' => str_replace('.', '', $request->jumlah),
    ]);

    $validated = $request->validate([
        'id_anggota'     => 'required|exists:anggota,id_anggota',
        'jenis_simpanan' => 'required|string',
        'jumlah'         => 'required|numeric',
        'tanggal'        => 'required|date',
    ]);

    Simpanan::findOrFail($id)->update($validated);

    return redirect()->route('simpanan.index')
                     ->with('success', 'Data Simpanan berhasil diperbarui.');
}

    public function destroy($id)
    {
        Simpanan::findOrFail($id)->delete();

        return redirect()->route('simpanan.index')
                         ->with('success', 'Data Simpanan berhasil dihapus.');
    }
    public function getTotalSimpanan()
    {
        // Menghitung total jumlah simpanan dan mengirimkan sebagai integer
        $totalSimpanan = Simpanan::sum('jumlah');
        return response()->json(['total_simpanan' => (int) $totalSimpanan]); // Mengubah ke integer
    }
}