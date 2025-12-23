@extends('layouts.app')

@section('title', $event->title . ' | EventKita')
@section('body-class', 'bg-light')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --border-radius-xl: 1.5rem;
    }

    /* 1. HERO SECTION */
    .event-detail-hero {
        position: relative;
        min-height: 500px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed; /* Parallax effect */
        display: flex;
        align-items: flex-end;
        padding-bottom: 60px;
        margin-top: -1.5rem; /* Menarik ke atas navbar jika transparan */
    }
    .event-detail-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.6) 40%, rgba(0,0,0,0.3) 100%);
        backdrop-filter: blur(2px);
    }
    .hero-content {
        position: relative;
        z-index: 2;
        color: white;
    }

    .hero-content h1,
    .hero-content p,
    .hero-content span,
    .hero-content .text-white {
        color: white !important;
    }

    .hero-content .text-white-50 {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    /* 2. CARDS & LAYOUT */
    .info-card {
        background: white;
        border-radius: var(--border-radius-xl);
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        padding: 35px;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }
    
    /* Sticky Sidebar */
    .sidebar-sticky {
        position: sticky;
        top: 100px; /* Jarak dari atas saat discroll */
        z-index: 10;
    }
    
    .sidebar-item {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px dashed rgba(0,0,0,0.1);
    }
    .sidebar-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .sidebar-icon {
        width: 48px;
        height: 48px;
        background: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* 3. TICKET CARDS */
    .ticket-card {
        border: 2px solid transparent;
        background: #f8f9fa;
        transition: all 0.2s ease;
        border-radius: 1rem;
    }
    .ticket-card:hover {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.1);
        transform: translateY(-2px);
    }
    
    /* 4. DETAIL PILLS & TAGS */
    .detail-info-pill {
        background: #f8f9fa;
        border-radius: 1rem;
        padding: 20px;
        height: 100%;
        border-left: 5px solid var(--primary-color);
    }
    .detail-tag {
        display: inline-block;
        background: #e9ecef;
        color: #495057;
        padding: 8px 18px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: 0.2s;
        text-decoration: none;
    }
    .detail-tag:hover {
        background: var(--primary-color);
        color: white;
    }

    /* 5. FORUM CTA */
    .forum-cta {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        color: white;
        border-radius: var(--border-radius-xl);
        padding: 40px;
        position: relative;
        overflow: hidden;
    }
    .forum-cta::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    /* Buttons */
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        transition: all 0.2s;
    }
    .btn-glass:hover {
        background: white;
        color: var(--primary-color);
    }
    .share-btn:hover {
        background: #f1f3f5 !important;
        transform: translateY(-3px);
    }
</style>

{{-- HERO SECTION --}}
@php
    $heroImgUrl = $event->image_path
        ? (str_starts_with($event->image_path, 'http')
            ? $event->image_path
            : \App\Helpers\StorageHelper::url($event->image_path))
        : 'https://via.placeholder.com/1200x500?text=EventKita';
