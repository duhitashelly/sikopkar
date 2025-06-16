<table>
    <thead>
        <tr>
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
                <td>{{ $index + 1 }}</td>
                <td>{{ $pinjaman->anggota->nama ?? '-' }}</td>
                <td>Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}</td>
                <td>{{ $pinjaman->tenor }} bulan</td>
                <td>{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d/m/Y') }}</td>
                <td>{{ ucfirst($pinjaman->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>