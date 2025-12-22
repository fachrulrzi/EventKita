@extends('layouts.app')

@section('title', $event->title . ' | EventKita')
@section('body-class', 'bg-light')

@section('content')
<style>
    /* Hero Section Styling */
    .event-detail-hero {
        position: relative;
        height: 450px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: flex-end;
        padding-bottom: 50px;
        color: white;
    }
    .event-detail-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.2) 100%);
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }

    /* Card & Info Styling */
    .info-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        padding: 30px;
        margin-bottom: 25px;
    }
    .detail-info-pill {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        height: 100%;
        border-left: 4px solid #0d6efd;
    }
    .detail-tag {
        background: #e9ecef;
        color: #495057;
        padding: 6px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: 0.2s;
    }
    .detail-tag:hover {
        background: #0d6efd;
        color: white;
    }

    /* Sidebar Timeline Styling */
    .sidebar-item {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }
    .sidebar-icon {
        width: 45px;
        height: 45px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* Forum CTA Section */
    .forum-cta {
        background: linear-gradient(135deg, #0d6efd 0%, #004bb5 100%);
        color: white;
        border-radius: 20px;
        padding: 30px;
    }
    .btn-glass {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
    }
    .btn-glass:hover {
        background: white;
        color: #0d6efd;
    }
</style>

<section class="event-detail-hero" style="background-image: url('{{ storage_url($event->image_path) }}');">
    <div class="event-detail-overlay"></div>
    <div class="container hero-content">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('kategori') }}" class="text-white-50 text-decoration-none">Event</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ Str::limit($event->title, 30) }}</li>
            </ol>
        </nav>
        
        <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 shadow-sm">{{ $event->category->name }}</span>
        <h1 class="display-4 fw-bold mb-4">{{ $event->title }}</h1>
        
        <div class="d-flex flex-wrap gap-4 mb-4 opacity-75">
            <div class="d-flex align-items-center"><i class="bi bi-calendar-check me-2 fs-5 text-primary"></i> {{ $event->date->format('l, d F Y') }}</div>
            <div class="d-flex align-items-center"><i class="bi bi-clock me-2 fs-5 text-primary"></i> {{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }} WIB</div>
            <div class="d-flex align-items-center"><i class="bi bi-geo-alt me-2 fs-5 text-primary"></i> {{ $event->location }}</div>
        </div>

        <div class="d-flex flex-wrap gap-3">
            @if($event->hasPaidTickets())
                @auth
                    <a href="{{ route('checkout', $event->slug) }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow">
                        <i class="bi bi-ticket-perforated me-2"></i> Beli Tiket
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 rounded-pill shadow">
                        <i class="bi bi-ticket-perforated me-2"></i> Login untuk Beli Tiket
                    </a>
                @endauth
            @endif
            
            @if($event->website_url)
                <a href="{{ $event->website_url }}" target="_blank" class="btn btn-outline-light btn-lg px-4 rounded-pill shadow">
                    <i class="bi bi-link-45deg me-2"></i> Situs Resmi
                </a>
            @endif
            
            @auth
                <form action="{{ route('favorites.toggle', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    @if(Auth::user()->favoriteEvents()->where('event_id', $event->id)->exists())
                        <button type="submit" class="btn btn-danger btn-lg px-4 rounded-pill shadow">
                            <i class="bi bi-heart-fill me-2"></i> Hapus Favorit
                        </button>
                    @else
                        <button type="submit" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                            <i class="bi bi-heart me-2"></i> Tambah Favorit
                        </button>
                    @endif
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 rounded-pill">
                    <i class="bi bi-heart me-2"></i> Tambah Favorit
                </a>
            @endauth
            
            <button class="btn btn-glass btn-lg px-4 rounded-pill">
                <i class="bi bi-share me-2"></i> Bagikan
            </button>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="info-card">
                    <h4 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="bg-primary p-1 rounded-3 me-2" style="width: 8px; height: 30px;"></span>
                        Tentang Event
                    </h4>
                    <p class="text-muted lh-lg" style="font-size: 1.05rem;">
                        {{ $event->description }}
                    </p>
                    
                    @if($event->tags)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-muted small text-uppercase mb-3">Tags Terkait</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach (explode(',', $event->tags) as $tag)
                                    <span class="detail-tag">#{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if($event->ticketCategories->isNotEmpty())
                <div class="info-card">
                    <h4 class="fw-bold mb-4 d-flex align-items-center">
                        <span class="bg-primary p-1 rounded-3 me-2" style="width: 8px; height: 30px;"></span>
                        Kategori Tiket & Harga
                    </h4>
                    <div class="row g-3">
                        @foreach($event->ticketCategories as $ticket)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">{{ $ticket->category_name }}</h5>
                                            @if($ticket->description)
                                                <p class="text-muted small mb-0">{{ $ticket->description }}</p>
                                            @endif
                                        </div>
                                        @if($ticket->stock)
                                            <span class="badge bg-primary-subtle text-primary">{{ $ticket->remaining_stock }} tersisa</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <span class="h5 mb-0 fw-bold text-primary">{{ $ticket->formatted_price }}</span>
                                        @if($ticket->isAvailable())
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                                        @else
                                            <span class="badge bg-danger">Sold Out</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="info-card">
                    <h4 class="fw-bold mb-4">Informasi Penting</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-info-pill">
                                <p class="text-muted small fw-bold text-uppercase mb-1">Lokasi Detail</p>
                                <p class="fw-semibold mb-0"><i class="bi bi-geo-alt-fill text-primary me-1"></i> {{ $event->location }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill text-center">
                                <p class="text-muted small fw-bold text-uppercase mb-1">Harga</p>
                                <p class="fw-bold text-primary mb-0">{{ $event->formatted_price }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill text-center">
                                <p class="text-muted small fw-bold text-uppercase mb-1">Waktu</p>
                                <p class="fw-semibold mb-0">{{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="forum-cta shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">Punya pertanyaan seputar event?</h4>
                            <p class="mb-0 opacity-75">Bergabunglah di forum diskusi dan tanyakan langsung pada komunitas.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            @auth
                                <a href="{{ route('forum.event', $event->slug) }}" class="btn btn-white btn-lg rounded-pill px-4 fw-bold text-primary bg-white" target="_blank">
                                    Buka Forum
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

            <div class="col-lg-4">
                <div class="info-card position-sticky" style="top: 100px;">
                    <h5 class="fw-bold mb-4">Ringkasan Event</h5>
                    
                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-calendar3"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Tanggal Pelaksanaan</p>
                            <p class="fw-bold mb-0">{{ $event->date->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-clock"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Waktu Acara</p>
                            <p class="fw-bold mb-0">{{ date('H:i', strtotime($event->time_start)) }} - Selesai</p>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-geo"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Kota</p>
                            <p class="fw-bold mb-0">{{ $event->city ?? 'Lokasi Terdaftar' }}</p>
                        </div>
                    </div>

                    <div class="sidebar-item">
                        <div class="sidebar-icon"><i class="bi bi-collection"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Kategori</p>
                            <p class="fw-bold mb-0 text-primary">{{ $event->category->name }}</p>
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="text-center p-3 bg-light rounded-4">
                        <h6 class="fw-bold mb-2 small">Butuh Bantuan Kerjasama?</h6>
                        <p class="text-muted small mb-3">Klik tombol di bawah untuk chat via WhatsApp dengan tim kami.</p>
                        <a href="https://wa.me/6285884653526" target="_blank" class="btn btn-success w-100 rounded-pill py-2">
                            <i class="bi bi-whatsapp me-2"></i> Chat Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection