@extends('layouts.app')
@section('title', 'Tambah Pinjaman')

@section('styles')
    <style>
        body {
            background-color: #EDECFF !important;
        }

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

        .form-label {
            font-weight: normal; /* Menghilangkan bold pada label */
            margin-bottom: 10px; /* Memberikan jarak di bawah label */
        }

        .form-control:disabled {
            background-color: #e9ecef;
            color: #6c757d;
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
        }

        .btn + .btn {
            margin-left: 10px;
        }

        .text-danger {
            color: red;
        }

        /* Update the alert style to remove the box, background, and close button */
        .alert-error {
            background-color: transparent; /* Remove background */
            padding: 0; /* Remove padding */
            margin-bottom: 15px;
            color: red; /* Red text */
            font-size: 14px; /* Font size */
        }

.select2-container--default .select2-selection--single {
    height: 38px !important;          /* tinggi kotak */
    padding: 6px 12px !important;     /* padding dalam kotak */
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    font-size: 1rem !important;
    line-height: 26px !important;
    box-sizing: border-box !important;
    width: 100% !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px !important;     /* vertikal teks */
    padding-left: 0 !important;       /* agar teks mulai dari kiri kotak */
    color: #495057;                   /* warna teks seperti input biasa */
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
    top: 1px !important;
    right: 12px !important;
}

    </style>

    <!-- Tambah Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')

<div class="form-card">
    <div class="form-header">
        <h2>Tambah Pinjaman</h2>
    </div>
    <div class="form-body">
        <form method="POST" action="{{ route('pinjaman.store') }}" id="pinjamanForm">
            @csrf

            <div class="mb-3">
                <label for="id_pinjaman" class="form-label">Id Pinjaman<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="id_pinjaman" name="id_pinjaman" value="{{ $newId }}" readonly>
            </div>

            <div class="mb-3">
                <label for="id_anggota" class="form-label">Nama Anggota<span class="text-danger">*</span></label>
                <select class="form-control" id="id_anggota" name="id_anggota" required>
                    <option value="" disabled selected>-- Pilih Anggota --</option>
                    @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->id_anggota }}">{{ $anggota->id_anggota }} - {{ $anggota->nama }}</option>
                    @endforeach
                </select>
                <div id="alertError" class="alert-error" style="display: none;">
                    <span>Anggota ini masih memiliki pinjaman yang belum lunas.</span>
                </div>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Pinjaman<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
            </div>

            <div class="mb-3">
                <label for="tenor" class="form-label">Tenor (bulan)<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="tenor" name="tenor" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_pinjaman" class="form-label">Tanggal Pinjaman<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_pinjaman" name="tanggal_pinjaman" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="status" name="status" value="Belum Lunas" readonly>
            </div>

            <button type="submit" class="btn btn-simpan">Simpan</button>
            <a href="{{ route('pinjaman.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Tambah Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#id_anggota').select2({
            placeholder: "-- Pilih Anggota --",
            allowClear: true,
            width: '100%'
        });
    });

    // Saat memilih anggota, lakukan pengecekan status pinjaman
    $('#id_anggota').on('change', function() {
        var idAnggota = $(this).val();
        if (idAnggota) {
            $.ajax({
                url: '/pinjaman/cek-status/' + idAnggota,
                type: 'GET',
                success: function(response) {
                    if (response.status) {
                        $('#alertError').fadeIn();
                    } else {
                        $('#alertError').fadeOut();
                    }
                }
            });
        }
    });

    // Format jumlah pinjaman dengan titik sebagai pemisah ribuan
    $('#jumlah').on('keyup', function() {
        var val = $(this).val();
        val = val.replace(/\./g, '');
        if (!isNaN(val)) {
            $(this).val(val.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
        }
    });

    // Sebelum submit form, hilangkan titik di jumlah agar valid
    $('#pinjamanForm').on('submit', function() {
        var jumlah = $('#jumlah').val();
        $('#jumlah').val(jumlah.replace(/\./g, ''));
    });
</script>
@endsection
