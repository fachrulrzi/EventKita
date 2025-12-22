@extends('layouts.app')

@section('title', 'EventKita - Temukan Event Hiburan Terbaik!')

@section('content')
<style>
    /* Custom Styles untuk mempercantik yang tidak tercover Bootstrap standar */
    .hero-section {
        position: relative;
        overflow: hidden;
    }
    .hero-carousel-item img {
        height: 550px;
        object-fit: cover;
        filter: brightness(60%);
    }
    .carousel-caption-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 80px 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        color: white;
        z-index: 10;
        pointer-events: none;
    }
    .carousel-caption-overlay .container {
        pointer-events: auto;
    }
    .carousel-caption-overlay a {
        pointer-events: auto;
    }
    .category-bubble img {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 3px solid transparent;
    }
    .category-bubble:hover img {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #0d6efd;
    }
    .event-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        overflow: hidden;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .city-card {
        border-radius: 12px;
        overflow: hidden;
        border: none;
    }
    .city-card img {
        transition: transform 0.5s ease;
        height: 180px;
        object-fit: cover;
    }
    .city-card:hover img {
        transform: scale(1.1);
    }
    .btn-primary-custom {
        background-color: #0d6efd;
        border: none;
        padding: 10px 25px;
        border-radius: 8px;
        font-weight: 600;
    }
    .badge-category {
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        border-radius: 5px;
        padding: 5px 10px;
    }
</style>

