<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'EventKita')</title>

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link 
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    >
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- Style tambahan dari tiap halaman --}}
    @stack('styles')
</head>
<body class="@yield('body-class', 'bg-white')">

    {{-- Navbar --}}
    @if (!request()->is('login') 
    && !request()->is('register') 
    && !request()->is('password/*') 
    && !request()->is('email/verify*')
    && !request()->is('admin/*') )
    @include('layouts.navbar')
@endif
 

    {{-- Bagian body halaman (diisi oleh child file) --}}
    <main>
      @yield('content')
    </main>

    {{-- Footer --}}
    @if (!request()->is('login') 
    && !request()->is('register') 
    && !request()->is('password/*') 
    && !request()->is('admin/*') 
    && !request()->is('email/verify*'))
    @include('layouts.footer')
@endif
 
<!-- File JS kamu -->
<script src="{{ asset('js/main.js') }}"></script>
    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