@endphp
<section class="event-detail-hero" style="background-image: url('{{ $heroImgUrl }}');">
    <div class="event-detail-overlay"></div>
    <div class="container hero-content">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none hover-white">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategori') }}" class="text-white-50 text-decoration-none hover-white">Event</a></li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">{{ Str::limit($event->title, 40) }}</li>
            </ol>
        </nav>
        
        <div class="row align-items-end">
            <div class="col-lg-8">
                <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 shadow-sm text-uppercase ls-1">
                    {{ $event->category->name }}
                </span>
                <h1 class="display-4 fw-bold mb-3 text-shadow">{{ $event->title }}</h1>
                
                <div class="d-flex flex-wrap gap-4 mb-4 text-white-50 fs-5">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2 text-warning"></i> 
                        <span class="text-white">{{ $event->date->format('l, d F Y') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-geo-alt me-2 text-warning"></i> 
                        <span class="text-white">{{ $event->location }}</span>
                        @if($event->cityRelation)
                            <span class="badge bg-dark bg-opacity-75 text-white ms-2 px-2 py-1 rounded-pill" style="font-size: 0.95em;">
                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $event->cityRelation->name }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3">
                    {{-- Tombol Beli / Login --}}
                    @if($event->hasPaidTickets())
                        @auth
                            <a href="{{ route('checkout', $event->slug) }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg fw-bold">
                                Beli Tiket <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg fw-bold">
                                Login Beli Tiket <i class="bi bi-box-arrow-in-right ms-2"></i>
                            </a>
                        @endauth
                    @endif
                    
                    {{-- Tombol Website --}}
                    @if($event->website_url)
                        <a href="{{ $event->website_url }}" target="_blank" class="btn btn-glass btn-lg px-4 rounded-pill">
                            <i class="bi bi-globe me-2"></i> Situs Resmi
                        </a>
                    @endif
                    
                    {{-- Tombol Favorit --}}
                    @auth
                        <form action="{{ route('favorites.toggle', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                                <button type="submit" class="btn btn-danger btn-lg px-4 rounded-pill shadow-sm" title="Hapus Favorit">
                                    <i class="bi bi-heart-fill me-2"></i> Disimpan
                                </button>
                            @else
                                <button type="submit" class="btn btn-outline-light btn-lg px-4 rounded-pill" title="Simpan Event">
                                    <i class="bi bi-heart me-2"></i> Simpan
                                </button>
                            @endif
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            <i class="bi bi-heart me-2"></i> Simpan
                        </a>
                    @endauth
                    
                    {{-- Tombol Share --}}
                    <button class="btn btn-glass btn-lg px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#shareModal">
                        <i class="bi bi-share-fill"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SHARE MODAL --}}
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Bagikan Event Seru Ini!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Ajak temanmu untuk bergabung dan seru-seruan bareng.</p>
                
                <div class="row g-3 mb-4 text-center">
                    <div class="col-3">
                        <a href="https://wa.me/?text={{ urlencode($event->title . ' - Yuk ikutan event ini! ' . url()->current()) }}" target="_blank" class="btn btn-light w-100 py-3 rounded-4 share-btn h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-whatsapp fs-3 text-success mb-1"></i>
                            <span class="small text-muted">WhatsApp</span>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-light w-100 py-3 rounded-4 share-btn h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-twitter-x fs-3 text-dark mb-1"></i>
                            <span class="small text-muted">X</span>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-light w-100 py-3 rounded-4 share-btn h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-facebook fs-3 text-primary mb-1"></i>
                            <span class="small text-muted">Facebook</span>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->title) }}" target="_blank" class="btn btn-light w-100 py-3 rounded-4 share-btn h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="bi bi-telegram fs-3 text-info mb-1"></i>
                            <span class="small text-muted">Telegram</span>
                        </a>
                    </div>
                </div>
                
                <div class="input-group bg-light rounded-pill p-1 border">
                    <input type="text" class="form-control border-0 bg-transparent ps-3" id="shareUrl" value="{{ url()->current() }}" readonly>
                    <button class="btn btn-primary rounded-pill px-4 fw-bold" type="button" id="copyLinkBtn" onclick="copyShareLink()">
                        Salin
                    </button>
                </div>
                <div id="copyFeedback" class="text-center mt-2 small fw-bold"></div>
            </div>
        </div>
    </div>
</div>

