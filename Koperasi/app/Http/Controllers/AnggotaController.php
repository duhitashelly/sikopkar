<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Anggota;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::all();
        return view('anggota.index', compact('anggota'));
    }

    public function create()
    {
        $last = Anggota::orderBy('id_anggota', 'desc')->first();

        if($last){
            $number = intval(substr($last->id_anggota, 2)) + 1;
            $newId = 'AG' . str_pad($number, 3 ,'0', STR_PAD_LEFT);
        }else{
            $newId = 'AG001';
        }
        return view('anggota.create', compact('newId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|unique:anggota,id_anggota',
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'kontak' => 'nullable|string|max:20',
            'status' => 'required|in:aktif,tidak aktif',
            'jenis_anggota' => 'required|in:nonkontrak,pns,pensiun',
            'tanggal_daftar' => 'required|date',
        ]);

        DB::table('anggota')->insert([
            'id_anggota' => $request->id_anggota,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'status' => $request->status,
            'jenis_anggota' => $request->jenis_anggota,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil disimpan.');
    }
    
    public function edit($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);
        return view('anggota.update', compact('anggota'));
    }

    public function update(Request $request, $id_anggota)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:20',
            'jenis_anggota' => 'required|in:nonkontrak,pns,pensiun',
            'tanggal_daftar' => 'nullable|date',
        ]);

        $anggota = Anggota::findOrFail($id_anggota);
        $anggota->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kontak' => $request->kontak,
            'status' => $request->status,
            'jenis_anggota' => $request->jenis_anggota,
            'tanggal_daftar' => $request->tanggal_daftar,
        ]);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy($id_anggota)
    {
        $anggota = Anggota::findOrFail($id_anggota);
        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }

    public function getActiveMembersCount()
    {
        // Menghitung jumlah anggota dengan status aktif
        $activeCount = Anggota::where('status', 'aktif')->count();
        return response()->json(['active_members' => $activeCount]);
    }

    

}