<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'EventKita') | Temukan Event Terbaik</title>

    {{-- Fonts: Menggunakan Poppins untuk kesan modern dan clean --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --bg-soft: #f8f9fa;
            --font-main: 'Poppins', sans-serif;
        }

        body {
            font-family: var(--font-main);
            color: #333;
            line-height: 1.6;
            overflow-x: hidden;
            scroll-behavior: smooth;
        }

        /* Navbar Glassmorphism Effect */
        .navbar-custom {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        /* Custom Button Global */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            background-color: #0b5ed7;
        }

        /* Animasi Transisi Halaman Sederhana */
        main {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Background Soft Surface */
        .bg-brand-surface {
            background-color: #f4f7fe;
        }

        /* Footer Styling */
        footer {
            background-color: #1a1a1a;
            color: #adb5bd;
        }
    </style>

    {{-- Style tambahan dari tiap halaman (Child File) --}}
    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-white')">

    {{-- Logic Navbar: Disembunyikan di halaman Auth & Admin --}}
    @if (!request()->is('login') 
        && !request()->is('register') 
        && !request()->is('password/*') 
        && !request()->is('email/verify*')
        && !request()->is('admin/*') )
        @include('layouts.navbar')
    @endif

    {{-- Bagian Utama --}}
    <main style="min-height: 80vh;">
        @yield('content')
    </main>

    {{-- Logic Footer: Disembunyikan di halaman Auth & Admin --}}
    @if (!request()->is('login') 
        && !request()->is('register') 
        && !request()->is('password/*') 
        && !request()->is('admin/*') 
        && !request()->is('email/verify*'))
        @include('layouts.footer')
    @endif

    {{-- Scripts Area --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    {{-- Script untuk Navbar saat Scroll --}}
    <script>
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.navbar');
            if (nav && window.scrollY > 50) {
                nav.classList.add('shadow-sm');
            } else if (nav) {
                nav.classList.remove('shadow-sm');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>