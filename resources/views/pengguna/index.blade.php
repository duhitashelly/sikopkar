@extends('layouts.app')
@section('title', 'Data Pengguna')

@section('styles')
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

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .alert-success {
        background-color: #cce5b6;
        border-radius: 8px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border: 1px solid #a5cf91;
    }

    .alert-success span {
        font-weight: bold;
        color: #173c1d;
    }

    .alert-success button {
        background: none;
        border: none;
        font-size: 20px;
        color: #333;
        cursor: pointer;
    }

    .btn {
        margin-right: 5px;
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
</style>
@endsection

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert-success">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    <h1><strong>Data Pengguna</strong></h1>

    <a href="{{ route('pengguna.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah
    </a>

    <table class="table table-bordered" id="tabel-pengguna">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Username</th>
                <th class="text-center">Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($penggunas as $index => $pengguna)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $pengguna->name }}</td>
                    <td class="text-center">{{ $pengguna->role }}</td>
                    <td class="text-center">
                        <a href="{{ route('pengguna.edit', $pengguna->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('pengguna.destroy', $pengguna->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin hapus pengguna ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    setTimeout(function () {
        let alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(function(alert) {
            alert.remove();
        });
    }, 3000);
</script>
@endsection
