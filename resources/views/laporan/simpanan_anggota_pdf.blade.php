<!DOCTYPE html>
<html>
<head>
    <title>Laporan Detail Simpanan Anggota - {{ $anggota->nama }}</title>
    <style>
        h3 {
            text-align: center;
            margin-top: 20px;
        }

        .kop {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: top;
        }
        .kop-logo img {
            width: 70px;
            height: auto;
            margin-top: -12px;
        }
        .kop-text {
            display: table-cell;
            padding-left: 15px;
            vertical-align: top;
        }
        .kop-text h2 {
            margin: -6px;
            font-size: 18px;
            text-transform: uppercase;
        }
        .kop-text p {
            margin: 7px -6px;
            font-size: 12px;
        }

        hr {
            border: none;
            border-top: 2px solid #000;
            margin: 10px 0 20px 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 1mm;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        h4 {
            margin-bottom: 15px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #9288BC;
            color: white;
            font-weight: bold;
        }
        table td:nth-child(2) { /* Jenis Simpanan */
            text-align: left;
        }
        table td:nth-child(3) { /* Jumlah */
            text-align: right;
        }
        .text-right {
            text-align: right;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-logo">
            <img src="{{ public_path('img/logo.png') }}" alt="Logo RSUD">
        </div>
        <div class="kop-text">
            <h2>RSUD GAMBIRAN KOTA KEDIRI</h2>
            <p>Jl. Kapten Piere Tendean No.16, Kota Kediri, Jawa Timur 64121</p>
            <p>Telp. (0354) 2810000 | Email: rsud.gambiran@kedirikota.go.id </p>
        </div>
    </div>
    <hr>

    <h3><u>Laporan Data Simpanan per Anggota</u></h3>

    <h4>Nama Anggota: <span class="font-weight-bold">{{ $anggota->nama }}</span></h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Simpanan</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anggota->simpanan as $simpanan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $simpanan->jenis_simpanan }}</td>
                <td>Rp {{ number_format($simpanan->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($simpanan->tanggal)->format('d-m-Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Tidak ada data simpanan untuk anggota ini.</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="2" class="text-right font-weight-bold">Total Simpanan Keseluruhan:</td>
                <td class="text-right font-weight-bold">Rp {{ number_format($anggota->simpanan->sum('jumlah'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>