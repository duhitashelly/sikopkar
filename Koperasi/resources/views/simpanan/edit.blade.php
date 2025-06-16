@extends('layouts.app')
@section('title', 'Edit Simpanan')

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
        .text-danger { color: red; }
    </style>
@endsection

@section('content')
<div class="form-card">
    <div class="form-header"><h2>Edit Simpanan</h2></div>
    <div class="form-body">
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul></div>
        @endif

        <form method="POST" action="{{ route('simpanan.update', $simpanan->id_simpanan) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">ID Simpanan</label>
                <input type="text" readonly class="form-control" name="id_simpanan"
                       value="{{ $simpanan->id_simpanan }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Anggota</label>
                <select id="id_anggota" name="id_anggota" class="form-control" required>
                    <option value="" disabled>-- Pilih Anggota --</option>
                    @foreach($anggotas as $anggota)
                        <option 
                          value="{{ $anggota->id_anggota }}"
                          data-status="{{ $anggota->jenis_anggota}}"
                          data-kategori="{{ $anggota->kategori }}"
                          {{ $simpanan->id_anggota == $anggota->id_anggota ? 'selected' : '' }}>
                          {{ $anggota->id_anggota }} - {{ $anggota->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status Anggota</label>
                <label id="status_label" class="form-control" readonly>
                    {{ $simpanan->anggota->status ?? '-' }}
                </label>
            </div>


            <div class="mb-3">
                <label class="form-label">Jenis Simpanan</label>
                <select id="jenis_simpanan" name="jenis_simpanan" class="form-control" required>
                    <option value="" disabled>-- Pilih Jenis --</option>
                    <option value="Simpanan Pokok"    {{ $simpanan->jenis_simpanan=='Simpanan Pokok'? 'selected':'' }}>Simpanan Pokok</option>
                    <option value="Simpanan Wajib"    {{ $simpanan->jenis_simpanan=='Simpanan Wajib'? 'selected':'' }}>Simpanan Wajib</option>
                    <option value="Simpanan Sukarela" {{ $simpanan->jenis_simpanan=='Simpanan Sukarela'? 'selected':'' }}>Simpanan Sukarela</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Simpanan</label>
                <input type="number" id="jumlah" name="jumlah" class="form-control"
                       value="{{ $simpanan->jumlah }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Simpanan</label>
                <input type="date" name="tanggal" class="form-control"
                       value="{{ $simpanan->tanggal }}" required>
            </div>

            <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
            <a href="{{ route('simpanan.index') }}" class="btn btn-kembali">Kembali</a>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const anggotaSel = document.getElementById('id_anggota');
    const jenisSel   = document.getElementById('jenis_simpanan');
    const jumlahInp  = document.getElementById('jumlah');
    const statusLabel = document.getElementById('status_label');

    function refreshJumlah() {
        const selectedOption = anggotaSel.selectedOptions[0];
        const kategori = selectedOption.dataset.kategori;
        const jenis    = jenisSel.value;
        const status   = selectedOption.dataset.status;

        // Perbarui label status anggota
        statusLabel.textContent = status || '-';

        if (jenis === 'Simpanan Pokok') {
            jumlahInp.value = 10000;
            jumlahInp.readOnly = true;
        } else if (jenis === 'Simpanan Wajib') {
            let val = 0;
            if (status === 'nonkontrak')   val = 30000;
            if (status === 'pns')          val = 100000;
            if (status === 'pensiunan')    val = 10000;
            jumlahInp.value = val;
            jumlahInp.readOnly = true;
        } else if (jenis === 'Simpanan Sukarela') {
            jumlahInp.readOnly = false;
        } else {
            jumlahInp.value = '';
            jumlahInp.readOnly = true;
        }
    }

    anggotaSel.addEventListener('change', refreshJumlah);
    jenisSel.addEventListener('change', refreshJumlah);

    // Panggil fungsi saat halaman selesai dimuat
    refreshJumlah();
});
</script>
@endpush