@extends('layouts.app')

@section('title', 'EventKita - Temukan Event Hiburan Terbaik!')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --border-radius-lg: 1rem;
        --border-radius-xl: 1.5rem;
    }

    /* 1. HERO SECTION STYLES */
    .hero-section {
        position: relative;
        overflow: hidden;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .hero-carousel-item {
        height: 600px; /* Sedikit lebih tinggi agar lebih megah */
        position: relative;
    }

    .hero-carousel-item img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        /* Efek zoom lambat saat aktif */
        animation: zoomEffect 20s infinite alternate;
    }

    @keyframes zoomEffect {
        from { transform: scale(1); }
        to { transform: scale(1.1); }
    }

    /* Gradient Overlay yang lebih halus untuk keterbacaan teks */
    .hero-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
        z-index: 1;
    }

    .hero-content {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 2;
        padding-bottom: 80px;
        color: white;
    }

    /* 2. CATEGORY BUBBLES */
    .category-card {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
    }
    .category-card:hover {
        transform: translateY(-10px);
    }
    .category-icon-wrapper {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        padding: 4px; /* Space for border effect */
        background: #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        margin: 0 auto 15px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .category-card:hover .category-icon-wrapper {
        border-color: var(--primary-color);
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.25);
    }
    .category-icon-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    /* 3. EVENT CARDS (CORE UI) */
    .event-card {
        border: none;
        border-radius: var(--border-radius-lg);
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .event-img-wrapper {
        position: relative;
        overflow: hidden;
        height: 220px;
    }
    .event-img-wrapper img {
        transition: transform 0.5s ease;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .event-card:hover .event-img-wrapper img {
        transform: scale(1.05);
    }
    
    /* Tombol Favorite Floating */
    .btn-fav-float {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-fav-float:hover {
        background: #fff;
        transform: scale(1.1);
    }

    /* Badge Category */
    .badge-category-float {
        background: rgba(13, 110, 253, 0.9);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
        letter-spacing: 0.5px;
    }

    /* 4. CITY CARDS */
    .city-card {
        border: none;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        position: relative;
    }
    .city-card img {
        height: 200px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .city-card:hover img {
        transform: scale(1.1);
    }
    .city-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 20px;
        pointer-events: none;
    }

    /* General Utilities */
    .section-title {
        position: relative;
        display: inline-block;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 2px;
    }
    
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

{{-- 1. SECTION HERO --}}
<section class="hero-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        {{-- Carousel Indicators (Opsional, dihapus jika tidak perlu, tapi bagus untuk UX) --}}
        @if($featuredEvents->count() > 1)
        <div class="carousel-indicators mb-5">
            @foreach($featuredEvents as $index => $featured)
                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        @endif

        <div class="carousel-inner">
            @if($featuredEvents->count() > 0)
                @foreach($featuredEvents as $index => $featured)
                    <div class="carousel-item hero-carousel-item {{ $index === 0 ? 'active' : '' }}">
                        <div class="hero-overlay"></div> {{-- Overlay Gelap --}}
                        
                        {{-- Logika Gambar --}}
                        @if($featured->image_path)
                            @php
                                $imgUrl = $featured->image_path
                                    ? (str_starts_with($featured->image_path, 'http')
                                        ? $featured->image_path
                                        : \App\Helpers\StorageHelper::url($featured->image_path))
                                    : null;
                            @endphp
                            <img src="{{ $imgUrl ?? 'https://via.placeholder.com/1200x600?text=' . urlencode($featured->title) }}" class="d-block w-100" alt="{{ $featured->title }}">
                        @else
                            <img src="https://via.placeholder.com/1200x600?text={{ urlencode($featured->title) }}" class="d-block w-100" alt="{{ $featured->title }}">
                        @endif

                        <div class="hero-content">
                            <div class="container text-start">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 text-uppercase tracking-wider shadow-sm">
                                            <i class="bi bi-star-fill me-1"></i> Featured Event
                                        </span>
                                        <h1 class="display-3 fw-bold mb-3 text-shadow">{{ $featured->title }}</h1>
                                        <p class="fs-5 mb-4 text-light opacity-90">
                                            <i class="bi bi-calendar-event me-2 text-warning"></i> {{ $featured->date->format('d F Y') }}
                                            <span class="mx-2">|</span>
                                            <i class="bi bi-geo-alt me-2 text-warning"></i> {{ $featured->location ?? 'Lokasi Event' }}
                                        </p>
                                        
                                        {{-- Tombol Detail Hero --}}
                                        <a href="{{ route('event.detail', $featured->slug) }}" 
                                           class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold shadow-lg btn-event-detail" 
                                           onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Loading...'">
                                           Lihat Detail <i class="bi bi-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Fallback jika tidak ada featured event --}}
                <div class="carousel-item hero-carousel-item active">
                    <div class="hero-overlay"></div>
                    <img src="https://nnc-media.netralnews.com/2025/08/IMG-Netral-News-Admin-68-6J5H0GLMIW.jpg" class="d-block w-100" alt="EventKita">
                    <div class="hero-content text-center">
                        <div class="container">
                            <h1 class="display-3 fw-bold mb-3">Temukan Event Paling Seru!</h1>
                            <p class="fs-4">Semua informasi hiburan, konser, dan festival ada di sini.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Navigasi Carousel --}}
        @if($featuredEvents->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon p-3 bg-dark rounded-circle bg-opacity-25" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon p-3 bg-dark rounded-circle bg-opacity-25" aria-hidden="true"></span>
            </button>
        @endif
    </div>
</section>

{{-- 2. SECTION CATEGORIES --}}
<section class="py-5 bg-white position-relative">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h5 class="text-primary fw-bold text-uppercase ls-2 small">Explorasi</h5>
            <h2 class="fw-bold section-title">Kategori Event</h2>
        </div>
        
        <div class="row justify-content-center">
            @forelse ($categories as $category)
                <div class="col-4 col-md-3 col-lg-2 mb-4">
                    <div class="category-card text-center">
                        <a href="{{ route('kategori.filter', ['slug' => $category->slug]) }}" class="text-decoration-none">
                            <div class="category-icon-wrapper">
                                @php
                                    $placeholderIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"><rect width="100%" height="100%" fill="%23f8f9fa"/><text x="50%" y="55%" font-size="12" font-family="Arial" fill="%23adb5bd" text-anchor="middle">No Icon</text></svg>';
                                    $iconUrl = $category->icon_path
                                        ? (str_starts_with($category->icon_path, 'http')
                                            ? $category->icon_path
                                            : \App\Helpers\StorageHelper::url($category->icon_path))
                                        : $placeholderIcon;
                                @endphp
                                <img src="{{ $iconUrl }}" alt="{{ $category->name }}">
                            </div>
                            <h6 class="text-dark fw-bold mt-2">{{ $category->name }}</h6>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted fst-italic">Belum ada kategori yang tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- 3. SECTION EVENT PILIHAN --}}
<section id="event-pilihan" class="py-5 bg-light">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold mb-1">Event Pilihan Minggu Ini <span class="text-primary">🔥</span></h2>
                <p class="text-muted mb-0">Jangan lewatkan momen seru bersama teman dan keluarga</p>
            </div>
            {{-- Tombol Lihat Semua (Opsional, visual only) --}}
            </div>

        <div class="row g-4">
            @forelse ($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card event-card h-100 shadow-sm">
                        
                        {{-- Image Wrapper & Badges --}}
                        <div class="event-img-wrapper">
                            {{-- Logic Image --}}
                            @if($event->image_path)
                                @php
                                    $eventImgUrl = $event->image_path
                                            ? (str_starts_with($event->image_path, 'http')
                                                ? $event->image_path
                                                : \App\Helpers\StorageHelper::url($event->image_path))
                                            : null;
                                @endphp
                                <img src="{{ $eventImgUrl ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" alt="{{ $event->title }}">
                            @endif
                            
                            {{-- Badge Kategori --}}
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge-category-float shadow-sm">
                                    {{ $event->category->name }}
                                </span>
                            </div>

                            {{-- Tombol Favorite (Form) --}}
                            @auth
                                <div class="position-absolute top-0 end-0 m-3">
                                    <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-fav-float shadow-sm" title="Tambahkan ke Favorit">
                                            @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                                <i class="bi bi-heart-fill text-danger fs-5"></i>
                                            @else
                                                <i class="bi bi-heart text-secondary fs-5"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4 d-flex flex-column">
                            {{-- Tanggal & Lokasi Kecil --}}
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <div class="d-flex align-items-center me-3">
                                    <i class="bi bi-calendar3 text-primary me-2"></i>
                                    <span>{{ $event->date->format('d M Y') }}</span>
                                </div>
                                @if($event->location)
                                    <div class="d-flex align-items-center text-truncate">
                                        <i class="bi bi-geo-alt text-danger me-2"></i>
                                        <span class="text-truncate" style="max-width: 100px;">{{ $event->location }}</span>
                                    </div>
                                @endif
                            </div>

                            <h5 class="card-title fw-bold mb-2 text-dark lh-sm text-truncate-2" style="min-height: 48px;">
                                {{ $event->title }}
                            </h5>
                            
                            <p class="card-text text-muted mb-4 small flex-grow-1">
                                {{ Str::limit($event->description, 85) }}
                            </p>
                            
                            {{-- Footer Card: Harga & Tombol --}}
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center mt-auto">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Mulai dari</small>
                                    <span class="h5 mb-0 fw-bold text-primary">{{ $event->formatted_price }}</span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    {{-- Tombol Detail --}}
                                    @if($event->slug)
                                        <a href="{{ route('event.detail', ['slug' => $event->slug]) }}" 
                                           class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold">
                                           Detail
                                        </a>
                                    @else
                                        <span class="btn btn-outline-secondary btn-sm rounded-pill px-3 disabled">Detail</span>
                                    @endif

                                    {{-- Tombol Beli / Login --}}
                                    @if($event->hasPaidTickets() && $event->slug)
                                        @auth
                                            <a href="{{ route('checkout', $event->slug) }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" title="Beli Tiket">
                                                Beli <i class="bi bi-ticket-perforated ms-1"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" title="Login untuk Beli Tiket">
                                                Beli <i class="bi bi-ticket-perforated ms-1"></i>
                                            </a>
                                        @endauth
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5 bg-white rounded-3 shadow-sm">
                        <img src="https://illustrations.popsy.co/amber/calendar.svg" alt="empty" width="200" class="mb-3 opacity-75">
                        <h5 class="fw-bold text-muted">Wah, belum ada event tersedia nih.</h5>
                        <p class="text-muted">Coba cek lagi nanti atau telusuri kategori lain ya!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- 4. SECTION KOTA --}}
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Temukan di Kotamu</h2>
            <p class="text-muted">Cari keseruan terdekat dari lokasimu sekarang</p>
        </div>

        @if($cities->count() > 0)
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 justify-content-center">
                @foreach($cities as $city)
                    <div class="col">
                        <a href="{{ route('kota') }}?city={{ urlencode($city->name) }}" class="text-decoration-none">
                            <div class="card city-card shadow-sm h-100 text-white">
                                {{-- Logic Image Kota --}}
                                @if($city->image_path)
                                    @php
                                        $cityImgUrl = $city->image_path
                                            ? (str_starts_with($city->image_path, 'http')
                                                ? $city->image_path
                                                : \App\Helpers\StorageHelper::url($city->image_path))
                                            : null;
                                    @endphp
                                    <img src="{{ $cityImgUrl ?? 'https://via.placeholder.com/300x200?text=' . urlencode($city->name) }}" class="card-img" alt="{{ $city->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text={{ urlencode($city->name) }}" class="card-img" alt="{{ $city->name }}">
                                @endif
                                
                                <div class="city-overlay">
                                    <h6 class="mb-0 fw-bold">{{ $city->name }}</h6>
                                    <small class="text-white-50" style="font-size: 0.8rem;">
                                        <i class="bi bi-ticket-detailed me-1"></i> {{ $city->events_count }} Event
                                    </small>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-secondary border-0 text-center py-4 rounded-3">
                <i class="bi bi-geo-alt fs-2 text-muted mb-2 d-block"></i>
                <p class="mb-0">Belum ada data kota yang tersedia saat ini.</p>
            </div>
        @endif
    </div>
</section>

@endsection