@extends('layouts.app')
@section('title', 'Data Simpanan')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    body {
        background-color: #EDECFF !important;
    }

    .container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
    }

    h1 {
        color: black;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th{
        text-align: center !important;
        vertical-align: middle !important;
    }
    .table td {
        border: 1px solid #ccc; /* border tipis & seragam */
        padding: 10px;
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 14px;
        color: black;
        white-space: normal;
        word-break: break-word;
        
    }
    

    .table thead th {
        background-color: #9288BC;
        color: white;
        border: 1px solid #ccc;
        text-align: center !important;      /* pastikan ini */
        vertical-align: middle !important;  /* pastikan ini */
    }

    .table th:nth-child(1),
    .table td:nth-child(1) {
        min-width: 60px !important;
        width: 60px;
    }
        

    .table th,
    .table td {
        min-width: 100px;
    }

    .d-flex.mb-3 > a {
        margin-right: 15px;
    }

    .d-flex.mb-3 > a:last-child {
        margin-right: 0;
    }

    /* Styling untuk halaman aktif paginasi DataTables (warna sedikit lebih gelap) */
    .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: #e0e0e0 !important; /* Warna abu-abu medium */
        border-color: #c0c0c0 !important;     /* Border abu-abu yang lebih gelap */
        color: #4a4a4a !important;            /* Warna teks abu-abu yang lebih gelap */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Tambahkan sedikit bayangan */
    }

    /* Styling saat di-hover untuk tombol aktif (jika diperlukan) */
    .dataTables_wrapper .pagination .page-item.active .page-link:hover {
        background-color: #d0d0d0 !important; /* Sedikit lebih gelap saat di-hover */
        border-color: #b0b0b0 !important;
        color: #3a3a3a !important;
    }

    /* Opsional: Warna untuk tombol non-aktif saat di-hover */
    .dataTables_wrapper .pagination .page-item:not(.active) .page-link:hover {
        background-color: #f0f0f0 !important; /* Latar belakang abu-abu terang saat di-hover */
        color: #333 !important; /* Warna teks lebih gelap */
    }

    turbo-frame#main-content-frame {
  display: block;
  opacity: 1;
  transition: opacity 0.3s ease;
}

turbo-frame#main-content-frame[turbo-loading] {
  opacity: 0.3;
}
</style>
@endsection

@section('content')
<div class="container py-4">
    @if(session('error'))
        <div style="background-color: #f8d7da; border-radius: 8px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border: 1px solid #f5c6cb;">
            <span style="font-weight: bold; color: #721c24;">
                {{ session('error') }}
            </span>
            <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 20px; color: #333; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if(session('success'))
        <div style="background-color: #cce5b6; border-radius: 8px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border: 1px solid #a5cf91;">
            <span style="font-weight: bold; color: #173c1d;">
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 20px; color: #333; cursor: pointer;">&times;</button>
        </div>
    @endif

    <h1><strong>Data Simpanan</strong></h1>

    <div class="d-flex mb-3">
        <a href="{{ route('simpanan.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Tambah
        </a>
    </div>

    <table id="tabel-simpanan" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis Simpanan</th>
                <th>Jenis Anggota</th>
                <th>Jumlah Simpanan</th>
                <th>Tanggal</th>
                {{-- <th>Aksi</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($dataSimpanan as $index => $simpanan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $simpanan->anggota->nama ?? '-' }}</td>
                    <td>{{ ucfirst($simpanan->jenis_simpanan) }}</td>
                    <td>{{ $simpanan->anggota->jenis_anggota ?? '-' }}</td>
                    <td>Rp. {{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
                    <td>{{ \Carbon\Carbon::parse($simpanan->tanggal)->format('d/m/Y') }}</td>
                    {{-- <td>
                        <a href="{{ route('simpanan.edit', $simpanan->id_simpanan) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('simpanan.destroy', $simpanan->id_simpanan) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
