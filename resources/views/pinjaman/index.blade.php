@extends('layouts.app')
@section('title', 'Data Pinjaman')

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

    h1 {
        color: black;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th{
        text-align: center !important;
        vertical-align: middle !important;
    }
    .table td {
        border: 1px solid #ccc; /* border tipis & seragam */
        padding: 10px;
        text-align: center !important;
        vertical-align: middle !important;
        font-size: 14px;
        color: black;
        white-space: normal;
        word-break: break-word;
        
    }
    

    .table thead th {
        background-color: #9288BC;
        color: white;
        border: 1px solid #ccc;
        text-align: center !important;      /* pastikan ini */
        vertical-align: middle !important;  /* pastikan ini */
    }

    .table th:nth-child(1),
    .table td:nth-child(1) {
        min-width: 60px !important;
        width: 60px;
    }
        

    .table th,
    .table td {
        min-width: 100px;
    }

    .d-flex.mb-3 > a {
        margin-right: 15px;
    }

    .d-flex.mb-3 > a:last-child {
        margin-right: 0;
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

turbo-frame#main-content-frame {
  display: block;
  opacity: 1;
  transition: opacity 0.3s ease;
}

turbo-frame#main-content-frame[turbo-loading] {
  opacity: 0.3;
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

    <h1><strong>Data Pinjaman</strong></h1>

    <div class="d-flex mb-3">
        <a href="{{ route('pinjaman.create') }}" class="btn btn-primary me-2">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
        <a href="{{ route('pinjaman.simulasi') }}" class="btn" style="background-color: #5C4DB1; color: white;">
            <i class="bi bi-calculator"></i> Simulasi Pinjaman
        </a>
    </div>

    <table id="tabel-pinjaman" class="table table-bordered">
        <thead class="bg-purple text-white">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jumlah Pinjaman</th>
                <th class="text-center">Tenor</th>
                <th class="text-center">Tanggal Pinjaman</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pinjamans as $pinjaman)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $pinjaman->anggota->nama ?? '-' }}</td>
                    <td class="text-center">Rp {{ number_format($pinjaman->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $pinjaman->tenor }} bulan</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $pinjaman->status }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success btn-sm download-pinjaman" data-id="{{ $pinjaman->id_pinjaman }}">
                            <i class="bi bi-download"></i> Unduh
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    const { jsPDF } = window.jspdf;

    function convertToWords(n) {
        const satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan"];
        const belasan = ["Sepuluh", "Sebelas", "Dua Belas", "Tiga Belas", "Empat Belas", "Lima Belas", "Enam Belas", "Tujuh Belas", "Delapan Belas", "Sembilan Belas"];
        const puluhan = ["", "", "Dua Puluh", "Tiga Puluh", "Empat Puluh", "Lima Puluh", "Enam Puluh"];

        if (n < 10) return satuan[n];
        if (n >= 10 && n < 20) return belasan[n - 10];
        if (n < 100) {
            const puluh = Math.floor(n / 10);
            const sisa = n % 10;
            return puluhan[puluh] + (sisa !== 0 ? " " + satuan[sisa] : "");
        }
        return "Lebih dari Sembilan Puluh";
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".download-pinjaman").forEach(button => {
            button.addEventListener("click", function () {
                const pinjamanId = button.getAttribute("data-id");

                fetch(`/pinjaman/${pinjamanId}`)
                    .then(response => response.json())
                    .then(data => {
                        const doc = new jsPDF();
                        const totalPinjaman = Number(data.jumlah ?? 0);
                        const tenor = Number(data.tenor ?? 0);

                        if (!totalPinjaman || !tenor) {
                            alert("Data pinjaman tidak valid.");
                            return;
                        }

                        // Fungsi untuk menggambar kop surat
                        function drawKop() {
                            return new Promise((resolve) => {
                                const img = new Image();
                                img.src = '/img/logo.png';  // Ganti sesuai path logo kamu
                                img.onload = () => {
                                    doc.addImage(img, 'PNG', 14, 10, 20, 20);

                                    doc.setFontSize(12);
                                    doc.setFont('Helvetica', 'bold');
                                    doc.text("RSUD GAMBIRAN KOTA KEDIRI", 40, 15);

                                    doc.setFontSize(11);
                                    doc.setFont('Helvetica', 'normal');
                                    doc.text("Jl. Letjen Soeprapto No.99, Mojoroto, Kota Kediri, Jawa Timur 64121", 40, 22);
                                    doc.text("Email: info@rsudgambiran.go.id", 40, 29);

                                    doc.setLineWidth(0.5);
                                    doc.line(14, 38, 196, 38);

                                    resolve();
                                };
                                img.onerror = () => {
                                    // Kalau gagal load logo, tetap lanjut tanpa logo
                                    doc.setFontSize(12);
                                    doc.setFont('Helvetica', 'bold');
                                    doc.text("RSUD GAMBIRAN KOTA KEDIRI", 14, 20);
                                    doc.setFontSize(11);
                                    doc.setFont('Helvetica', 'normal');
                                    doc.text("Jl. Letjen Soeprapto No.99, Mojoroto, Kota Kediri, Jawa Timur 64121", 14, 27);
                                    doc.text("Email: info@rsudgambiran.go.id", 14, 34);
                                    doc.setLineWidth(0.5);
                                    doc.line(14, 40, 196, 40);
                                    resolve();
                                };
                            });
                        }

                        drawKop().then(() => {
                            let y = 50;

                            doc.setFontSize(18);
                            doc.setFont('Helvetica', 'bold');
                            doc.text("BUKTI PINJAMAN", 105, y, { align: 'center' });

                            y += 12;
                            doc.setFontSize(12);
                            doc.setFont('Helvetica', 'normal');
                            doc.text('Koperasi RSUD Gambiran Kota Kediri', 105, y, { align: 'center' });
                            y += 10;

                            doc.setFont('Helvetica', 'bold');
                            doc.text('Nama Anggota:', 20, y);
                            doc.setFont('Helvetica', 'normal');
                            doc.text(`${data.anggota?.nama ?? '-'}`, 70, y);

                            doc.setFont('Helvetica', 'bold');
                            doc.text('Tanggal Pinjaman:', 20, y + 8);
                            doc.setFont('Helvetica', 'normal');
                            doc.text(`${data.tanggal_pinjaman ?? '-'}`, 70, y + 8);

                            doc.setFont('Helvetica', 'bold');
                            doc.text('Tenor:', 20, y + 16);
                            doc.setFont('Helvetica', 'normal');
                            doc.text(`${tenor} bulan`, 70, y + 16);

                            doc.setFont('Helvetica', 'bold');
                            doc.text('Jumlah Pinjaman:', 20, y + 24);
                            doc.setFont('Helvetica', 'normal');
                            doc.text(`Rp ${totalPinjaman.toLocaleString('id-ID')}`, 70, y + 24);

                            doc.setFont('Helvetica', 'bold');
                            doc.text('Status:', 20, y + 32);
                            doc.setFont('Helvetica', 'normal');
                            doc.text(`${data.status ?? '-'}`, 70, y + 32);

                            y += 45;
                            doc.setFont('Helvetica', 'bold');
                            doc.text("Tabel Rincian Angsuran", 105, y - 5, { align: 'center' });

                            const headers = ["No", "Bulan", "Angsuran", "Bunga"];
                            const colWidths = [15, 50, 60, 50];
                            const startX = 20;
                            let currentY = y;

                            const pageHeight = doc.internal.pageSize.height;
                            const rowHeight = 10;

                            function drawTableHeader() {
                                doc.setFont('Helvetica', 'bold');
                                headers.forEach((h, i) => {
                                    const x = startX + colWidths.slice(0, i).reduce((a, b) => a + b, 0);
                                    doc.rect(x, currentY, colWidths[i], rowHeight);
                                    doc.text(h, x + colWidths[i] / 2, currentY + 7, { align: 'center' });
                                });
                                currentY += rowHeight;
                                doc.setFont('Helvetica', 'normal');
                            }

                            drawTableHeader();

                            let sisaPokok = totalPinjaman;

                            for (let i = 0; i < tenor; i++) {
                                if (currentY + rowHeight > pageHeight - 20) {
                                    doc.addPage();
                                    currentY = 20;
                                    drawTableHeader();
                                }

                                const bungaRate = 0.0125;
                                const bunga = sisaPokok * bungaRate;
                                const pokokPerBulan = totalPinjaman / tenor;
                                const angsuran = pokokPerBulan + bunga;
                                sisaPokok -= pokokPerBulan;

                                const bulanText = convertToWords(i + 1);
                                const row = [
                                    `${i + 1}`,
                                    bulanText,
                                    `Rp ${Math.round(angsuran).toLocaleString('id-ID')}`,
                                    `Rp ${Math.round(bunga).toLocaleString('id-ID')}`
                                ];

                                row.forEach((cell, j) => {
                                    const x = startX + colWidths.slice(0, j).reduce((a, b) => a + b, 0);
                                    doc.rect(x, currentY, colWidths[j], rowHeight);
                                    doc.text(cell, x + colWidths[j] / 2, currentY + 7, { align: 'center' });
                                });

                                currentY += rowHeight;
                            }

                            if (currentY + 30 > pageHeight) {
                                doc.addPage();
                                currentY = 20;
                            }

                            const currentDate = new Date().toLocaleDateString('id-ID');
                            doc.text(`Kediri, ${currentDate}`, 180, currentY + 15, { align: 'right' });
                            doc.text('Admin Koperasi', 180, currentY + 30, { align: 'right' });

                            doc.save(`Pinjaman_${data.anggota?.nama ?? 'unknown'}.pdf`);
                        });
                    })
                    .catch(error => {
                        console.error("Gagal mengambil data pinjaman:", error);
                        alert("Gagal mengambil data pinjaman.");
                    });
            });
        });
    });
</script>

@endsection
