<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Simpanan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 5px 30px 30px 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
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

    <h3><u>Laporan Data Simpanan</u></h3>

    <table>
        <thead>
            <tr>
                <th>ID Simpanan</th>
                <th>Nama Anggota</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($simpanan as $item)
                <tr>
                    <td>{{ $item->id_simpanan }}</td>
                    <td>{{ $item->anggota->nama }}</td>
                    <td>{{ ucfirst($item->jenis_simpanan) }}</td>
                    <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td>{{ $item->tanggal }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
