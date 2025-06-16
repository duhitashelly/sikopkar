<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .stars {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -2;
        }

        .stars span {
            position: absolute;
            background: white;
            border-radius: 100%;
            opacity: 0.8;
            animation: rise 18s linear infinite;
            box-shadow: 0 0 6px white;
        }

        @keyframes rise {
            0% {
                transform: translateY(100vh) scale(0.8);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-10vh) scale(1.4);
                opacity: 0;
            }
        }

        .aurora {
            position: fixed;
            width: 120%;
            height: 120%;
            top: -10%;
            left: -10%;
            background: radial-gradient(circle at 20% 40%, rgba(255, 0, 255, 0.2), transparent 60%),
                        radial-gradient(circle at 80% 60%, rgba(0, 255, 255, 0.2), transparent 60%);
            filter: blur(80px);
            z-index: -3;
            animation: auroraMove 20s ease-in-out infinite alternate;
        }

        @keyframes auroraMove {
            0% { transform: scale(1) translateX(0); }
            100% { transform: scale(1.1) translateX(10px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-indigo-800 to-purple-900 text-white flex items-center justify-center h-screen relative">

    <!-- Efek aurora -->
    <div class="aurora"></div>

    <!-- Efek bintang -->
    <div class="stars">
        @for ($i = 0; $i < 50; $i++)
            @php
                $size = rand(2, 5);
                $left = rand(0, 100);
                $top = rand(0, 100);
                $delay = rand(0, 20);
                $duration = rand(12, 25);
            @endphp
            <span style="
                width: {{ $size }}px;
                height: {{ $size }}px;
                left: {{ $left }}%;
                top: {{ $top }}%;
                animation-delay: {{ $delay }}s;
                animation-duration: {{ $duration }}s;
            "></span>
        @endfor
    </div>

    <!-- Logo RSUD -->
    <div class="absolute top-4 left-4 flex items-center z-20">
        <img src="{{ asset('img/rsud-gambiran-logo.png') }}" alt="RSUD Gambiran" class="w-14 h-auto">
        <span class="ml-3 text-white font-semibold text-lg leading-tight">
            RSUD Gambiran Kota Kediri
        </span>
    </div>

    <!-- Konten utama -->
    <div class="text-center space-y-6 z-10">
        <h1 class="text-5xl font-bold drop-shadow-md">Selamat Datang di Website Koperasi</h1>
        <p class="text-lg text-gray-200">Silakan login atau daftar untuk melanjutkan</p>
        <div class="flex justify-center gap-6">
            <a href="{{ route('login') }}" class="bg-white text-purple-700 font-semibold px-6 py-3 rounded-full shadow-lg hover:bg-purple-200 transition">Login</a>
            <a href="{{ route('register') }}" class="bg-white text-purple-700 font-semibold px-6 py-3 rounded-full shadow-lg hover:bg-purple-200 transition">Register</a>
        </div>
    </div>

</body>
</html>
