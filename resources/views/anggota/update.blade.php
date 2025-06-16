@extends('layouts.app')

@section('title', 'Edit Data Anggota')

@section('styles')
<style>
    body { background-color: #EDECFF !important; }

    .form-card {
        background-color: white;
        border-radius: 10px;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .form-header {
        background-color: #e0dbf9;
        padding: 20px 30px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .form-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        color: #5D3FD3;
    }

    .form-body {
        padding: 30px;
    }

    label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    input.form-control,
    select.form-control {
        margin-bottom: 15px;
    }

    .btn-simpan {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-kembali {
        background-color: #28a745;
        color: white;
        border: none;
        margin-left: 10px;
    }

    .text-danger {
        color: red;
    }
</style>
@endsection

@section('content')
<div class="form-card">
    <div class="form-header">
        <h2>Edit Data Anggota</h2>
    </div>

    <div class="form-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('anggota.update', $anggota->id_anggota) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="id_anggota">ID Anggota</label>
                <input type="text" class="form-control" id="id_anggota" name="id_anggota" value="{{ $anggota->id_anggota }}" readonly>
            </div>

            <div class="mb-3">
                <label for="nama">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $anggota->nama }}" required>
            </div>

            <div class="mb-3">
                <label for="alamat">Alamat</label>
                <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $anggota->alamat }}">
            </div>

            <div class="mb-3">
                <label for="kontak">Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" value="{{ $anggota->kontak }}">
            </div>

            <div class="mb-3">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="aktif" {{ $anggota->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ $anggota->status == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="jenis_anggota">Jenis Anggota</label>
                <select class="form-control" id="jenis_anggota" name="jenis_anggota" required>
                    <option value="">-- Pilih Jenis Anggota --</option>
                    <option value="nonkontrak" {{ $anggota->jenis_anggota == 'nonkontrak' ? 'selected' : '' }}>Nonkontrak</option>
                    <option value="pns" {{ $anggota->jenis_anggota == 'pns' ? 'selected' : '' }}>PNS</option>
                    <option value="pensiun" {{ $anggota->jenis_anggota == 'pensiun' ? 'selected' : '' }}>Pensiun</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="tanggal_daftar">Tanggal Daftar</label>
                <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar" value="{{ $anggota->tanggal_daftar }}" required>
            </div>

            <button type="submit" class="btn btn-simpan">Simpan</button>
            <a href="{{ route('anggota.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>
@endsection
