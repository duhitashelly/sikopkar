@extends('layouts.app')

@section('title', 'Laporan Anggota')

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
    text-align: center; /* Center header text */
    vertical-align: middle; /* Ensures vertical alignment */
  }

  table tbody td:first-child { /* Center content of the 'No' column */
    text-align: center;
  }
  table tbody td {
    color: black; /* Warna isi tabel */
  }

  .btn-purple {
    background-color: #6f42c1;
    border-color: #6f42c1;
    color: white;
  }

  .btn-purple:hover {
    background-color: #5936a7;
    border-color: #5936a7;
    color: white;
  }

  h2 { /* Ensured h2 also has black color for consistency */
    color: black !important;
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

/* Mengatur warna font untuk pesan "Data tidak tersedia" pada DataTables */
.dataTables_empty {
    color: #6c757d !important; /* Warna abu-abu yang umum digunakan */
    font-weight: normal !important; /* Pastikan tidak bold */
}
</style>
@endsection

@section('content')
<div class="container container-custom mt-4">
  <h2 class="mb-4 font-weight-bold">Laporan Anggota</h2>

  <form action="{{ route('laporan.anggota') }}" method="GET" class="mb-4" id="filterForm">
    <div class="form-row">
      <div class="form-group col-md-2">
        <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}" class="form-control" placeholder="Dari">
      </div>
      <div class="form-group col-md-2">
        <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="form-control" placeholder="Sampai">
      </div>
      <div class="form-group col-md-2 text-right">
        <button type="submit" class="btn btn-purple btn-block">Tampilkan</button> {{-- Changed to btn-purple --}}
      </div>
    </div>
  </form>


  <div class="mb-3">
    <a href="{{ route('laporan.anggota.pdf', request()->query()) }}" target="_blank" class="btn btn-purple mr-2">
      <i class="bi bi-upload"></i> Export ke PDF
    </a>

    <a href="{{ route('laporan.anggota.excel', request()->query()) }}" class="btn btn-purple">
      <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export ke Excel
    </a>
  </div>

  <div class="table-responsive">
    <table id="laporan-anggota" class="table table-bordered table-hover text-sm">
      <thead>
        <tr>
          <th style="text-align: center;">No</th> {{-- Changed from ID to No --}}
          <th style="text-align: center;">Nama</th>
          <th style="text-align: center;">Alamat</th>
          <th style="text-align: center;">Kontak</th>
          <th style="text-align: center;">Status</th>
          <th style="text-align: center;">Jenis</th>
          <th style="text-align: center;">Tanggal Daftar</th>
        </tr>
      </thead>
        <tbody>
    @forelse ($anggota as $a)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $a->nama }}</td>
            <td>{{ $a->alamat ?? '-' }}</td>
            <td>{{ $a->kontak ?? '-' }}</td>
            <td>{{ $a->status ?? '-' }}</td>
            <td>{{ ucfirst($a->jenis_anggota) }}</td>
            <td>{{ \Carbon\Carbon::parse($a->tanggal_daftar)->format('d/m/Y') }}</td>
        </tr>
    @empty
        {{-- <tr>
            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
        </tr> --}}
    @endforelse
</tbody>
</table>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterForm = document.getElementById('filterForm');
  });
</script>
@endsection