@extends('layouts.app')
@section('title', 'Data Anggota')

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

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .alert-error {
        background-color: #f8d7da;
        border-radius: 8px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        border: 1px solid #f5c6cb;
    }

    .alert-error span {
        font-weight: bold;
        color: #721c24;
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


    .d-flex.mb-3 > a {
        margin-right: 15px;
    }

    .d-flex.mb-3 > a:last-child {
        margin-right: 0;
    }

    turbo-frame#main-content-frame {
  display: block;
  opacity: 1;
  transition: opacity 0.3s ease;
}

turbo-frame#main-content-frame[turbo-loading] {
  opacity: 0.3;
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
<div class="container">
    @if ($errors->any())
        <div class="alert-error">
            <span>
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </span>
            <button onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert-success">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    <h1><strong>Data Anggota</strong></h1>

    <div class="d-flex mb-3">
        <a href="{{ route('anggota.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
    </div>

    <table id="tabel-pinjaman" class="table table-bordered">
        <thead class="bg-purple text-black">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Alamat</th>
                <th class="text-center">Kontak</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggota as $data)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $data->nama }}</td>
                    <td class="text-center">{{ $data->alamat }}</td>
                    <td class="text-center">{{ $data->kontak }}</td>
                    <td class="text-center">{{ $data->status }}</td>
                    <td class="text-center">
                        <a href="{{ route('anggota.edit', $data->id_anggota) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('anggota.destroy', $data->id_anggota) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Yakin ingin menghapus data ini?')" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
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
