@extends('layouts.app')

@section('title', 'Pencarian Event - EventKita')

@section('content')
<style>
    /* Custom Styles untuk konsistensi tema */
    .search-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 40px 20px;
        margin-bottom: 40px;
    }
    .search-input-group {
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border-radius: 15px;
        overflow: hidden;
    }
    .search-input-group .form-control {
        border: none;
        padding: 15px 25px;
    }
    .search-input-group .btn {
        padding: 0 30px;
        font-weight: 600;
    }
    .event-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    .card-img-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }
    .card-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .favorite-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        z-index: 10;
    }
    .category-badge {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        color: #0d6efd;
        font-weight: 700;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 50px;
        backdrop-filter: blur(4px);
    }
    .tag-link {
        text-decoration: none;
        font-size: 0.8rem;
        color: #6c757d;
        transition: color 0.2s;
    }
    .tag-link:hover {
        color: #0d6efd;
    }
</style>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <a href="{{ url('/') }}" class="btn btn-link text-decoration-none text-muted p-0">
                <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali ke Beranda
            </a>
        </nav>

        <div class="search-section text-center">
            <h2 class="fw-bold mb-4">
                @if($query)
                    <span class="text-muted">Hasil untuk:</span> "{{ $query }}"
                @else
                    <i class="bi bi-search me-2 text-primary"></i> Temukan Event Impianmu
                @endif
            </h2>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group search-input-group">
                            <input type="text" class="form-control" name="q" 
                                   placeholder="Cari konser, workshop, atau festival..." 
                                   value="{{ $query ?? '' }}" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                    
                    @if($query)
                        <div class="mt-3">
                            <span class="badge bg-white text-dark shadow-sm py-2 px-3">
                                <i class="bi bi-filter-circle me-1 text-primary"></i> 
                                Ditemukan <strong>{{ $events->count() }}</strong> Event Terkait
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div id="event-card-container" class="row g-4">
            @if($query && $events->count() > 0)
                @foreach ($events as $event)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card event-card h-100 shadow-sm">
                            <div class="card-img-container">
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
                                
                                <div class="category-badge shadow-sm">
                                    {{ $event->category->name }}
                                </div>

                                @auth
                                    <div class="favorite-btn">
                                        <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-white btn-sm rounded-circle shadow" style="width: 38px; height: 38px;">
                                                @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                                    <i class="bi bi-heart-fill text-danger"></i>
                                                @else
                                                    <i class="bi bi-heart text-muted"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>

                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="card-title fw-bold mb-3">{{ $event->title }}</h5>
                                
                                <div class="mb-3 text-muted small">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-calendar-check me-2 text-primary"></i>
                                        {{ $event->date->format('d M Y') }}
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt me-2 text-primary"></i>
                                        @if($event->cityRelation)
                                            {{ $event->cityRelation->name }}
                                        @else
                                            {{ $event->location ?? 'Lokasi Belum Ditentukan' }}
                                        @endif
                                    </div>
                                </div>

                                <p class="card-text text-muted mb-4 small">
                                    {{ Str::limit($event->description, 90) }}
                                </p>

                                @if($event->tags)
                                    <div class="mb-4">
                                        @foreach(explode(',', $event->tags) as $tag)
                                            <a href="#" class="tag-link me-2">#{{ trim($tag) }}</a>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary h5 mb-0">{{ $event->formatted_price }}</span>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('event.detail', ['slug' => $event->slug]) }}"
                                           class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                           Detail
                                        </a>
                                        @if($event->website_url)
                                            <a href="{{ $event->website_url }}" target="_blank"
                                               class="btn btn-sm btn-primary rounded-circle">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif($query)
                <div class="col-12 text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-search-heart text-muted" style="font-size: 5rem; opacity: 0.3;"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Yah, event tidak ditemukan...</h4>
                    <p class="text-muted">Coba gunakan kata kunci lain atau jelajahi kategori yang tersedia.</p>
                    <a href="{{ route('kategori') }}" class="btn btn-primary mt-3 px-4 rounded-pill shadow-sm">
                        Lihat Semua Kategori
                    </a>
                </div>
            @else
                <div class="col-12 text-center py-5">
                    <div class="p-5 border rounded-4 bg-light shadow-sm d-inline-block w-100">
                        <i class="bi bi-binoculars text-primary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold">Mulai Pencarianmu!</h4>
                        <p class="text-muted">Tuliskan nama event atau lokasi yang ingin kamu kunjungi di kolom atas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection