@extends('layouts.app')
@section('title', 'Tambah Pengguna')

@section('content')
<style>
    body {
        background-color: #EDECFF !important;
    }

    .form-card {
        background-color: white;
        border-radius: 10px;
        padding: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        max-width: 2000px;
        margin: 10px auto;
        box-sizing: border-box;
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
        display: block;
    }

    input.form-control,
    select.form-control {
        width: 100%;
        padding: 6px 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        box-sizing: border-box;
        font-size: 1rem;
    }

    .btn-submit {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-submit:hover {
        background-color: #0056b3;
    }

    .btn-cancel {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        margin-left: 10px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-cancel:hover {
        background-color: #1e7e34;
        color: white;
        text-decoration: none;
    }
</style>

<div class="form-card">
    <div class="form-header">
        <h2>Tambah Pengguna</h2>
    </div>
    <div class="form-body">
        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf
            <label>Username</label>
            <input type="text" name="name" class="form-control" required>

            <label>Email</label>
            <input type="email" name="email" class="form-control" required>

            <label>Password</label>
            <input type="password" name="password" class="form-control" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>

            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="admin">Admin</option>
                <option value="kepalakoperasi">Kepala Koperasi</option>
            </select>

            <button type="submit" class="btn-submit">Simpan</button>
            <a href="{{ route('pengguna.index') }}" class="btn-cancel">Kembali</a>
        </form>
    </div>
</div>
@endsection
