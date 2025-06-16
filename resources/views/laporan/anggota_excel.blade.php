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
                <td>{{ $a->alamat ?? '-' }}</td>
                <td>{{ $a->kontak ?? '-' }}</td>
                <td>{{ $a->status ?? '-' }}</td>
                <td>{{ ucfirst($a->jenis_anggota) }}</td>
                <td>{{ $a->tanggal_daftar ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
