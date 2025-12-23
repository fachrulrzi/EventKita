@extends('layouts.app')

@section('title', 'Pencarian Event - EventKita')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --border-radius-lg: 1rem;
    }

    /* 1. SEARCH SECTION HERO */
    .search-hero-section {
        background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
        padding: 60px 0 40px;
        position: relative;
    }
    
    .search-card {
        background: white;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
    }

    /* Input Search yang lebih "Chunky" dan Modern */
    .search-input-group {
        border-radius: 50px;
        background: #f8f9fa;
        padding: 5px;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    .search-input-group:focus-within {
        background: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
    }
    .search-input-group .form-control {
        border: none;
        background: transparent;
        padding: 15px 25px;
        font-size: 1.1rem;
        box-shadow: none;
    }
    .search-input-group .btn-search {
        border-radius: 50px;
        padding: 12px 35px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .search-input-group .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }

    /* 2. EVENT CARD STYLING (Konsisten dengan Welcome) */
    .event-card {
        border: none;
        border-radius: var(--border-radius-lg);
        background: #fff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
        height: 100%;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    
    .card-img-container {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .event-card:hover .card-img-container img {
        transform: scale(1.08);
    }

    /* Floating Elements */
    .favorite-btn-wrapper {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }
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

    .category-badge-float {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(13, 110, 253, 0.9);
        color: white;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 6px 14px;
        border-radius: 30px;
        backdrop-filter: blur(4px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        z-index: 9;
    }

    /* 3. TAGS STYLING (Chips) */
    .tag-pill {
        display: inline-block;
        text-decoration: none;
        font-size: 0.75rem;
        color: #6c757d;
        background-color: #f1f3f5;
        padding: 4px 12px;
        border-radius: 20px;
        transition: all 0.2s;
        margin-right: 5px;
        margin-bottom: 5px;
        font-weight: 500;
    }
    .tag-pill:hover {
        background-color: #e7f1ff;
        color: var(--primary-color);
    }

    /* Breadcrumb Button */
    .btn-back-custom {
        color: #6c757d;
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 10px;
        transition: all 0.2s;
        background: transparent;
    }
    .btn-back-custom:hover {
        background: #e9ecef;
        color: #212529;
    }
</style>

<section class="min-vh-100 bg-light">
    
    {{-- SEARCH HERO SECTION --}}
    <div class="search-hero-section">
        <div class="container">
            {{-- Navigasi Kembali --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <a href="{{ url('/') }}" class="btn btn-back-custom text-decoration-none">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
                </a>
            </nav>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-8">
                    <div class="search-card">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold display-6 mb-2">
                                @if($query)
                                    Hasil pencarian: <span class="text-primary">"{{ $query }}"</span>
                                @else
                                    <span class="text-primary">Temukan</span> Event Impianmu
                                @endif
                            </h2>
                            @if(!$query)
                                <p class="text-muted">Cari konser, workshop, festival, atau pameran menarik lainnya</p>
                            @endif
                        </div>

                        {{-- Form Pencarian --}}
                        <form action="{{ route('search') }}" method="GET">
                            <div class="input-group search-input-group">
                                <span class="input-group-text bg-transparent border-0 ps-4 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="q" 
                                       placeholder="Ketik nama event, artis, atau lokasi..." 
                                       value="{{ $query ?? '' }}" required>
                                <button class="btn btn-primary btn-search" type="submit">
                                    Cari Event
                                </button>
                            </div>
                        </form>

                        {{-- Result Count Badge --}}
                        @if($query)
                            <div class="text-center mt-4">
                                <span class="badge bg-light text-dark border shadow-sm py-2 px-3 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1 text-success"></i> 
                                    Ditemukan <strong>{{ $events->count() }}</strong> event terkait
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RESULT GRID SECTION --}}
    <div class="container py-5">
        <div id="event-card-container" class="row g-4">
            @if($query && $events->count() > 0)
                @foreach ($events as $event)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card event-card h-100 shadow-sm">
                            
                            {{-- Image Container --}}
                            <div class="card-img-container">
                                {{-- Logic Image --}}
                                @if($event->image_path)
                                    @php
                                        $searchImgUrl = $event->image_path
                                            ? (str_starts_with($event->image_path, 'http')
                                                ? $event->image_path
                                                : \App\Helpers\StorageHelper::url($event->image_path))
                                            : null;
                                    @endphp
                                    <img src="{{ $searchImgUrl ?? 'https://via.placeholder.com/400x300?text=EventKita' }}" alt="{{ $event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/400x300?text=EventKita" alt="{{ $event->title }}">
                                @endif
                                
                                {{-- Floating Category Badge --}}
                                <div class="category-badge-float">
                                    {{ $event->category->name }}
                                </div>

                                {{-- Floating Favorite Button --}}
                                @auth
                                    <div class="favorite-btn-wrapper">
                                        <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-fav-float shadow-sm" title="Simpan ke Favorit">
                                                @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                                    <i class="bi bi-heart-fill text-danger"></i>
                                                @else
                                                    <i class="bi bi-heart text-secondary"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="card-title fw-bold mb-3 text-dark">{{ $event->title }}</h5>
                                
                                <div class="mb-3 text-muted small">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                                        <span class="fw-medium">{{ $event->date->format('d M Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center text-truncate">
                                        <i class="bi bi-geo-alt me-2 text-danger"></i>
                                        <span class="text-truncate">
                                            @if($event->cityRelation)
                                                {{ $event->cityRelation->name }}
                                            @else
                                                {{ $event->location ?? 'Lokasi Belum Ditentukan' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <p class="card-text text-secondary mb-4 small" style="line-height: 1.6;">
                                    {{ Str::limit($event->description, 90) }}
                                </p>

                                {{-- Tags Section (Styled as Pills) --}}
                                @if($event->tags)
                                    <div class="mb-4">
                                        @foreach(explode(',', $event->tags) as $tag)
                                            <a href="#" class="tag-pill">#{{ trim($tag) }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Footer Action --}}
                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block" style="font-size: 0.7rem;">Mulai dari</small>
                                        <span class="fw-bold text-primary h5 mb-0">{{ $event->formatted_price }}</span>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        {{-- Tombol Website External --}}
                                        @if($event->website_url)
                                            <a href="{{ $event->website_url }}" target="_blank"
                                               class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm"
                                               title="Kunjungi Website" style="width: 32px; height: 32px; padding: 0; line-height: 30px; text-align: center;">
                                                <i class="bi bi-box-arrow-up-right small"></i>
                                            </a>
                                        @endif

                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('event.detail', ['slug' => $event->slug]) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-4 fw-semibold">
                                           Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            
            {{-- EMPTY STATE: Not Found --}}
            @elseif($query)
                <div class="col-12 text-center py-5">
                    <div class="py-5">
                        <div class="mb-3">
                            <i class="bi bi-journal-x text-muted opacity-25" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">Yah, event tidak ditemukan...</h4>
                        <p class="text-muted mb-4">Mungkin coba kata kunci lain atau cek kategori kami?</p>
                        <a href="{{ route('kategori') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            Lihat Semua Kategori
                        </a>
                    </div>
                </div>

            {{-- EMPTY STATE: Start Search --}}
            @else
                <div class="col-12 text-center py-5">
                    <div class="p-5 border-0 rounded-4 bg-white shadow-sm d-inline-block mw-100" style="max-width: 500px;">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/searching-concept-illustration-download-in-svg-png-gif-file-formats--search-magnifying-glass-gesture-business-pack-illustrations-3694380.png" 
                             alt="Start Searching" style="width: 150px; opacity: 0.8;" class="mb-3">
                        <h4 class="fw-bold">Siap Menjelajah?</h4>
                        <p class="text-muted">Gunakan kolom pencarian di atas untuk menemukan event seru di sekitarmu.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection