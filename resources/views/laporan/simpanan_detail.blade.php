@extends('layouts.app')

@section('title', 'Detail Simpanan Anggota')

@section('styles')
<style>
    body {
        background-color: #EDECFF;
    }

    .container-custom {
        background: white;
        padding: 20px;
        border-radius: 10px;
    }

    table thead th {
        background-color: #9288BC;
        color: white;
        text-align: center;
        vertical-align: middle;
    }

    table tbody td {
        text-align: center; /* Default center align for all tbody cells */
    }

    /* Override for specific columns if needed */
    table tbody td:nth-child(2) { /* For Nama Anggota */
        text-align: left;
    }
    table tbody td:nth-child(3) { /* For Jenis Simpanan */
        text-align: left;
    }

    .btn-green {
        background-color: #28a745; /* Bootstrap 'success' green */
        border-color: #28a745;
        color: white;
    }

    .btn-green:hover {
        background-color: #218838;
        border-color: #1e7e34;
        color: white;
    }

    h2, h4, .text-dark {
        color: black !important;
    }
</style>
@endsection

@section('content')
<div class="container container-custom mt-4">
    <h2 class="mb-4 font-weight-bold">Detail Simpanan Anggota</h2>

    <h4 class="mb-3 text-dark">Nama Anggota: <span class="font-weight-bold">{{ $anggota->nama }}</span></h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover text-dark">
            <thead class="text-white">
                <tr>
                    <th>No</th>
                    <th>Jenis Simpanan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody class="text-dark">
                @forelse ($anggota->simpanan as $simpanan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td style="text-align: left;">{{ $simpanan->jenis_simpanan }}</td>
                    <td style="text-align: right;">Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($simpanan->tanggal)->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data simpanan untuk anggota ini.</td>
                </tr>
                @endforelse
                <tr>
                    <td colspan="2" class="text-right"><strong>Total Simpanan Keseluruhan:</strong></td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($anggota->simpanan->sum('jumlah'), 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('laporan.simpanan') }}" class="btn btn-green">Kembali</a>
    </div>
</div>
@endsection

@section('scripts')
{{-- Tidak ada skrip JavaScript spesifik yang diperlukan untuk halaman ini saat ini --}}
@endsection