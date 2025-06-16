@extends('layouts.app')
@section('title', 'Tambah Simpanan')

@section('styles')
@section('styles')
<style>
    body {
        background-color: #EDECFF !important;
    }
    
    .form-card {
        background-color: white;
        border-radius: 10px;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .form-header {
        background-color: #e0dbf9;
        padding: 20px 30px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .form-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        color: #5D3FD3;
    }
    .form-body {
        padding: 30px;
    }
    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
    }
    .form-control:disabled {
        background-color: #e9ecef;
        color: #6c757d;
    }
    .btn-simpan {
        background-color: #007bff;
        color: white;
        border: none;
    }
    .btn-kembali {
        background-color: #28a745;
        color: white;
        border: none;
    }
    .btn + .btn {
        margin-left: 10px;
    }
    .text-danger {
        color: red;
    }

.select2-container--default .select2-selection--single {
    height: 38px !important;          /* tinggi kotak */
    padding: 6px 12px !important;     /* padding dalam kotak */
    border: 1px solid #ced4da !important;
    border-radius: 4px !important;
    font-size: 1rem !important;
    line-height: 26px !important;
    box-sizing: border-box !important;
    width: 100% !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 26px !important;     /* vertikal teks */
    padding-left: 0 !important;       /* agar teks mulai dari kiri kotak */
    color: #495057;                   /* warna teks seperti input biasa */
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px !important;
    top: 1px !important;
    right: 12px !important;
}

</style>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="form-card">
    <div class="form-header">
        <h2>Tambah Simpanan</h2>
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

        <form method="POST" action="{{ route('simpanan.store') }}">
            @csrf

            <div class="mb-3">
                <label for="id_simpanan" class="form-label">ID Simpanan<span class="text-danger">*</span></label>
                <input type="text" readonly class="form-control" id="id_simpanan" name="id_simpanan" value="{{ $newId }}">
            </div>

            <div class="mb-3">
                <label for="id_anggota" class="form-label">Nama Anggota<span class="text-danger">*</span></label>
                <select class="form-control" id="id_anggota" name="id_anggota" required>
                    <option value="" disabled selected>-- Pilih Anggota --</option>
                    @foreach($anggotas as $anggota)
                        <option 
                            data-status="{{ $anggota->jenis_anggota }}" 
                            data-kategori="{{ $anggota->kategori }}"
                            value="{{ $anggota->id_anggota }}">
                            {{ $anggota->id_anggota }} - {{ $anggota->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Anggota</label>
                <label id="status_label" class="form-control" readonly>-</label>
            </div>

            <div class="mb-3">
                <label for="jenis_simpanan" class="form-label">Jenis Simpanan<span class="text-danger">*</span></label>
                <select class="form-control" id="jenis_simpanan" name="jenis_simpanan" required>
                    <option value="" disabled selected>-- Pilih Jenis Simpanan --</option>
                    <option value="Simpanan Pokok">Simpanan Pokok</option>
                    <option value="Simpanan Wajib">Simpanan Wajib</option>
                    <option value="Simpanan Sukarela">Simpanan Sukarela</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah Simpanan<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="jumlah" name="jumlah" required>
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal Simpanan<span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>

            <button type="submit" class="btn btn-simpan">Simpan</button>
            <a href="{{ route('simpanan.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- JQuery dan Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    const anggotaSelect = $('#id_anggota');
    const jenisSelect = document.getElementById('jenis_simpanan');
    const jumlahInput = document.getElementById('jumlah');
    const statusLabel = document.getElementById('status_label');

    anggotaSelect.select2({
        placeholder: "-- Pilih Anggota --",
        allowClear: true,
        width: '100%'
    });

    function formatRupiah(angka) {
        return angka.toString().replace(/\D/g, '')
            .replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function unformatRupiah(angka) {
        return angka.replace(/\./g, '');
    }

    function updateForm() {
        const selectedOption = anggotaSelect.find('option:selected');
        const status = selectedOption.data('status') || '-';
        const jenis = jenisSelect.value;

        statusLabel.textContent = status;

        if (jenis === 'Simpanan Pokok') {
            jumlahInput.value = formatRupiah(10000);
            jumlahInput.readOnly = true;
        } else if (jenis === 'Simpanan Wajib') {
            let val = 0;
            if (status === 'nonkontrak') val = 30000;
            else if (status === 'pns') val = 100000;
            else if (status === 'pensiunan') val = 10000;
            jumlahInput.value = formatRupiah(val);
            jumlahInput.readOnly = true;
        } else if (jenis === 'Simpanan Sukarela') {
            jumlahInput.value = '';
            jumlahInput.readOnly = false;
        } else {
            jumlahInput.value = '';
            jumlahInput.readOnly = true;
        }
    }

    jumlahInput.addEventListener('input', function(e) {
        if (!jumlahInput.readOnly) {
            const unformatted = unformatRupiah(e.target.value);
            e.target.value = formatRupiah(unformatted);
        }
    });

    anggotaSelect.on('change', updateForm);
    jenisSelect.addEventListener('change', updateForm);
    updateForm(); // Inisialisasi
});
</script>
@endpush
