@extends('layouts.app')
@section('title', 'Tambah Angsuran')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body { background-color: #EDECFF !important; }
    .form-card { background-color: white; border-radius: 10px; padding: 0; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    .form-header { background-color: #e0dbf9; padding: 20px 30px; border-top-left-radius: 10px; border-top-right-radius: 10px; }
    .form-header h2 { margin: 0; font-size: 18px; font-weight: bold; color: #5D3FD3; }
    .form-body { padding: 30px; }
    .form-label { font-weight: 600; margin-bottom: 5px; }
    .form-control:disabled { background-color: #e9ecef; color: #6c757d; }
    .btn-simpan { background-color:#007bff; color: white; border: none; }
    .btn-kembali { background-color: #28a745; color: white; border: none; }
    .btn + .btn { margin-left: 10px; }
    .text-danger { color: red; }
/* Style dasar select2 agar mirip input biasa */
.select2-container .select2-selection--single {
    height: 38px;           /* tinggi sama dengan input.form-control */
    padding: 0.375rem 0.75rem; /* padding seperti input */
    border: 1px solid #ccc; /* warna border sama */
    border-radius: 6px;     /* border-radius sama */
    font-size: 1rem;
    color: #495057;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;      /* sesuaikan teks vertikal */
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px;
    right: 10px;
}

.select2-container--default.select2-container--open .select2-selection--single {
    border-color:rgb(0, 123, 255);
    box-shadow: 0 0 0 0.2rem rgb(0 123 255 / 25%);

}

</style>
@endsection

@section('content')
<div class="form-card">
    <div class="form-header">
        <h2>Tambah Angsuran</h2>
    </div>
    <div class="form-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('angsuran.store') }}">
            @csrf

            <div class="mb-3">
                <label for="id_angsuran" class="form-label">ID Angsuran<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="id_angsuran" name="id_angsuran" value="{{ $newId }}" readonly>
            </div>

            <div class="mb-3">
                <label for="id_anggota" class="form-label">Nama Anggota<span class="text-danger">*</span></label>
                <select class="form-control" id="id_anggota" name="id_anggota" required>
                    <option value="" disabled selected>-- Pilih Anggota --</option>
                    @foreach($anggotas as $anggota)
                        <option value="{{ $anggota->id_anggota }}">{{ $anggota->id_anggota }} - {{ $anggota->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Pinjaman (Rp)</label>
                <input type="text" class="form-control" id="jumlah_pinjaman" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Tenor Pinjaman (bulan)</label>
                <input type="number" class="form-control" id="tenor_pinjaman" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Pinjaman</label>
                <input type="date" class="form-control" id="tanggal_pinjaman" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Sisa Angsuran</label>
                <input type="text" class="form-control" id="sisa_angsuran" readonly>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Angsuran<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                <div id="peringatan-angsuran" class="text-danger mt-1" style="display: none;">
                    Anggota ini sudah membayar angsuran pada bulan ini.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Angsuran (Rp)</label>
                <input type="text" class="form-control" id="jumlah_angsuran" readonly>
                <input type="hidden" id="jumlah_angsuran_raw" name="jumlah_angsuran">
            </div>

            <input type="hidden" id="id_pinjaman" name="id_pinjaman">

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('angsuran.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    $('#id_anggota').select2({
        placeholder: "-- Pilih Anggota --",
        allowClear: true,
        width: '100%'
    });

    function formatRupiah(angka) {
        if (!angka) return '';
        const bulat = Math.ceil(parseFloat(angka));
        return bulat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function cekAngsuranBulan() {
        const anggotaId = $('#id_anggota').val();
        const tanggal = $('#tanggal').val();
        const idPinjaman = $('#id_pinjaman').val();
        const simpanBtn = $('.btn-simpan');

        if (!anggotaId || !tanggal || !idPinjaman) return;

        fetch(`/angsuran/cek-bulan?id_anggota=${anggotaId}&tanggal=${tanggal}&id_pinjaman=${idPinjaman}`)
            .then(res => res.json())
            .then(data => {
                if (data.exists) {
                    $('#peringatan-angsuran').show();
                    simpanBtn.prop('disabled', true);
                } else {
                    $('#peringatan-angsuran').hide();
                    simpanBtn.prop('disabled', false);
                }
            });
    }

    $('#id_anggota').on('change', function () {
        const anggotaId = $(this).val();
        if (!anggotaId) {
            $('#jumlah_pinjaman').val('');
            $('#tenor_pinjaman').val('');
            $('#tanggal_pinjaman').val('');
            $('#id_pinjaman').val('');
            $('#sisa_angsuran').val('');
            $('#tanggal').val('');
            $('#jumlah_angsuran').val('');
            $('#jumlah_angsuran_raw').val('');
            $('.btn-simpan').prop('disabled', true);
            return;
        }

        fetch(`/pinjaman/${anggotaId}/data`)
            .then(res => res.json())
            .then(data => {
                if (!data || !data.jumlah || !data.tenor || !data.tanggal_pinjaman || !data.id_pinjaman) {
                    alert("Data pinjaman tidak valid.");
                    return;
                }

                $('#jumlah_pinjaman').val(formatRupiah(data.jumlah));
                $('#tenor_pinjaman').val(data.tenor);
                $('#tanggal_pinjaman').val(data.tanggal_pinjaman);
                $('#id_pinjaman').val(data.id_pinjaman);

                const bungaPerBulan = 0.0125;
                const angsuranPokok = data.jumlah / data.tenor;
                const angsuranBulan = [];

                let sisa = data.jumlah;
                for (let i = 0; i < data.tenor; i++) {
                    const bunga = sisa * bungaPerBulan;
                    const total = angsuranPokok + bunga;
                    angsuranBulan.push(Math.ceil(total)); // pembulatan di sini
                    sisa -= angsuranPokok;
                }

                const startDate = new Date(data.tanggal_pinjaman);
                startDate.setMonth(startDate.getMonth() + 1);

                const endDate = new Date(startDate);
                endDate.setMonth(endDate.getMonth() + data.tenor - 1);

                function formatDate(date) {
                    let d = date.getDate();
                    let m = date.getMonth() + 1;
                    let y = date.getFullYear();
                    if (m < 10) m = '0' + m;
                    if (d < 10) d = '0' + d;
                    return `${y}-${m}-${d}`;
                }

                $('#tanggal').attr('min', formatDate(startDate));
                $('#tanggal').attr('max', formatDate(endDate));
                $('#tanggal').val('');
                $('#jumlah_angsuran').val('');
                $('#jumlah_angsuran_raw').val('');
                $('.btn-simpan').prop('disabled', true);

                // Hitung sisa angsuran dan tampilkan
                fetch(`/angsuran/jumlah/${data.id_pinjaman}`)
                    .then(res => res.json())
                    .then(resData => {
                        console.log('total angsuran dari API:', resData.jumlah); // lihat key jumlah
                        const angsuranKe = resData.jumlah || 0; // gunakan key jumlah
                        const sisaAngsuran = data.tenor - angsuranKe;
                        console.log('sisa angsuran:', sisaAngsuran);
                        $('#sisa_angsuran').val(`${sisaAngsuran} bulan`);
                    });

                $('#tanggal').off('change').on('change', function () {
                    const selectedDate = new Date(this.value);
                    const bulanKe = (selectedDate.getFullYear() - startDate.getFullYear()) * 12 + (selectedDate.getMonth() - startDate.getMonth());

                    if (bulanKe >= 0 && bulanKe < angsuranBulan.length) {
                        const angsuran = angsuranBulan[bulanKe];
                        $('#jumlah_angsuran').val(formatRupiah(angsuran));
                        $('#jumlah_angsuran_raw').val(angsuran);
                    } else {
                        alert("Tanggal angsuran tidak valid. Harap pilih tanggal sesuai tenor pinjaman.");
                        $('#jumlah_angsuran').val('');
                        $('#jumlah_angsuran_raw').val('');
                    }

                    cekAngsuranBulan();
                });
            })
            .catch(err => {
                console.error(err);
                alert('Gagal mengambil data pinjaman.');
            });
    });

    $('#tanggal').on('change', cekAngsuranBulan);
    $('.btn-simpan').prop('disabled', true);
});
</script>
@endsection
