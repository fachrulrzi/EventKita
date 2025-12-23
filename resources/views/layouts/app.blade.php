<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', 'EventKita') | Temukan Event Terbaik</title>

    {{-- Fonts: Menggunakan Plus Jakarta Sans agar SEIRAMA dengan Admin Panel --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-hover: #0b5ed7;
            --secondary-color: #6c757d;
            --bg-body: #f8fafc; /* Warna background sedikit lebih soft/kebiruan modern */
            --font-main: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            font-family: var(--font-main);
            color: #334155; /* Warna teks tidak hitam pekat agar mata nyaman */
            line-height: 1.6;
            overflow-x: hidden;
            background-color: var(--bg-body);
            -webkit-font-smoothing: antialiased; /* Font rendering tajam */
            -moz-osx-font-smoothing: grayscale;
            
            /* Flexbox setup untuk Sticky Footer */
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Custom Scrollbar (Agar terlihat modern) */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Text Selection Color */
        ::selection {
            background: rgba(13, 110, 253, 0.2);
            color: var(--primary-color);
        }

        /* Navbar Glassmorphism Effect */
        .navbar-custom {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background-color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        .navbar-custom.shadow-sm {
            box-shadow: 0 4px 20px rgba(0,0,0,0.05) !important;
        }

        /* Custom Button Global */
        .btn-primary-custom {
            background-color: var(--primary-color);
            color: white;
            border-radius: 50px; /* Lebih modern dengan rounded pill */
            padding: 8px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.15);
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(13, 110, 253, 0.25);
            background-color: var(--primary-hover);
            color: white;
        }

        /* Animasi Transisi Halaman Sederhana */
        main {
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            flex: 1; /* PENTING: Agar footer terdorong ke bawah */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Footer Styling */
        footer {
            background-color: #1e1b4b; /* Dark Indigo (Sama dengan Sidebar Admin) */
            color: #94a3b8;
            margin-top: auto; /* PENTING: Bagian dari Sticky Footer */
        }
    </style>

    {{-- Style tambahan dari tiap halaman (Child File) --}}
    @stack('styles')
</head>
<body class="@yield('body-class', '')">

    {{-- Logic Navbar: Disembunyikan di halaman Auth & Admin --}}
    {{-- LOGIKA INI DIPERTAHANKAN 100% SESUAI PERMINTAAN --}}
    @if (!request()->is('login') 
        && !request()->is('register') 
        && !request()->is('password/*') 
        && !request()->is('email/verify*')
        && !request()->is('admin/*') )
        @include('layouts.navbar')
    @endif

    {{-- Bagian Utama --}}
    {{-- Menambahkan padding-top agar konten tidak tertutup navbar fixed (jika navbar fixed) --}}
    <main>
        @yield('content')
    </main>

    {{-- Logic Footer: Disembunyikan di halaman Auth & Admin --}}
    {{-- LOGIKA INI DIPERTAHANKAN 100% SESUAI PERMINTAAN --}}
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
            if (nav && window.scrollY > 20) { // Trigger lebih cepat (20px) agar responsif
                nav.classList.add('shadow-sm');
                nav.classList.add('bg-white'); // Ensure white background on scroll
            } else if (nav) {
                nav.classList.remove('shadow-sm');
                // Optional: remove bg-white if you want transparent at top
            }
        });
    </script>

    @stack('scripts')
</body>
</html>