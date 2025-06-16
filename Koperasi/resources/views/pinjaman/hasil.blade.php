@extends('layouts.app')

@section('title', 'Hasil Simulasi Pinjaman')

@section('styles')
    <style>
        body {
            background-color: #EDECFF !important;
        }

        .form-card {
            max-width: 2900px;
            margin: 10px auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            padding: 32px;
        }

        h1 {
            font-size: 20px;
            font-weight: 600;
            color: #5D3FD3;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .table th, .table td {
            padding: 14px 12px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 14px;
        }

        .table th {
            background-color:#9288BC;
            color: white;
        }

        .btn-kembali {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-kembali:hover {
            background-color: #218838;
        }

        .form-body p {
            text-align: center;
            font-size: 16px;
            color: #555;
        }
    </style>
@endsection

@section('content')

<div class="form-card">
    <h1><strong>Simulasi Pinjaman</strong></h1>

    <div class="form-body">
        @if(isset($angsuran))
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bulan Ke-</th>
                        <th>Angsuran</th>
                        <th>Bunga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($angsuran as $index => $angsuran_bulan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $bulanKe[$index] }}</td>
                            <td>Rp {{ number_format($angsuran_bulan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($bunga[$index], 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Data simulasi pinjaman belum tersedia. Silakan lakukan perhitungan terlebih dahulu.</p>
        @endif

        <div style="text-align: right;">
            <a href="{{ route('pinjaman.index') }}" class="btn btn-kembali">Kembali</a>
        </div>
    </div>
</div>

@endsection
