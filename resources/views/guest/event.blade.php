@extends('layouts.app')

@section('title', (isset($category) ? $category->name : 'Semua Kategori') . ' - EventKita')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. PAGE HEADER STYLES */
    .page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 60px 0 40px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        position: relative;
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        font-weight: 500;
        transition: color 0.2s;
    }
    .breadcrumb-item a:hover {
        color: var(--primary-color);
    }

    /* Filter Card / Dropdown Styles */
    .filter-card {
        background: #ffffff;
        border-radius: 50px; /* Pill shape container */
        border: 1px solid rgba(0,0,0,0.08);
        padding: 8px 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
    }
    .filter-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: var(--primary-color);
    }
    .form-select-custom {
        border: none;
        background-color: transparent;
        font-weight: 600;
        color: #495057;
        cursor: pointer;
        padding-right: 30px;
        box-shadow: none !important;
    }

    /* 2. CATEGORY PILLS */
    .category-scroll-container {
        overflow-x: auto;
        padding-bottom: 10px;
        scrollbar-width: none; /* Hide scrollbar FF */
    }
    .category-scroll-container::-webkit-scrollbar {
        display: none; /* Hide scrollbar Chrome */
    }
    
    .category-pill {
        transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border: 1px solid rgba(0,0,0,0.08);
        font-weight: 500;
        white-space: nowrap;
    }
    .category-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.15);
        border-color: var(--primary-color);
    }
    .category-pill.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
    .category-pill img {
        width: 20px;
        height: 20px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* 3. EVENT CARD STYLES (Sama dengan Welcome/Search) */
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
    .img-wrapper {
        position: relative;
        height: 220px;
        overflow: hidden;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .event-card:hover .img-wrapper img {
        transform: scale(1.08);
    }

    /* Floating Badge & Btn */
    .badge-float {
        position: absolute;
        bottom: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.95);
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 30px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 2;
    }
    .btn-fav-float {
        position: absolute;
        top: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(4px);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        transition: all 0.2s ease;
    }
    .btn-fav-float:hover {
        transform: scale(1.1);
        background: white;
    }
</style>

{{-- HEADER SECTION --}}
<header class="page-header">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jelajahi</li>
                    </ol>
                </nav>
                <h1 class="fw-bold display-6 mb-2 text-dark">
                    @if (request()->is('kota') && isset($cityName))
                        <span class="text-primary"><i class="bi bi-geo-alt-fill me-2"></i></span>Event di {{ $cityName }}
                    @else
                        <span class="text-primary"><i class="bi bi-grid-fill me-2"></i></span>{{ isset($category) ? $category->name : 'Semua Kategori' }}
                    @endif
                </h1>
                <p class="text-muted fs-5 mb-0" style="max-width: 600px;">
                    {{ isset($category) ? $category->description ?? 'Menampilkan semua event dalam kategori ini.' : 'Temukan berbagai event menarik berdasarkan minatmu.' }}
                </p>
            </div>
            
            {{-- Filter Kota Dropdown --}}
            @if (!request()->is('kota'))
            <div class="col-md-4">
                <div class="filter-card ms-md-auto">
                    <label for="filter-kota" class="text-muted me-2 small text-uppercase fw-bold">
                        <i class="bi bi-filter-circle text-primary fs-5 align-middle"></i>
                    </label>
                    <div class="flex-grow-1">
                        <select id="filter-kota" class="form-select form-select-custom" onchange="filterByCity(this.value)">
                            <option value="">Semua Kota</option>
                            @if(isset($cities))
                                @foreach($cities as $city)
                                    @php
                                        $isSelected = (request('city_id') == $city->id || request('city') == $city->name);
                                    @endphp
                                    <option value="{{ $city->id }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $city->name }} ({{ $city->events_count ?? 0 }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</header>

{{-- MAIN CONTENT SECTION --}}
<section class="py-5 bg-light min-vh-100">
    <div class="container">
        
        {{-- KATEGORI PILLS --}}
        @if(isset($categories) && $categories->count() && !request()->is('kategori*'))
            @php
                $placeholderIconSmall = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24'><rect width='100%' height='100%' fill='%23e9ecef'/></svg>";
                $cityParam = request('city') ? '?city=' . urlencode(request('city')) : (request('city_id') ? '?city_id=' . request('city_id') : '');
                $isKotaPage = request()->is('kota');
            @endphp
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold text-uppercase text-muted ls-1 mb-0 small">Filter Kategori</h6>
                </div>
                
                <div class="category-scroll-container d-flex gap-2 pb-2">
                    {{-- Tombol 'Semua Event' --}}
                    <a href="{{ $isKotaPage ? (route('kota') . $cityParam) : route('kategori') }}" 
                       class="btn category-pill rounded-pill px-4 py-2 {{ !isset($category) ? 'active shadow-sm' : 'bg-white' }}">
                        Semua
                    </a>
                    
                    {{-- Loop Kategori --}}
                    @foreach($categories as $cat)
                        @php
                            // Logika URL Kompleks (Dipertahankan)
                            $categoryUrl = $isKotaPage 
                                ? route('kota') . ($cityParam ? $cityParam . '&category=' . $cat->slug : '?category=' . $cat->slug)
                                : route('kategori.filter', ['slug' => $cat->slug]) . $cityParam;
                            $isActive = (isset($category) && $category->id == $cat->id);
                        @endphp
                        
                        <a href="{{ $categoryUrl }}" 
                           class="btn category-pill rounded-pill px-3 py-2 d-flex align-items-center gap-2 {{ $isActive ? 'active shadow-sm' : 'bg-white' }}">
                            @php
                                $catIconUrl = $cat->icon_path
                                    ? (str_starts_with($cat->icon_path, 'http')
                                        ? $cat->icon_path
                                        : \App\Helpers\StorageHelper::url($cat->icon_path))
                                    : $placeholderIconSmall;
                            @endphp
                            <img src="{{ $catIconUrl }}" alt="{{ $cat->name }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- EVENT GRID --}}
        <div id="event-card-container" class="row g-4">
            @forelse ($events as $event)
                <div class="col-12 col-md-6 col-lg-4 event-col-filterable"
                     data-kota="{{ $event->city }}"
                     data-tanggal="{{ $event->date->format('Y-m-d') }}">
                    
                    <div class="card event-card h-100 shadow-sm">
                        {{-- Image Wrapper --}}
                        <div class="img-wrapper">
                            @php
                                $eventImgUrl = $event->image_path
                                    ? (str_starts_with($event->image_path, 'http')
                                        ? $event->image_path
                                        : \App\Helpers\StorageHelper::url($event->image_path))
                                    : 'https://via.placeholder.com/400x300?text=No+Image';
                            @endphp
                            <img src="{{ $eventImgUrl }}" class="card-img-top" alt="{{ $event->title }}">
                            
                            {{-- Favorite Button --}}
                            @auth
                                <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-fav-float shadow-sm">
                                        @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                            <i class="bi bi-heart-fill text-danger fs-5"></i>
                                        @else
                                            <i class="bi bi-heart text-muted fs-5"></i>
                                        @endif
                                    </button>
                                </form>
                            @endauth

                            {{-- Category Badge --}}
                            <div class="badge-float shadow-sm">
                                {{ $event->category->name }}
                            </div>

                            {{-- City Badge --}}
                            @if($event->cityRelation)
                                <div class="position-absolute bottom-0 start-0 m-3">
                                    <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $event->cityRelation->name }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate mb-2 text-dark">{{ $event->title }}</h5>
                            
                            <div class="text-muted small mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-calendar3 me-2 text-primary"></i> 
                                    <span class="fw-medium">{{ $event->date->format('d M Y') }}</span>
                                </div>
                                @if($event->location)
                                    <div class="d-flex align-items-center text-truncate">
                                        <i class="bi bi-geo-alt me-2 text-danger"></i> 
                                        <span class="text-truncate">{{ $event->location }}</span>
                                    </div>
                                @endif
                            </div>

                            <p class="card-text text-secondary small mb-4 flex-grow-1" style="line-height: 1.6;">
                                {{ Str::limit($event->description, 85) }}
                            </p>
                            
                            {{-- Footer Actions --}}
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Tiket Mulai</small>
                                    <span class="fw-bold text-primary fs-5">{{ $event->formatted_price }}</span>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    {{-- Website Button --}}
                                    @if($event->website_url)
                                        <a href="{{ $event->website_url }}" target="_blank" 
                                           class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm"
                                           title="Kunjungi Website" style="width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-box-arrow-up-right small"></i>
                                        </a>
                                    @endif

                                    {{-- Detail Button --}}
                                    <a href="{{ route('event.detail', ['slug' => $event->slug]) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                                         Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-12 py-5">
                    <div class="text-center bg-white rounded-4 p-5 shadow-sm border-0">
                        <div class="mb-3">
                            <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="mt-2 fw-bold text-dark">Belum Ada Event</h4>
                        <p class="text-muted mb-4">Sepertinya belum ada event tersedia untuk kategori atau filter ini.</p>
                        <a href="{{ route('kategori') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-arrow-repeat me-2"></i> Lihat Semua Event
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- SCRIPT (Logic Dipertahankan) --}}
<script>
function filterByCity(cityId) {
    let url = new URL(window.location.href);
    url.searchParams.delete('city');
    url.searchParams.delete('city_id');
    
    if (cityId) {
        url.searchParams.set('city_id', cityId);
    }
    window.location.href = url.toString();
}
</script>
@endsection