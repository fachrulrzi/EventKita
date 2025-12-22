@extends('layouts.app')

@section('title', (isset($category) ? $category->name : 'Semua Kategori') . ' - EventKita')

@section('content')
<style>
    /* Styling khusus untuk filter dan kategori */
    .filter-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .category-pill {
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .category-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.15);
    }
    .category-pill img {
        width: 24px;
        height: 24px;
        object-fit: cover;
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
    .img-wrapper {
        position: relative;
        height: 200px;
        overflow: hidden;
        border-radius: 15px 15px 0 0;
    }
    .img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .price-tag {
        font-weight: 700;
        color: #0d6efd;
        font-size: 1.1rem;
    }
    .page-header {
        background: white;
        padding: 40px 0;
        border-bottom: 1px solid #eee;
        margin-bottom: 40px;
    }
</style>

<header class="page-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active">Jelajahi</li>
                    </ol>
                </nav>
                <h1 class="fw-bold mb-0">
                    @if (request()->is('kota') && isset($cityName))
                        <i class="bi bi-geo-alt text-primary me-2"></i>Event di {{ $cityName }}
                    @else
                        <i class="bi bi-grid-fill text-primary me-2"></i>{{ isset($category) ? $category->name : 'Semua Kategori' }}
                    @endif
                </h1>
                <p class="text-muted mt-2">
                    {{ isset($category) ? $category->description ?? 'Menampilkan semua event dalam kategori ini.' : 'Temukan berbagai event menarik berdasarkan minatmu.' }}
                </p>
            </div>
            
            @if (!request()->is('kota'))
            <div class="col-md-4">
                <div class="filter-card p-3 shadow-sm">
                    <label for="filter-kota" class="form-label small fw-bold text-uppercase text-muted">
                        <i class="bi bi-filter me-1"></i> Pilih Lokasi
                    </label>
                    <select id="filter-kota" class="form-select border-0 bg-light" onchange="filterByCity(this.value)">
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
            @endif
        </div>
    </div>
</header>

<section class="pb-5">
    <div class="container">
        
        @if(isset($categories) && $categories->count() && !request()->is('kategori*'))
            @php
                $placeholderIconSmall = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24'><rect width='100%' height='100%' fill='%23e9ecef'/></svg>";
                $cityParam = request('city') ? '?city=' . urlencode(request('city')) : (request('city_id') ? '?city_id=' . request('city_id') : '');
                $isKotaPage = request()->is('kota');
            @endphp
            <div class="mb-5">
                <h5 class="fw-bold mb-3 small text-uppercase text-muted">Filter Kategori</h5>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ $isKotaPage ? (route('kota') . $cityParam) : route('kategori') }}" 
                       class="btn {{ !isset($category) ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 category-pill">
                        Semua Event
                    </a>
                    @foreach($categories as $cat)
                        @php
                            $categoryUrl = $isKotaPage 
                                ? route('kota') . ($cityParam ? $cityParam . '&category=' . $cat->slug : '?category=' . $cat->slug)
                                : route('kategori.filter', ['slug' => $cat->slug]) . $cityParam;
                            $isActive = (isset($category) && $category->id == $cat->id);
                        @endphp
                        <a href="{{ $categoryUrl }}" 
                           class="btn {{ $isActive ? 'btn-primary' : 'btn-white bg-white border' }} rounded-pill px-3 d-flex align-items-center gap-2 category-pill shadow-sm">
                            @php
                                $catIconUrl = $cat->icon_path
                                    ? (str_starts_with($cat->icon_path, 'http')
                                        ? $cat->icon_path
                                        : \App\Helpers\StorageHelper::url($cat->icon_path))
                                    : $placeholderIconSmall;
                            @endphp
                            <img src="{{ $catIconUrl }}" 
                                 alt="{{ $cat->name }}" class="rounded-circle">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div id="event-card-container" class="row g-4">
            @forelse ($events as $event)
                <div class="col-12 col-md-6 col-lg-4 event-col-filterable"
                     data-kota="{{ $event->city }}"
                     data-tanggal="{{ $event->date->format('Y-m-d') }}">
                    <div class="card event-card h-100 shadow-sm border-0">
                        <div class="img-wrapper">
                            @php
                                $eventImgUrl = $event->image_path
                                    ? (str_starts_with($event->image_path, 'http')
                                        ? $event->image_path
                                        : \App\Helpers\StorageHelper::url($event->image_path))
                                    : 'https://via.placeholder.com/400x300?text=No+Image';
                            @endphp
                            <img src="{{ $eventImgUrl }}" class="card-img-top" alt="{{ $event->title }}">
                            <div class="position-absolute top-0 end-0 p-3">
                                @auth
                                    <form action="{{ route('favorites.toggle', $event->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-white btn-sm rounded-circle shadow" style="width: 35px; height: 35px;">
                                            <i class="bi {{ Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists() ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill small">
                                    {{ $event->category->name }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            <h5 class="card-title fw-bold text-truncate mb-2">{{ $event->title }}</h5>
                            <div class="text-muted small mb-3">
                                <div class="mb-1"><i class="bi bi-calendar3 me-2 text-primary"></i> {{ $event->date->format('d M Y') }}</div>
                                @if($event->location)
                                    <div class="text-truncate"><i class="bi bi-geo-alt me-2 text-primary"></i> {{ $event->location }}</div>
                                @endif
                            </div>
                            <p class="card-text text-muted small mb-4">
                                {{ Str::limit($event->description, 85) }}
                            </p>
                            
                            <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                                <span class="price-tag">{{ $event->formatted_price }}</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('event.detail', ['slug' => $event->slug]) }}"
                                       class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Detail
                                    </a>
                                    @if($event->website_url)
                                        <a href="{{ $event->website_url }}" target="_blank" class="btn btn-sm btn-primary rounded-circle">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5">
                    <div class="text-center bg-white rounded-4 border p-5 shadow-sm">
                        <i class="bi bi-emoji-frown display-1 text-muted opacity-25"></i>
                        <h4 class="mt-4 fw-bold">Belum Ada Event</h4>
                        <p class="text-muted">Wah, sepertinya belum ada event tersedia untuk pilihan ini.</p>
                        <a href="{{ route('kategori') }}" class="btn btn-primary rounded-pill px-4">Lihat Semua Event</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

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