<table>
    <thead>
        <tr style="background-color:#9288BC; color:#fff; text-align:center;">
            <th>No</th>
            <th>Nama</th>
            <th>Jumlah Pinjaman</th>
            <th>Tenor</th>
            <th>Tanggal Pinjaman</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pinjamans as $index => $pinjaman)
            <tr>
                <td style="text-align:center;">{{ $index + 1 }}</td>
                <td>{{ $pinjaman->anggota->nama ?? '-' }}</td>
                <td>Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}</td>
                <td style="text-align:center;">{{ $pinjaman->tenor }} bulan</td>
                <td style="text-align:center;">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d/m/Y') }}</td>
                <td style="text-align:center;">{{ ucfirst($pinjaman->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
