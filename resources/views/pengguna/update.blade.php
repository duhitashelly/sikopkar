@extends('layouts.app')
@section('title', 'Edit Pengguna')

@section('content')
<style>
    body { background-color: #EDECFF !important; }

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

    label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }

    input.form-control,
    select.form-control {
        margin-bottom: 15px;
    }

    .btn-simpan {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 6px 20px;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-kembali {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        margin-left: 10px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-simpan:hover {
        background-color: #0056b3;
    }

    .btn-kembali:hover {
        background-color: #1e7e34;
    }

    .text-danger {
        color: red;
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h2>Edit Pengguna</h2>
    </div>
    <div class="form-body">
        <form action="{{ route('pengguna.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>

            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>

            <label>Password (biarkan kosong jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="form-control">

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">

            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin" {{ strtolower($user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="kepalakoperasi" {{ strtolower($user->role) == 'kepalakoperasi' ? 'selected' : '' }}>Kepala Koperasi</option>
            </select>

            <button type="submit" class="btn-simpan">Simpan</button>
            <a href="{{ url('/pengguna') }}" class="btn-kembali">Kembali</a>
        </form>
    </div>
</div>
@endsection