{{-- MAIN CONTENT --}}
<section class="py-5">
    <div class="container">
        <div class="row g-5">
            {{-- KOLOM KIRI: Deskripsi & Tiket --}}
            <div class="col-lg-8">
                {{-- Deskripsi Card --}}
                <div class="info-card">
                    <h4 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="bg-primary rounded-pill me-3" style="width: 6px; height: 30px;"></span>
                        Tentang Event
                    </h4>
                    <article class="text-muted lh-lg" style="font-size: 1.05rem; white-space: pre-line;">
                        {{ $event->description }}
                    </article>
                    
                    @if($event->tags)
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold text-uppercase text-muted small mb-3 ls-1">Tags Terkait</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (explode(',', $event->tags) as $tag)
                                    <span class="detail-tag">#{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Tiket Section --}}
                @if($event->ticketCategories->isNotEmpty())
                <div class="info-card bg-light border-0">
                    <h4 class="fw-bold mb-4 d-flex align-items-center text-dark">
                        <span class="bg-primary rounded-pill me-3" style="width: 6px; height: 30px;"></span>
                        Pilih Tiketmu
                    </h4>
                    <div class="row g-3">
                        @foreach($event->ticketCategories as $ticket)
                        <div class="col-md-6">
                            <div class="card ticket-card h-100 p-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold mb-1 text-dark">{{ $ticket->category_name }}</h5>
                                        @if($ticket->isAvailable())
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Tersedia</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Habis</span>
                                        @endif
                                    </div>
                                    
                                    @if($ticket->description)
                                        <p class="text-muted small mb-3">{{ $ticket->description }}</p>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-end mt-auto pt-3 border-top border-light">
                                        <div>
                                            @if($ticket->stock)
                                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem;">Sisa: {{ $ticket->remaining_stock }}</small>
                                            @endif
                                            <span class="h5 mb-0 fw-bold text-primary">{{ $ticket->formatted_price }}</span>
                                        </div>
                                        <i class="bi bi-ticket-perforated fs-4 text-secondary opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quick Info Pills --}}
                <div class="info-card p-0 overflow-hidden border-0 bg-transparent shadow-none mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-info-pill">
                                <p class="text-muted small fw-bold text-uppercase mb-2 ls-1">Lokasi Lengkap</p>
                                <p class="fw-semibold mb-0 fs-5">
                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> {{ $event->location }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill text-center border-start-0 border-top border-3 border-primary h-100 bg-white">
                                <p class="text-muted small fw-bold text-uppercase mb-2 ls-1">Harga Mulai</p>
                                <p class="fw-bold text-primary mb-0 fs-5">{{ $event->formatted_price }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill text-center border-start-0 border-top border-3 border-primary h-100 bg-white">
                                <p class="text-muted small fw-bold text-uppercase mb-2 ls-1">Durasi</p>
                                <p class="fw-bold text-dark mb-0 fs-5">
                                    {{ date('H:i', strtotime($event->time_start)) }} - Selesai
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Forum CTA --}}
                <div class="forum-cta shadow">
                    <div class="row align-items-center position-relative z-1">
                        <div class="col-md-8 text-center text-md-start">
                            <h3 class="fw-bold mb-2">Ada pertanyaan?</h3>
                            <p class="mb-0 text-white-50 fs-5">Gabung diskusi dengan peserta lain di Forum.</p>
                        </div>
                        <div class="col-md-4 text-center text-md-end mt-4 mt-md-0">
                            @auth
                                <a href="{{ route('forum.event', $event->slug) }}" class="btn btn-light btn-lg rounded-pill px-4 fw-bold text-primary shadow-sm" target="_blank">
                                    <i class="bi bi-chat-dots me-2"></i> Buka Forum
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-glass btn-lg rounded-pill px-4 fw-bold">
                                    Login Diskusi
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Sidebar Sticky --}}
            <div class="col-lg-4">
                <div class="info-card sidebar-sticky">
                    <h5 class="fw-bold mb-4 pb-2 border-bottom">Ringkasan Event</h5>
                    
                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-calendar-check"></i></div>
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Tanggal</p>
                            <p class="fw-bold text-dark mb-0 fs-5">{{ $event->date->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Waktu</p>
                            <p class="fw-bold text-dark mb-0 fs-5">
                                {{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }}
                            </p>
                            <small class="text-muted">WIB (Waktu Indonesia Barat)</small>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-building"></i></div>
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Kota</p>
                            <p class="fw-bold text-dark mb-0 fs-5">{{ $event->city ?? 'Lokasi Terdaftar' }}</p>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-tags"></i></div>
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                {{ $event->category->name }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-top text-center">
                        <p class="text-muted small mb-3">Butuh bantuan atau kerjasama?</p>
                        <a href="https://wa.me/62811547999" target="_blank" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">
                            <i class="bi bi-whatsapp me-2"></i> Hubungi Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- JAVASCRIPT --}}
<script>
function copyShareLink() {
    const shareUrl = document.getElementById('shareUrl');
    shareUrl.select();
    shareUrl.setSelectionRange(0, 99999); // Untuk mobile
    navigator.clipboard.writeText(shareUrl.value);
    
    const feedback = document.getElementById('copyFeedback');
    const copyBtn = document.getElementById('copyLinkBtn');
    
    feedback.innerHTML = '<span class="text-success"><i class="bi bi-check-circle me-1"></i> Link berhasil disalin!</span>';
    copyBtn.innerHTML = '<i class="bi bi-check"></i>';
    copyBtn.classList.replace('btn-primary', 'btn-success');
    
    setTimeout(() => {
        feedback.innerHTML = '';
        copyBtn.innerHTML = 'Salin';
        copyBtn.classList.replace('btn-success', 'btn-primary');
    }, 3000);
}
</script>
@endsection