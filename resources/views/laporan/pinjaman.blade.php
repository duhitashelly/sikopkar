@extends('layouts.app')

@section('title', 'Laporan Pinjaman')

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
    text-align: center; /* Added this line to center the text */
    vertical-align: middle; /* Ensures vertical alignment in case of multi-line headers */
  }

  table tbody td:first-child { /* New rule to center the 'No' column */
    text-align: center;
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

  h2, .text-dark {
    color: black !important;
  }
  table tbody td {
    color: black; /* warna teks isi tabel jadi hitam */
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
  <h2 class="mb-4 font-weight-bold">Laporan Pinjaman</h2>

  <form action="{{ route('laporan.pinjaman') }}" method="GET" class="mb-3" id="filterForm">
    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
      <input type="date" name="tanggal_dari" id="tanggal_dari" value="{{ request('tanggal_dari') }}"
        class="form-control"
        placeholder="Dari"
        style="width: 170px; padding: 8px 12px; font-size: 16px;">

      <input type="date" name="tanggal_sampai" id="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
        class="form-control"
        placeholder="Sampai"
        style="width: 170px; padding: 8px 12px; font-size: 16px;">

      {{-- Tombol Tampilkan dengan lebar seragam --}}
      <button type="submit" class="btn btn-purple d-flex align-items-center justify-content-center"
        style="height: 38px; width: 170px; font-size: 16px;">Tampilkan</button> {{-- Changed to submit button with "Tampilkan" --}}

    </div>
  </form>

  <div class="mt-5 mb-3">
    <a href="{{ route('laporan.pinjaman.pdf', request()->query()) }}" target="_blank" class="btn btn-purple mr-2">
      <i class="bi bi-upload"></i> Export ke PDF
    </a>

    <a href="{{ route('laporan.pinjaman.excel', request()->query()) }}" class="btn btn-purple">
      <i class="bi bi-file-earmark-spreadsheet-fill"></i> Export ke Excel
    </a>
  </div>

  <div class="table-responsive">
    <table id="laporan-pinjaman" class="table table-bordered table-hover text-sm">
      <thead>
        <tr>
          <th style="text-align: center;">No</th>
          <th style="text-align: center;">ID Pinjaman</th>
          <th style="text-align: center;">Nama</th>
          <th style="text-align: center;">Jumlah Pinjaman</th>
          <th style="text-align: center;">Tenor</th>
          <th style="text-align: center;">Tanggal Pinjaman</th>
          <th style="text-align: center;">Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($pinjamans as $pinjaman)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $pinjaman->id_pinjaman }}
            <td>{{ $pinjaman->anggota->nama ?? '-' }}</td>
            <td>Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}</td>
            <td>{{ $pinjaman->tenor }} bulan</td>
            <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d/m/Y') }}</td>
            <td>{{ ucfirst($pinjaman->status) }}</td>
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
    // Remove event listeners for automatic submission if you want a dedicated "Tampilkan" button
    // const namaInput = document.getElementById('nama');
    // const statusSelect = document.getElementById('status');
    // const tanggalDari = document.getElementById('tanggal_dari');
    // const tanggalSampai = document.getElementById('tanggal_sampai');

    // function submitForm() {
    //   filterForm.submit();
    // }

    // namaInput.addEventListener('input', submitForm);
    // statusSelect.addEventListener('change', submitForm);
    // tanggalDari.addEventListener('change', submitForm);
    // tanggalSampai.addEventListener('change', submitForm);
  });
</script>
@endsection