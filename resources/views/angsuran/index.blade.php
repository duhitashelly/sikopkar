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

    h1 {
        color: black;
    }

    h5 {
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

    turbo-frame#main-content-frame {
    display: block;
    opacity: 1;
    transition: opacity 0.3s ease;
    }

    turbo-frame#main-content-frame[turbo-loading] {
    opacity: 0.3;
    }

    /* Styling untuk halaman aktif paginasi DataTables (warna sedikit lebih gelap) */
    .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: #e0e0e0 !important;
        border-color: #c0c0c0 !important;
        color: #4a4a4a !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Styling saat di-hover untuk tombol aktif (jika diperlukan) */
    .dataTables_wrapper .pagination .page-item.active .page-link:hover {
        background-color: #d0d0d0 !important;
        border-color: #b0b0b0 !important;
        color: #3a3a3a !important;
    }

    /* Opsional: Warna untuk tombol non-aktif saat di-hover */
    .dataTables_wrapper .pagination .page-item:not(.active) .page-link:hover {
        background-color: #f0f0f0 !important;
        color: #333 !important;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 8px;
        position: relative;
    }
    .close-button {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close-button:hover,
    .close-button:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
                <th class="text-center">Nama Anggota</th>
                <th class="text-center">ID Pinjaman</th>
                <th class="text-center">Total Angsuran Dibayar</th>
                <th class="text-center">Tenor (Sudah Dibayar/Total)</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Iterasi berdasarkan data pinjaman yang sudah dikelompokkan --}}
            @foreach ($angsuransByPinjaman as $pinjamanData)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $pinjamanData['anggota_nama'] }}</td>
                    <td class="text-center">{{ $pinjamanData['id_pinjaman'] }}</td>
                    <td class="text-center">Rp {{ number_format($pinjamanData['total_jumlah_angsuran'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $pinjamanData['jumlah_angsuran_terbayar'] }} / {{ $pinjamanData['tenor'] }} bulan</td>
                    <td class="text-center">
                        {{-- Tombol Detail untuk menampilkan modal --}}
                        <button class="btn btn-info btn-sm btn-detail"
                            data-id-pinjaman="{{ $pinjamanData['id_pinjaman'] }}">
                            <i class="bi bi-info-circle"></i> Detail
                        </button>
                        {{-- Tombol Unduh PDF --}}
                        <button class="btn btn-success btn-sm btn-download-pdf"
                            data-pinjaman="{{ $pinjamanData['id_pinjaman'] }}"
                            data-anggota="{{ $pinjamanData['anggota_nama'] }}">
                            <i class="bi bi-download"></i> Unduh
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="detailAngsuranModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <h5>Detail Angsuran untuk <span id="modalAnggotaNama"></span> (Pinjaman: <span id="modalPinjamanId"></span>)</h5>
        <table class="table table-bordered mt-3" id="modalAngsuranTable">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">ID Angsuran</th>
                    <th class="text-center">Jumlah Angsuran</th>
                    <th class="text-center">Tanggal Angsuran</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Data angsuran yang sudah dikelompokkan, diambil dari PHP
    const angsuransGrouped = @json($angsuransByPinjaman);
    // Semua angsuran (digunakan oleh fungsi PDF yang ada sebelumnya)
    const allAngsurans = @json($angsurans->keyBy('id_angsuran')); 

    // Ambil elemen modal
    var modal = document.getElementById("detailAngsuranModal");
    // Ambil elemen tombol close (x)
    var span = document.getElementsByClassName("close-button")[0];

    // Ketika pengguna mengklik <span> (x), tutup modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Ketika pengguna mengklik di luar area modal, tutup modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Tangani klik tombol "Detail"
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function () {
            const idPinjaman = this.getAttribute('data-id-pinjaman');
            const pinjamanData = angsuransGrouped[idPinjaman]; // Ambil data spesifik untuk pinjaman ini

            if (pinjamanData && pinjamanData.details) {
                document.getElementById('modalPinjamanId').innerText = pinjamanData.id_pinjaman;
                document.getElementById('modalAnggotaNama').innerText = pinjamanData.anggota_nama; 

                const modalTableBody = document.getElementById('modalAngsuranTable').getElementsByTagName('tbody')[0];
                modalTableBody.innerHTML = ''; // Hapus data sebelumnya di tabel modal

                // Isi tabel modal dengan detail angsuran
                pinjamanData.details.forEach((angsuran, index) => {
                    const row = modalTableBody.insertRow();
                    row.insertCell(0).innerText = index + 1;
                    row.insertCell(1).innerText = angsuran.id_angsuran;
                    row.insertCell(2).innerText = `Rp ${parseInt(angsuran.jumlah_angsuran).toLocaleString('id-ID')}`;
                    row.insertCell(3).innerText = new Date(angsuran.tanggal).toLocaleDateString("id-ID");
                });
                modal.style.display = "block"; // Tampilkan modal
            } else {
                alert('Detail angsuran tidak ditemukan.');
            }
        });
    });