<section class="hero-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            @if($featuredEvents->count() > 0)
                @foreach($featuredEvents as $index => $featured)
                    <div class="carousel-item hero-carousel-item {{ $index === 0 ? 'active' : '' }}">
                        @if($featured->image_path)
                            <img src="{{ storage_url($featured->image_path) }}" class="d-block w-100" alt="{{ $featured->title }}">
                        @else
                            <img src="https://via.placeholder.com/1200x500?text={{ urlencode($featured->title) }}" class="d-block w-100" alt="{{ $featured->title }}">
                        @endif
                        <div class="carousel-caption-overlay">
                            <div class="container text-start">
                                <span class="badge bg-primary mb-2">Featured Event</span>
                                <h1 class="display-4 fw-bold">{{ $featured->title }}</h1>
                                <p class="fs-5"><i class="bi bi-calendar-event me-2"></i> {{ $featured->date->format('d M Y') }}</p>
                                <a href="{{ route('event.detail', $featured->slug) }}" class="btn btn-light btn-lg px-4 shadow-sm btn-event-detail" onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-2\'></span>Loading...'">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="carousel-item hero-carousel-item active">
                    <img src="https://nnc-media.netralnews.com/2025/08/IMG-Netral-News-Admin-68-6J5H0GLMIW.jpg" class="d-block w-100" alt="EventKita">
                    <div class="carousel-caption-overlay text-center">
                        <h1 class="display-4 fw-bold">Temukan Event Paling Seru!</h1>
                        <p class="fs-5">Semua informasi hiburan, konser, dan festival ada di sini.</p>
                    </div>
                </div>
            @endif
        </div>
        @if($featuredEvents->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        @endif
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0">Telusuri Kategori</h2>
            <hr class="flex-grow-1 ms-4 d-none d-md-block opacity-25">
        </div>
        
        <div class="row text-center g-4 justify-content-center">
            @forelse ($categories as $category)
                <div class="col-6 col-sm-4 col-md-2 category-bubble">
                    <a href="{{ route('kategori.filter', ['slug' => $category->slug]) }}" class="text-decoration-none">
                        <div class="bg-white rounded-circle d-inline-block p-1 mb-3">
                            @php
                                $placeholderIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"><rect width="100%" height="100%" fill="%23f8f9fa"/><text x="50%" y="55%" font-size="12" font-family="Arial" fill="%23adb5bd" text-anchor="middle">No Icon</text></svg>';
                            @endphp
                            <img src="{{ $category->icon_path ? storage_url($category->icon_path) : $placeholderIcon }}" 
                                 class="rounded-circle shadow-sm" alt="{{ $category->name }}" width="100" height="100" style="object-fit: cover;">
                        </div>
                        <h6 class="text-dark fw-bold">{{ $category->name }}</h6>
                    </a>
                </div>
            @empty
                <p class="text-muted">Belum ada kategori yang tersedia.</p>
            @endforelse
        </div>
    </div>
</section>

<section id="event-pilihan" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Event Pilihan Minggu Ini</h2>
            <p class="text-muted">Jangan lewatkan momen seru bersama teman dan keluarga</p>
        </div>

        <div class="row g-4">
            @forelse ($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card event-card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($event->image_path)
                                <img src="{{ storage_url($event->image_path) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 220px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/400x300?text=No+Image" class="card-img-top" alt="{{ $event->title }}" style="height: 220px; object-fit: cover;">
                            @endif
                            
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-white text-dark shadow-sm px-3 py-2">
                                    <i class="bi bi-tag-fill text-primary me-1"></i> {{ $event->category->name }}
                                </span>
                            </div>

                            @auth
                                <div class="position-absolute top-0 end-0 m-3">
                                    <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-white btn-sm rounded-circle shadow" style="width: 35px; height: 35px;">
                                            @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                                <i class="bi bi-heart-fill text-danger"></i>
                                            @else
                                                <i class="bi bi-heart"></i>
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>

                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold text-truncate">{{ $event->title }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="bi bi-calendar3 me-1"></i> {{ $event->date->format('d M Y') }}
                                @if($event->location)
                                    <span class="mx-2">|</span> <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                                @endif
                            </p>
                            <p class="card-text text-muted mb-4" style="font-size: 0.9rem;">
                                {{ Str::limit($event->description, 90) }}
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                <span class="h5 mb-0 fw-bold text-primary">{{ $event->formatted_price }}</span>
                                <div class="btn-group">
                                    @if($event->slug)
                                        <a href="{{ route('event.detail', ['slug' => $event->slug]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Detail</a>
                                    @else
                                        <span class="btn btn-outline-secondary btn-sm rounded-pill px-3 disabled">Detail</span>
                                    @endif
                                    @if($event->hasPaidTickets() && $event->slug)
                                        @auth
                                            <a href="{{ route('checkout', $event->slug) }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2" title="Beli Tiket">
                                                <i class="bi bi-ticket-perforated"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm rounded-pill px-3 ms-2" title="Login untuk Beli Tiket">
                                                <i class="bi bi-ticket-perforated"></i>
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
                    <img src="https://illustrations.popsy.co/amber/calendar.svg" alt="empty" width="200" class="mb-3">
                    <p class="text-muted">Wah, belum ada event tersedia nih. Cek lagi nanti ya!</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Temukan di Kotamu</h2>
            <p class="text-muted">Cari keseruan terdekat dari lokasimu</p>
        </div>

        @if($cities->count() > 0)
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3">
                @foreach($cities as $city)
                    <div class="col">
                        <a href="{{ route('kota') }}?city={{ urlencode($city->name) }}" class="text-decoration-none">
                            <div class="card city-card shadow-sm h-100">
                                @if($city->image_path)
                                    <img src="{{ storage_url($city->image_path) }}" class="card-img" alt="{{ $city->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text={{ urlencode($city->name) }}" class="card-img" alt="{{ $city->name }}">
                                @endif
                                <div class="card-img-overlay d-flex align-items-end p-0">
                                    <div class="w-100 bg-dark bg-opacity-50 text-white p-3 backdrop-blur" style="backdrop-filter: blur(2px);">
                                        <h6 class="mb-0 fw-bold">{{ $city->name }}</h6>
                                        <small class="opacity-75">{{ $city->events_count }} Event</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light border text-center">
                <i class="bi bi-geo-alt fs-3 text-muted"></i>
                <p class="mb-0 mt-2">Belum ada data kota yang tersedia.</p>
            </div>
        @endif
    </div>
</section>

@endsection