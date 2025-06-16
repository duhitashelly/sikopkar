<!-- Ganti kode sebelumnya dengan ini -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - SI KOPKAR</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


  <style>
    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      height: 100vh;
      width: 100%;
      display: flex;
      overflow: hidden;
      background: #f5f5f5;
      transition: background 0.4s ease, color 0.4s ease;
    }

    body.dark {
      background-color: #1e1e2f;
      color: #f1f1f1;
    }

    .container {
      display: flex;
      flex: 1;
      height: 100%;
      position: relative;
    }

    .left-panel {
      flex: 1;
      background-color: #fff;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      box-shadow: 10px 0 20px rgba(0, 0, 0, 0.08);
      animation: slideLeft 1s ease forwards;
      opacity: 0;
    }

    @keyframes slideLeft {
      from {
        transform: translateX(-100px);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    body.dark .left-panel {
      background-color: #2c2c3c;
      color: #fff;
    }

    .left-panel h2 {
      font-size: 30px;
      margin-bottom: 30px;
      font-weight: 600;
      text-align: center;
    }

    form {
      width: 100%;
      max-width: 350px;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    .form-group input {
      width: 100%;
      padding: 14px 18px 14px 45px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 15px;
      transition: border 0.3s;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .form-group i {
      position: absolute;
      top: 50%;
      left: 15px;
      transform: translateY(-50%);
      color: #888;
    }

    body.dark .form-group input {
      background-color: #3a3a4f;
      border-color: #555;
      color: #f1f1f1;
    }

    .form-group input:focus {
      border-color: #6A1B9A;
      outline: none;
      box-shadow: 0 0 5px rgba(106, 27, 154, 0.4);
    }

    .login-btn {
      width: 100%;
      background: #6A1B9A;
      color: #fff;
      padding: 14px;
      font-size: 16px;
      font-weight: 600;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-btn:hover {
      background: #4A148C;
    }

    .register-link {
      margin-top: 20px;
      text-align: center;
      font-size: 13px;
      color: #666;
    }

    .register-link a {
      color: #6A1B9A;
      text-decoration: none;
      font-weight: 600;
    }

    .register-link a:hover {
      text-decoration: underline;
    }

    .right-panel {
      position: relative;
      flex: 1;
      background: url('img/rumah.jpg') no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    /* Cahaya abstrak bergerak (3 lapis lingkaran transparan) */
    .right-panel::before,
    .right-panel::after,
    .right-panel .shine {
      content: '';
      position: absolute;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 80%);
      pointer-events: none;
      animation-timing-function: ease-in-out;
      animation-iteration-count: infinite;
      animation-direction: alternate;
      z-index: 2;
      filter: drop-shadow(0 0 8px rgba(255,255,255,0.3));
    }

    .right-panel::before {
      width: 220px;
      height: 220px;
      top: 15%;
      left: 20%;
      animation-name: move1;
      animation-duration: 12s;
    }

    .right-panel::after {
      width: 160px;
      height: 160px;
      top: 60%;
      left: 55%;
      animation-name: move2;
      animation-duration: 15s;
    }

    .right-panel .shine {
      width: 270px;
      height: 270px;
      top: 40%;
      left: 75%;
      animation-name: move3;
      animation-duration: 18s;
    }

    @keyframes move1 {
      0% { transform: translate(0, 0); }
      25% { transform: translate(40px, -30px); }
      50% { transform: translate(80px, 20px); }
      75% { transform: translate(30px, 50px); }
      100% { transform: translate(0, 0); }
    }

    @keyframes move2 {
      0% { transform: translate(0, 0); }
      25% { transform: translate(-30px, 40px); }
      50% { transform: translate(-60px, 0); }
      75% { transform: translate(-20px, -40px); }
      100% { transform: translate(0, 0); }
    }

    @keyframes move3 {
      0% { transform: translate(0, 0); }
      25% { transform: translate(50px, 20px); }
      50% { transform: translate(20px, -30px); }
      75% { transform: translate(-30px, 30px); }
      100% { transform: translate(0, 0); }
    }

    body.dark .right-panel {
      filter: brightness(0.8);
    }

    .welcome {
      max-width: 400px;
      text-align: center;
      color: #fff;
      position: relative;
      z-index: 3; /* supaya di atas cahaya */
    }

    .welcome h1 {
      font-size: 36px;
      font-weight: 700;
      margin-bottom: 12px;
      text-shadow: 0 1px 4px rgba(0,0,0,0.5);
    }

    .welcome p {
      font-size: 15px;
      color: #e0e0e0;
    }

    .error-box {
      background: #ffe6e6;
      color: #d32f2f;
      padding: 12px;
      border-radius: 6px;
      font-size: 13px;
      margin-bottom: 20px;
    }

    .theme-switch {
      position: absolute;
      top: 20px;
      right: 20px;
      z-index: 10;
    }

    .theme-switch input {
      display: none;
    }

    .theme-switch .slider {
      display: inline-block;
      width: 50px;
      height: 26px;
      background-color: #ccc;
      border-radius: 50px;
      position: relative;
      cursor: pointer;
    }

    .theme-switch .slider::before {
      content: "";
      position: absolute;
      width: 22px;
      height: 22px;
      left: 2px;
      top: 2px;
      background: white;
      border-radius: 50%;
      transition: all 0.3s;
    }

    .theme-switch input:checked + .slider {
      background-color: #6A1B9A;
    }

    .theme-switch input:checked + .slider::before {
      transform: translateX(24px);
    }
    .error-box {
      transition: opacity 0.5s ease;
    }

  </style>
</head>
<body>
  <label class="theme-switch">
    <input type="checkbox" id="darkToggle">
    <span class="slider"></span>
  </label>

  <div class="container">
    <!-- Welcome Panel -->
    <div class="right-panel">
      <div class="welcome">
        <h1>Welcome to <b>Website</b></h1>
        <p>Sistem Informasi Koperasi Simpan Pinjam</p>
      </div>
    </div>

    <!-- Login Panel -->
    <div class="left-panel">
      <h2>Login</h2>

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <i class="fas fa-user"></i>
          <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Username" required autofocus>
        </div>

        <div class="form-group">
          <i class="fas fa-lock"></i>
          <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

        @if ($errors->has('name') || $errors->has('password'))
          <div class="error-box" id="errorMessage">
            Username atau password salah.
          </div>
        @endif

        <button type="submit" class="login-btn">LOGIN</button>
      </form>
    </div>
  </div>

  <script>
    const toggle = document.getElementById('darkToggle');
    const body = document.body;

    if (localStorage.getItem("dark-mode") === "true") {
      body.classList.add("dark");
      toggle.checked = true;
    }

    toggle.addEventListener('change', () => {
      body.classList.toggle("dark");
      localStorage.setItem("dark-mode", body.classList.contains("dark"));
    });

    // Logout when coming back via browser history
    window.addEventListener("pageshow", function(event) {
      const fromBack = event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward";
      if (fromBack) {
        window.location.href = "{{ route('logout') }}";
      }
    });
  </script>
  <script>
  // Auto-hide error message after 3 seconds
  const errorBox = document.getElementById('errorMessage');
  if (errorBox) {
    setTimeout(() => {
      errorBox.style.opacity = '0';
      setTimeout(() => {
        errorBox.remove();
      }, 500); // Tunggu transisi selesai
    }, 3000);
  }
</script>
</body>
</html>
