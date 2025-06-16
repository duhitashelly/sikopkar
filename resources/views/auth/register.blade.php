<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-slide {
            animation: fadeSlideIn 0.8s ease-out forwards;
        }

    </style>
</head>
<body class="relative bg-gradient-to-br from-purple-100 to-purple-300 min-h-screen flex items-center justify-center overflow-hidden">

  <!-- Blob Background -->
  <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-floaty"></div>
  <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-floaty delay-1000"></div>
    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md animate-fade-slide">
        <h2 class="text-2xl font-semibold text-purple-700 text-center mb-6 floaty">
            Daftar Akun
        </h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Nama -->
            <div class="transition transform hover:scale-[1.01] duration-300">
                <label for="name" class="block text-sm font-medium text-purple-700 mb-1">Username</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                    class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:outline-none text-gray-800 transition duration-300">
            </div>

            <!-- Email -->
            <div class="transition transform hover:scale-[1.01] duration-300">
                <label for="email" class="block text-sm font-medium text-purple-700 mb-1">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:outline-none text-gray-800 transition duration-300">
            </div>

            <!-- Password -->
            <div class="transition transform hover:scale-[1.01] duration-300">
                <label for="password" class="block text-sm font-medium text-purple-700 mb-1">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:outline-none text-gray-800 transition duration-300">
            </div>

            <!-- Konfirmasi Password -->
            <div class="transition transform hover:scale-[1.01] duration-300">
                <label for="password_confirmation" class="block text-sm font-medium text-purple-700 mb-1">Konfirmasi Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full px-4 py-2 border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-400 focus:outline-none text-gray-800 transition duration-300">
            </div>

            <!-- Tombol -->
                <div class="mt-4">
                @if ($errors->any())
                    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="submit"
                    class="w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-1 transition duration-300 floaty">
                    Daftar Sekarang
                </button>
            </div>

            <!-- Link ke Login -->
            <div class="text-center text-sm text-purple-600 mt-4">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-purple-900 font-semibold hover:underline">Masuk di sini</a>
            </div>
        </form>
    </div>

</body>
</html>
