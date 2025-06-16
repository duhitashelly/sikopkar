@extends('layouts.app')
@section('title', 'Simulasi Pinjaman')

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
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control:disabled {
            background-color: #e9ecef;
            color: #6c757d;
        }

        .btn-hitung {
            background-color: #5C4DB1;
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
        
    </style>
@endsection

@section('content')

<div class="form-card">
    <div class="form-header">
        <h2>Simulasi Pinjaman</h2>
    </div>
    <div class="form-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pinjaman.hasil') }}" id="simulasiForm">
            @csrf

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Pinjaman<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
            </div>

            <div class="mb-3">
                <label for="tenor" class="form-label">Tenor (bulan)<span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="tenor" name="tenor" required>
            </div>

            <button type="submit" class="btn btn-hitung">Hitung</button>
            <a href="{{ route('pinjaman.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Format jumlah pinjaman dengan titik sebagai pemisah ribuan
    $('#jumlah').on('keyup', function() {
        var val = $(this).val();
        val = val.replace(/\./g, ''); // Hilangkan titik sebelumnya
        if (!isNaN(val)) {
            $(this).val(val.replace(/\B(?=(\d{3})+(?!\d))/g, '.')); // Menambahkan titik pemisah ribuan
        }
    });

    // Sebelum mengirimkan form, hapus titik agar input valid
    $('#simulasiForm').on('submit', function() {
        var jumlah = $('#jumlah').val();
        // Hapus titik sebelum mengirimkan data ke server
        $('#jumlah').val(jumlah.replace(/\./g, ''));
    });
</script>
@endsection