</script>

{{-- Script untuk jsPDF dan jspdf-autotable --}}
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
            const anggotaNama = this.getAttribute('data-anggota'); 

            const dataPinjamanGrouped = angsuransGrouped[idPinjaman];
            if (!dataPinjamanGrouped || dataPinjamanGrouped.details.length === 0) {
                alert('Data angsuran tidak ditemukan untuk pinjaman ini.');
                return;
            }

            const dataAngsuranForPdf = dataPinjamanGrouped.details;
            const nama = dataPinjamanGrouped.anggota_nama;
            const totalJumlahAngsuran = dataPinjamanGrouped.total_jumlah_angsuran;
            const tenorPinjaman = dataPinjamanGrouped.tenor;
            const sudahBayar = dataPinjamanGrouped.jumlah_angsuran_terbayar;
            const sisaTenor = Math.max(tenorPinjaman - sudahBayar, 0);
            
            try {
                const logoUrl = window.location.origin + '/img/logo.png'; // Pastikan path logo benar
                const logoBase64 = await getBase64ImageFromUrl(logoUrl);

                // --- Header (Logo dan Informasi Perusahaan) ---
                doc.addImage(logoBase64, 'PNG', 14, 10, 20, 20); // Posisi logo
                doc.setFontSize(12);
                doc.setFont('helvetica', 'bold');
                doc.text("RSUD GAMBIRAN KOTA KEDIRI", 40, 15);
                doc.setFontSize(11);
                doc.setFont('helvetica', 'normal');
                doc.text("Jl. Letjen Soeprapto No.99, Mojoroto, Kota Kediri, Jawa Timur 64121", 40, 22);
                doc.text("Telp. (0354) 1234567 | Email: info@rsudgambiran.go.id", 40, 29);
                doc.line(14, 35, 196, 35); // Garis pemisah, sesuaikan Y jika perlu

                // --- Judul Dokumen ---
                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text("Detail Angsuran Pinjaman", 105, 45, null, null, 'center'); // Pusatkan judul

                // --- Detail Pinjaman ---
                doc.setFontSize(12);
                let startYDetails = 55; // Posisi Y awal untuk detail pinjaman
                const labelX = 20; // Posisi X untuk label
                const colonX = 75; // Posisi X untuk titik dua
                const valueX = 78; // Posisi X untuk nilai
                const detailLineHeight = 7;

                doc.setFont('helvetica', 'bold');
                doc.text("Nama Anggota", labelX, startYDetails);
                doc.text(":", colonX, startYDetails);
                doc.setFont('helvetica', 'normal');
                doc.text(nama, valueX, startYDetails);

                doc.setFont('helvetica', 'bold');
                doc.text("ID Pinjaman", labelX, startYDetails + detailLineHeight);
                doc.text(":", colonX, startYDetails + detailLineHeight);
                doc.setFont('helvetica', 'normal');
                doc.text(idPinjaman, valueX, startYDetails + detailLineHeight);

                doc.setFont('helvetica', 'bold');
                doc.text("Total Angsuran Dibayar", labelX, startYDetails + detailLineHeight * 2);
                doc.text(":", colonX, startYDetails + detailLineHeight * 2);
                doc.setFont('helvetica', 'normal');
                doc.text(`Rp ${totalJumlahAngsuran.toLocaleString('id-ID')}`, valueX, startYDetails + detailLineHeight * 2);

                doc.setFont('helvetica', 'bold');
                doc.text("Tenor (Terbayar/Total)", labelX, startYDetails + detailLineHeight * 3);
                doc.text(":", colonX, startYDetails + detailLineHeight * 3);
                doc.setFont('helvetica', 'normal');
                doc.text(`${sudahBayar} / ${tenorPinjaman} bulan`, valueX, startYDetails + detailLineHeight * 3);

                doc.setFont('helvetica', 'bold');
                doc.text("Sisa Tenor", labelX, startYDetails + detailLineHeight * 4);
                doc.text(":", colonX, startYDetails + detailLineHeight * 4);
                doc.setFont('helvetica', 'normal');
                doc.text(`${sisaTenor} bulan`, valueX, startYDetails + detailLineHeight * 4);


                // --- Tabel Angsuran ---
                const tableStartY = startYDetails + detailLineHeight * 6; // Posisi Y awal untuk tabel

                const tableData = dataAngsuranForPdf.map((item, index) => [
                    index + 1,
                    item.id_angsuran,
                    "Rp " + parseInt(item.jumlah_angsuran).toLocaleString("id-ID"),
                    new Date(item.tanggal).toLocaleDateString("id-ID")
                ]);

                doc.autoTable({
                    startY: tableStartY,
                    head: [["No", "ID Angsuran", "Jumlah Angsuran", "Tanggal Angsuran"]],
                    body: tableData,
                    styles: {
                        fontSize: 10, // Ukuran font tabel
                        lineColor: [0, 0, 0], 
                        lineWidth: 0.2, // Ketebalan garis tabel
                        cellPadding: 2, // Padding sel
                    },
                    headStyles: {
                        fillColor: [146, 136, 188], // Warna ungu tua sesuai tabel web
                        textColor: 255, 
                        lineColor: [0, 0, 0], 
                        lineWidth: 0.2,
                        halign: 'center', // Rata tengah header
                    },
                    bodyStyles: {
                        textColor: 0,
                        valign: 'middle', // Vertikal tengah
                        halign: 'center' // Rata tengah semua kolom body
                    },
                    columnStyles: {
                        0: { halign: 'center' }, // No
                        1: { halign: 'center' }, // ID Angsuran
                        2: { halign: 'center' }, // Jumlah Angsuran (agar sejajar dengan Rp)
                        3: { halign: 'center' }  // Tanggal Angsuran
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245], // Abu-abu muda untuk baris selang-seling
                    },
                });

                // --- Tanda Tangan ---
                const finalY = doc.lastAutoTable.finalY || (tableStartY + 10); // Ambil Y setelah tabel
                const pageWidth = doc.internal.pageSize.getWidth();
                doc.setFontSize(10); // Ukuran font tanda tangan lebih kecil
                doc.setFont('helvetica', 'normal');

                const tanggalUnduh = new Date();
                const tanggalFormat = ("0" + tanggalUnduh.getDate()).slice(-2) + '/' +
                                      ("0" + (tanggalUnduh.getMonth() + 1)).slice(-2) + '/' +
                                      tanggalUnduh.getFullYear();

                const textKediriTanggal = `Kediri, ${tanggalFormat}`;
                // Hitung posisi X agar rata kanan
                const textKediriTanggalWidth = doc.getTextWidth(textKediriTanggal);
                doc.text(textKediriTanggal, pageWidth - textKediriTanggalWidth - 14, finalY + 15);

                const textAdminKoperasi = "Admin Koperasi";
                const textAdminKoperasiWidth = doc.getTextWidth(textAdminKoperasi);
                doc.text(textAdminKoperasi, pageWidth - textAdminKoperasiWidth - 14, finalY + 40);

                const namaFile = nama.replace(/\s+/g, '_') || 'anggota';
                doc.save(`angsuran_${namaFile}_${idPinjaman}.pdf`);

            } catch (error) {
                alert("Gagal membuat PDF: " + error);
                console.error("PDF Error:", error);
            }
        });
    });
</script>
@endsection