<!DOCTYPE html>
<html>
<head>
    <title>Laporan Anggota</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 5px 30px 30px 30px; /* dikurangi margin top dari 30px ke 10px */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #eee;
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
            margin-top: -9; /* pastikan tidak ada margin tambahan di gambar */
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

        h3 {
            text-align: center;
            margin-top: 20px;
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

    <h3><u>Laporan Anggota</u></h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kontak</th>
                <th>Status</th>
                <th>Jenis</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($anggota as $a)
                <tr>
                    <td>{{ $a->id_anggota }}</td>
                    <td>{{ $a->nama }}</td>
                    <td>{{ $a->alamat }}</td>
                    <td>{{ $a->kontak }}</td>
                    <td>{{ $a->status }}</td>
                    <td>{{ ucfirst($a->jenis_anggota) }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal_daftar)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
