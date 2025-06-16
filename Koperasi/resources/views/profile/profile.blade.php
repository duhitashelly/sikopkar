@extends('layouts.app')
@section('title', 'Profile')
@section('content')

<style>
  body {
    background: #f4f1fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .profile-container {
    padding: 40px 20px 20px; /* lebih naik sedikit */
    display: flex;
    justify-content: center;
  }

  .profile-card {
    background: #fff;
    max-width: 480px;
    width: 100%;
    padding: 40px 30px;
    border-radius: 16px;
    box-shadow: 
      0 20px 40px rgba(193, 66, 66, 0.6), 
      0 8px 20px rgba(111, 66, 193, 0.4),  
      0 0 15px rgba(111, 66, 193, 0.8);   
    box-sizing: border-box;
  }

  .profile-header {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
  }

  .profile-icon {
    width: 60px;
    height: 60px;
    background-color: #6f42c1;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    font-size: 28px;
    box-shadow: 0 4px 12px rgba(111, 66, 193, 0.4);
    flex-shrink: 0;
  }

  .profile-name {
    margin-left: 20px;
    font-weight: 700;
    font-size: 1.6rem;
    color: #4b306a;
    user-select: none;
  }

  label.form-label {
    font-weight: 600;
    color: #6f42c1;
    font-size: 0.95rem;
  }

  input.form-control[readonly] {
    background-color: #f4efff;
    border: 1px solid #d3c0f9;
    color: #5b3a99;
    font-weight: 600;
    letter-spacing: 0.02em;
  }

  .input-group-text {
    background-color: #6f42c1;
    border: none;
    color: white;
    cursor: default;
    user-select: none;
  }

  #password {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
  }

  .btn-logout {
    margin-top: 25px;
    width: 100%;
    padding: 12px 0;
    font-weight: 700;
    font-size: 1rem;
    background-color: #6f42c1;
    border: none;
    color: white;
    border-radius: 10px;
    transition: background-color 0.3s ease;
  }
  .btn-logout:hover {
    background-color: #593a99;
  }

  small.text-muted {
    display: block;
    margin-top: 6px;
    color: #8a6bc1;
    font-size: 0.85rem;
  }
</style>

<div class="profile-container">
  <div class="profile-card shadow-sm">
    <div class="profile-header">
      <div class="profile-icon">
        <i class="fas fa-user"></i>
      </div>
      <div class="profile-name">{{ $user->name }}</div>
    </div>

    <div class="mb-4">
      <label for="name" class="form-label">Name</label>
      <input type="text" id="name" readonly class="form-control" value="{{ $user->name }}">
    </div>

    <div class="mb-4">
      <label for="password" class="form-label">Password</label>
      <div class="input-group">
        <input type="password" id="password" class="form-control" readonly value="*******">
        <span class="input-group-text">
          <i class="fas fa-lock"></i>
        </span>
      </div>
      <small class="text-muted">Password tidak ditampilkan demi keamanan.</small>
    </div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn btn-logout">Log Out</button>
    </form>
  </div>
</div>

@endsection
