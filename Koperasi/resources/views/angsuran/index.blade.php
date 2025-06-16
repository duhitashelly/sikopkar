@extends('layouts.app')
@section('title', 'Data Angsuran')

@section('styles')
<style>
    body {
        background-color: #EDECFF !important;
    }
    .container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
    }
    h1 { color: black; }
    .table thead {
        background-color: #9288BC;
        color: white;
        border-bottom: 1px solid black;
    }
    .table-bordered, .table-bordered th, .table-bordered td {
        border: 1px solid black;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .table tbody tr, .table tbody td {
        color: black;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #842029;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #f5c2c7;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .alert-error button {
        background: none;
        border: none;
        font-size: 20px;
        color: #842029;
        cursor: pointer;
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
</style>
@endsection

@section('content')
<div class="container py-4">
    @if(session('error'))
        <div class="alert-error">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @endif

    @if(session('success'))
        <div style="background-color: #cce5b6; border-radius: 8px; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border: 1px solid #a5cf91;">
            <span style="font-weight: bold; color: #173c1d;">
                {{ session('success') }}
            </span>
            <button onclick="this.parentElement.style.display='none';" style="background: none; border: none; font-size: 20px; color: #333; cursor: pointer;">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <h1><strong>Data Angsuran</strong></h1>

    <div class="d-flex mb-3">
        <a href="{{ route('angsuran.create') }}" class="btn btn-primary" style="margin-right: 20px;">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
    </div>

    <table id="tabel-angsuran" class="table table-bordered">
        <thead class="bg-purple text-white">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jumlah Angsuran</th>
                <th class="text-center">Tanggal Angsuran</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($angsurans as $angsuran)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $angsuran->anggota->nama ?? '-' }}</td>
                    <td class="text-center">Rp {{ number_format($angsuran->jumlah_angsuran, 0, ',', '.') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($angsuran->tanggal)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <button class="btn btn-success btn-sm btn-download-pdf"
                            data-pinjaman="{{ $angsuran->id_pinjaman }}"
                            data-angsuran="{{ $angsuran->id_angsuran }}">
                            <i class="bi bi-download"></i> Unduh
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const angsuransByPinjaman = @json($angsuransByPinjaman);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
    async function getBase64ImageFromUrl(imageUrl) {
        return new Promise((resolve, reject) => {
            var img = new Image();
            img.setAttribute('crossOrigin', 'anonymous');
            img.onload = function () {
                var canvas = document.createElement("canvas");
                canvas.width = this.width;
                canvas.height = this.height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(this, 0, 0);
                var dataURL = canvas.toDataURL("image/png");
                resolve(dataURL);
            };
            img.onerror = () => reject("Gagal memuat gambar");
            img.src = imageUrl;
        });
    }

    document.querySelectorAll('.btn-download-pdf').forEach(button => {
        button.addEventListener('click', async function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            const idPinjaman = this.getAttribute('data-pinjaman');
            const idAngsuran = this.getAttribute('data-angsuran');

            const dataAngsuran = angsuransByPinjaman[idPinjaman];
            if (!dataAngsuran || dataAngsuran.length === 0) {
                alert('Data angsuran tidak ditemukan.');
                return;
            }

            const angsuranTerpilih = dataAngsuran.find(item => item.id_angsuran == idAngsuran);
            if (!angsuranTerpilih) {
                alert('Data angsuran terpilih tidak ditemukan.');
                return;
            }

            const nama = angsuranTerpilih.anggota?.nama ?? '-';
            const tanggalPinjamanRaw = angsuranTerpilih.pinjaman?.tanggal ?? null;
            const tanggalPinjamanFormat = tanggalPinjamanRaw ? new Date(tanggalPinjamanRaw).toLocaleDateString("id-ID") : '-';
            const jumlahAngsuran = parseInt(angsuranTerpilih.jumlah_angsuran);
            const tenorPinjaman = angsuranTerpilih.pinjaman?.tenor ?? 0;
            const sudahBayar = dataAngsuran.length;
            const sisaTenor = Math.max(tenorPinjaman - sudahBayar, 0);

            try {
                const logoUrl = window.location.origin + '/img/logo.png';
                const logoBase64 = await getBase64ImageFromUrl(logoUrl);

                doc.addImage(logoBase64, 'PNG', 14, 10, 20, 20);
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.text("RSUD GAMBIRAN KOTA KEDIRI", 40, 15);
                doc.setFontSize(11);
                doc.setFont('helvetica', 'normal');
                doc.text("Jl. Letjen Soeprapto No.99, Mojoroto, Kota Kediri, Jawa Timur 64121", 40, 22);
                doc.text("Telp. (0354) 1234567 | Email: info@rsudgambiran.go.id", 40, 29);
                doc.line(14, 40, 196, 40);

                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text("Detail Angsuran", 14, 50);

                doc.setFontSize(12);
                let startY = 58;
                const labelX = 14;
                const valueX = 60;
                const lineHeight = 7;

                doc.setFont('helvetica', 'bold');
                doc.text("Nama", labelX, startY);
                doc.setFont('helvetica', 'normal');
                doc.text(`: ${nama}`, valueX, startY);

                doc.setFont('helvetica', 'bold');
                doc.text("Jumlah Angsuran", labelX, startY + lineHeight);
                doc.setFont('helvetica', 'normal');
                doc.text(`: Rp ${jumlahAngsuran.toLocaleString('id-ID')}`, valueX, startY + lineHeight);

                doc.setFont('helvetica', 'bold');
                doc.text("Tanggal Angsuran", labelX, startY + lineHeight * 2);
                doc.setFont('helvetica', 'normal');
                const tanggalAngsuranFormat = angsuranTerpilih.tanggal ? new Date(angsuranTerpilih.tanggal).toLocaleDateString("id-ID") : '-';
                doc.text(`: ${tanggalAngsuranFormat}`, valueX, startY + lineHeight * 2);

                doc.setFont('helvetica', 'bold');
                doc.text("Sisa Tenor", labelX, startY + lineHeight * 3);
                doc.setFont('helvetica', 'normal');
                doc.text(`: ${sisaTenor} bulan`, valueX, startY + lineHeight * 3);

                const tableData = dataAngsuran.map((item, index) => [
                    index + 1,
                    item.anggota?.nama ?? '-',
                    "Rp " + parseInt(item.jumlah_angsuran).toLocaleString("id-ID"),
                    new Date(item.tanggal).toLocaleDateString("id-ID")
                ]);

                doc.autoTable({
                    startY: startY + lineHeight * 5,
                    head: [["No", "Nama", "Jumlah Angsuran", "Tanggal Angsuran"]],
                    body: tableData,
                    styles: {
                        fontSize: 12,
                        lineColor: [0, 0, 0],  // warna garis hitam untuk body tabel
                        lineWidth: 0.3,
                    },
                    headStyles: {
                        fillColor: [220, 220, 220], // abu-abu muda untuk header
                        textColor: 0,               // tulisan hitam header
                        lineColor: [0, 0, 0],       // garis hitam header
                        lineWidth: 0.3,
                    },
                    alternateRowStyles: {
                        fillColor: [255, 255, 255], // putih untuk baris ganjil/genap (tidak ada shading)
                    },
                });


                const finalY = doc.lastAutoTable.finalY || (startY + lineHeight * 7);
                const tanggalUnduh = new Date();
                const tanggalFormat = ("0" + tanggalUnduh.getDate()).slice(-2) + '/' +
                                    ("0" + (tanggalUnduh.getMonth() + 1)).slice(-2) + '/' +
                                    tanggalUnduh.getFullYear();

                const pageWidth = doc.internal.pageSize.getWidth();
                doc.setFontSize(12);
                doc.setFont('helvetica', 'normal');

                const text1 = `Kediri, ${tanggalFormat}`;
                const text1Width = doc.getTextWidth(text1);
                doc.text(text1, pageWidth - text1Width - 14, finalY + 15);

                const text2 = "Admin Koperasi";
                const text2Width = doc.getTextWidth(text2);
                doc.text(text2, pageWidth - text2Width - 14, finalY + 40);

                const namaFile = nama.replace(/\s+/g, '_') || 'anggota';
                doc.save(`angsuran_${namaFile}.pdf`);

            } catch (error) {
                alert("Gagal membuat PDF: " + error);
            }
        });
    });
</script>
@endsection
