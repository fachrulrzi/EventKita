@extends('layouts.app')

@section('title', 'Dashboard Anggota - EventKita')

@section('content')
<style>
    /* Custom Styling untuk Dashboard */
    .dashboard-header {
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        padding: 60px 0;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    .avatar-wrapper {
        width: 100px;
        height: 100px;
        background: white;
        padding: 5px;
        border-radius: 50%;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: inline-block;
    }
    .avatar-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    .stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .nav-pills-dashboard {
        background: white;
        padding: 8px;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        display: inline-flex;
    }
    .nav-pills-dashboard .nav-link {
        border-radius: 50px;
        padding: 10px 25px;
        font-weight: 600;
        color: #6c757d;
        transition: 0.3s;
    }
    .nav-pills-dashboard .nav-link.active {
        background-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    .event-card-fav {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s;
    }
    .event-card-fav:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .profile-form-card {
        border: none;
        border-radius: 20px;
        background: white;
    }
    .form-control-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 15px;
        border-radius: 10px;
    }
    .form-control-custom:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .btn-brand-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        transition: 0.3s;
    }
    .btn-brand-gradient:hover {
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        color: white;
        transform: scale(1.02);
    }
</style>

<div class="bg-light min-vh-100">
    {{-- Header Section --}}
    <header class="dashboard-header text-center">
        <div class="container">
            <div class="avatar-wrapper mb-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff&size=128" 
                     alt="User Avatar">
            </div>
            <h2 class="fw-bold mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-muted mb-4">Senang melihatmu kembali. Cek event pilihanmu di sini.</p>

            {{-- Ringkasan Statistik --}}
            <div class="row justify-content-center g-3">
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <h3 class="fw-bold text-primary mb-0">{{ $favoriteEvents->count() }}</h3>
                            <small class="text-muted fw-semibold">Favorit</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <h3 class="fw-bold text-success mb-0">{{ Auth::user()->orders()->where('status', 'paid')->count() }}</h3>
                            <small class="text-muted fw-semibold">Tiket Dibeli</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="card stat-card shadow-sm h-100">
                        <div class="card-body">
                            <i class="bi bi-shield-check text-info fs-4"></i>
                            <div class="text-muted small fw-semibold">Member Aktif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            {{-- Navigasi Tab --}}
            <div class="text-center mb-5">
                <ul class="nav nav-pills nav-pills-dashboard" id="dashboardTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="favorit-tab" data-bs-toggle="pill" data-bs-target="#favorit" type="button">
                            <i class="bi bi-heart me-2"></i>Event Favorit
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tiket-tab" data-bs-toggle="pill" data-bs-target="#tiket" type="button">
                            <i class="bi bi-ticket-perforated me-2"></i>Tiket Saya
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="riwayat-tab" data-bs-toggle="pill" data-bs-target="#riwayat" type="button">
                            <i class="bi bi-clock-history me-2"></i>Riwayat Pemesanan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profil-tab" data-bs-toggle="pill" data-bs-target="#profil" type="button">
                            <i class="bi bi-person-gear me-2"></i>Pengaturan Profil
                        </button>
                    </li>
                </ul>
            </div>

            {{-- Konten Tab --}}
            <div class="tab-content" id="dashboardTabsContent">

                {{-- TAB FAVORIT --}}
                <div class="tab-pane fade show active" id="favorit" role="tabpanel">
                    @if($favoriteEvents->count() > 0)
                        <div class="row g-4">
                            @foreach($favoriteEvents as $event)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card event-card-fav h-100 shadow-sm">
                                        <div class="position-relative">
                                            @if($event->image_path)
                                                @php
                                                    $favImgUrl = $event->image_path
                                                        ? (str_starts_with($event->image_path, 'http')
                                                            ? $event->image_path
                                                            : \App\Helpers\StorageHelper::url($event->image_path))
                                                        : null;
                                                @endphp
                                                <img src="{{ $favImgUrl ?? 'https://via.placeholder.com/400x200?text=EventKita' }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                            @else
                                                <img src="https://via.placeholder.com/400x200?text=EventKita" class="card-img-top" alt="Placeholder">
                                            @endif
                                            <span class="position-absolute top-0 end-0 m-3 badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill small">
                                                {{ $event->category->name }}
                                            </span>
                                        </div>
                                        <div class="card-body p-4">
                                            <h5 class="fw-bold text-truncate mb-2">{{ $event->title }}</h5>
                                            <div class="text-muted small mb-3">
                                                <div class="mb-1"><i class="bi bi-calendar3 me-2 text-primary"></i> {{ $event->date->format('d M Y') }}</div>
                                                <div class="text-truncate"><i class="bi bi-geo-alt me-2 text-primary"></i> {{ $event->cityRelation ? $event->cityRelation->name : $event->city }}</div>
                                            </div>
                                            <div class="h5 fw-bold text-primary mb-4">{{ $event->formatted_price }}</div>
                                            
                                            <div class="d-flex gap-2 pt-3 border-top">
                                                <a href="{{ route('event.detail', $event->slug) }}" class="btn btn-primary flex-grow-1 rounded-pill px-3">
                                                    Lihat Detail
                                                </a>
                                                <form action="{{ route('favorites.destroy', $event->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger rounded-circle p-2" title="Hapus dari favorit" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-heart-break-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-heartbreak text-muted opacity-25" style="font-size: 5rem;"></i>
                            </div>
                            <h4 class="fw-bold text-muted">Belum ada event favorit</h4>
                            <p class="text-muted">Jelajahi berbagai event seru dan simpan yang kamu suka di sini!</p>
                            <a href="{{ route('kategori') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                                <i class="bi bi-search me-2"></i> Cari Event Sekarang
                            </a>
                        </div>
                    @endif
                </div>

                {{-- TAB TIKET SAYA --}}
                <div class="tab-pane fade" id="tiket" role="tabpanel">
                    @php
                        $now = now();
                        $allOrders = Auth::user()->orders()
                            ->with(['event', 'tickets.ticketCategory'])
                            ->where('status', 'paid')
                            ->orderBy('created_at', 'desc')
                            ->get();
                        
                        // Pisahkan tiket aktif dan riwayat berdasarkan tanggal event
                        $activeOrders = $allOrders->filter(function($order) use ($now) {
                            return $order->event->date->isFuture() || $order->event->date->isToday();
                        });
                        
                        $historyOrders = $allOrders->filter(function($order) use ($now) {
                            return $order->event->date->isPast() && !$order->event->date->isToday();
                        });
                    @endphp

                    {{-- Sub Navigation untuk Tiket --}}
                    <div class="mb-4">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-semibold" id="active-tickets-tab" data-bs-toggle="tab" data-bs-target="#active-tickets" type="button">
                                    <i class="bi bi-ticket-perforated-fill me-2"></i>Tiket Aktif 
                                    <span class="badge bg-primary ms-1">{{ $activeOrders->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-semibold" id="history-tickets-tab" data-bs-toggle="tab" data-bs-target="#history-tickets" type="button">
                                    <i class="bi bi-clock-history me-2"></i>Riwayat 
                                    <span class="badge bg-secondary ms-1">{{ $historyOrders->count() }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        {{-- TIKET AKTIF --}}
                        <div class="tab-pane fade show active" id="active-tickets" role="tabpanel">
                            @if($activeOrders->count() > 0)
                                <div class="row g-4">
                                    @foreach($activeOrders as $order)
                                <div class="col-12">
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body p-4">
                                            <div class="row align-items-center">
                                                {{-- Event Image --}}
                                                <div class="col-md-2 mb-3 mb-md-0">
                                                            @if($order->event->image_path)
                                                                @php
                                                                    $activeOrderImgUrl = $order->event->image_path
                                                                        ? (str_starts_with($order->event->image_path, 'http')
                                                                            ? $order->event->image_path
                                                                            : \App\Helpers\StorageHelper::url($order->event->image_path))
                                                                        : null;
                                                                @endphp
                                                                <img src="{{ $activeOrderImgUrl ?? 'https://via.placeholder.com/150x100?text=Event' }}" 
                                                                     class="img-fluid rounded" 
                                                                     alt="{{ $order->event->title }}"
                                                                     style="height: 100px; width: 100%; object-fit: cover;">
                                                    @else
                                                        <img src="https://via.placeholder.com/150x100?text=Event" 
                                                             class="img-fluid rounded" 
                                                             alt="{{ $order->event->title }}">
                                                    @endif
                                                </div>

                                                {{-- Order Details --}}
                                                <div class="col-md-6 mb-3 mb-md-0">
                                                    <h5 class="fw-bold mb-2">{{ $order->event->title }}</h5>
                                                    <div class="text-muted small mb-2">
                                                        <i class="bi bi-receipt me-2"></i>
                                                        <strong>Order:</strong> {{ $order->order_number }}
                                                    </div>
                                                    <div class="text-muted small mb-2">
                                                        <i class="bi bi-calendar3 me-2"></i>
                                                        {{ $order->event->date->format('d M Y') }} • 
                                                        {{ date('H:i', strtotime($order->event->time_start)) }} WIB
                                                    </div>
                                                    <div class="text-muted small mb-2">
                                                        <i class="bi bi-ticket-perforated me-2"></i>
                                                        {{ $order->tickets->count() }} Tiket
                                                    </div>
                                                    <div class="mt-2">
                                                        @if($order->status === 'paid')
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle me-1"></i>Dibayar
                                                            </span>
                                                        @elseif($order->status === 'pending')
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-clock me-1"></i>Menunggu Pembayaran
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Price & Action --}}
                                                <div class="col-md-4 text-md-end">
                                                    <div class="mb-3">
                                                        <div class="text-muted small mb-1">Total Pembayaran</div>
                                                        <h4 class="fw-bold text-primary mb-0">
                                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                                        </h4>
                                                    </div>
                                                    <div class="d-grid gap-2">
                                                        <button class="btn btn-primary btn-sm" 
                                                                data-bs-toggle="collapse" 
                                                                data-bs-target="#tickets-{{ $order->id }}">
                                                            <i class="bi bi-eye me-1"></i>Lihat Tiket
                                                        </button>
                                                        @if($order->status === 'paid')
                                                            <a href="{{ route('order.print', $order->id) }}" 
                                                               class="btn btn-outline-primary btn-sm"
                                                               target="_blank">
                                                                <i class="bi bi-printer me-1"></i>Cetak Tiket
                                                            </a>
                                                        @elseif($order->status === 'pending')
                                                            <a href="{{ route('order.show', $order->id) }}" 
                                                               class="btn btn-warning btn-sm">
                                                                <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Tickets Details (Collapsible) --}}
                                            <div class="collapse mt-4" id="tickets-{{ $order->id }}">
                                                <hr class="my-3">
                                                <h6 class="fw-bold mb-3">
                                                    <i class="bi bi-ticket-detailed me-2"></i>Detail Tiket
                                                </h6>
                                                <div class="row g-3">
                                                    @foreach($order->tickets as $ticket)
                                                        <div class="col-md-6">
                                                            <div class="card border border-primary">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                                        <div>
                                                                            <h6 class="fw-bold mb-1">{{ $ticket->ticketCategory->name }}</h6>
                                                                            <p class="text-muted small mb-0">
                                                                                Kode: <strong>{{ $ticket->ticket_code }}</strong>
                                                                            </p>
                                                                        </div>
                                                                        <span class="badge bg-success">Valid</span>
                                                                    </div>
                                                                    <div class="mt-3 pt-3 border-top">
                                                                        <div class="d-flex justify-content-between">
                                                                            <span class="text-muted small">Harga</span>
                                                                            <span class="fw-bold">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                @endforeach
                            </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="bi bi-ticket text-muted opacity-25" style="font-size: 5rem;"></i>
                                    </div>
                                    <h4 class="fw-bold text-muted">Belum ada tiket aktif</h4>
                                    <p class="text-muted">Yuk beli tiket untuk event seru yang akan datang!</p>
                                    <a href="{{ route('kategori') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                                        <i class="bi bi-search me-2"></i>Cari Event Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- RIWAYAT TIKET --}}
                        <div class="tab-pane fade" id="history-tickets" role="tabpanel">
                            @if($historyOrders->count() > 0)
                                <div class="alert alert-info border-0 mb-4">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Info:</strong> Berikut adalah riwayat tiket untuk event yang sudah berlalu. Data tersimpan untuk keperluan arsip Anda.
                                </div>

                                <div class="row g-4">
                                    @foreach($historyOrders as $order)
                                        <div class="col-12">
                                            <div class="card shadow-sm border-0 opacity-75">
                                                <div class="card-body p-4">
                                                    <div class="row align-items-center">
                                                        {{-- Event Image --}}
                                                        <div class="col-md-2 mb-3 mb-md-0">
                                                            @if($order->event->image_path)
                                                                @php
                                                                    $historyOrderImgUrl = $order->event->image_path
                                                                        ? (str_starts_with($order->event->image_path, 'http')
                                                                            ? $order->event->image_path
                                                                            : \App\Helpers\StorageHelper::url($order->event->image_path))
                                                                        : null;
                                                                @endphp
                                                                <img src="{{ $historyOrderImgUrl ?? 'https://via.placeholder.com/150x100?text=Event' }}" 
                                                                     class="img-fluid rounded" 
                                                                     alt="{{ $order->event->title }}"
                                                                     style="height: 100px; width: 100%; object-fit: cover; filter: grayscale(50%);">
                                                            @else
                                                                <img src="https://via.placeholder.com/150x100?text=Event" 
                                                                     class="img-fluid rounded" 
                                                                     alt="{{ $order->event->title }}"
                                                                     style="filter: grayscale(50%);">
                                                            @endif
                                                            <div class="text-center mt-2">
                                                                <span class="badge bg-secondary">
                                                                    <i class="bi bi-check-circle me-1"></i>Selesai
                                                                </span>
                                                            </div>
                                                        </div>

                                                        {{-- Order Details --}}
                                                        <div class="col-md-6 mb-3 mb-md-0">
                                                            <h5 class="fw-bold mb-2">{{ $order->event->title }}</h5>
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-receipt me-2"></i>
                                                                <strong>Order:</strong> {{ $order->order_number }}
                                                            </div>
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-calendar3 me-2"></i>
                                                                {{ $order->event->date->format('d M Y') }} • 
                                                                {{ date('H:i', strtotime($order->event->time_start)) }} WIB
                                                                <span class="badge bg-dark ms-2">Sudah Berlalu</span>
                                                            </div>
                                                            <div class="text-muted small mb-2">
                                                                <i class="bi bi-ticket-perforated me-2"></i>
                                                                {{ $order->tickets->count() }} Tiket
                                                            </div>
                                                            <div class="text-muted small">
                                                                <i class="bi bi-clock me-2"></i>
                                                                Dibeli: {{ $order->created_at->format('d M Y H:i') }} WIB
                                                            </div>
                                                        </div>

                                                        {{-- Price & Action --}}
                                                        <div class="col-md-4 text-md-end">
                                                            <div class="mb-3">
                                                                <div class="text-muted small mb-1">Total Pembayaran</div>
                                                                <h4 class="fw-bold text-muted mb-0">
                                                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                                                </h4>
                                                            </div>
                                                            <div class="d-grid gap-2">
                                                                <button class="btn btn-outline-secondary btn-sm" 
                                                                        data-bs-toggle="collapse" 
                                                                        data-bs-target="#history-tickets-{{ $order->id }}">
                                                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Tickets Details (Collapsible) --}}
                                                    <div class="collapse mt-4" id="history-tickets-{{ $order->id }}">
                                                        <hr class="my-3">
                                                        <h6 class="fw-bold mb-3">
                                                            <i class="bi bi-ticket-detailed me-2"></i>Detail Tiket
                                                        </h6>
                                                        <div class="row g-3">
                                                            @foreach($order->tickets as $ticket)
                                                                <div class="col-md-6">
                                                                    <div class="card border">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                                <div>
                                                                                    <h6 class="fw-bold mb-1">{{ $ticket->ticketCategory->name }}</h6>
                                                                                    <p class="text-muted small mb-0">
                                                                                        Kode: <strong>{{ $ticket->ticket_code }}</strong>
                                                                                    </p>
                                                                                </div>
                                                                                <span class="badge bg-secondary">Expired</span>
                                                                            </div>
                                                                            <div class="mt-3 pt-3 border-top">
                                                                                <div class="d-flex justify-content-between">
                                                                                    <span class="text-muted small">Harga</span>
                                                                                    <span class="fw-bold">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-4">
                                        <i class="bi bi-clock-history text-muted opacity-25" style="font-size: 5rem;"></i>
                                    </div>
                                    <h4 class="fw-bold text-muted">Belum ada riwayat</h4>
                                    <p class="text-muted">Riwayat tiket event yang sudah berlalu akan muncul di sini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TAB RIWAYAT PEMESANAN --}}
                <div class="tab-pane fade" id="riwayat" role="tabpanel">
                    @php
                        $allOrders = Auth::user()->orders()->with(['event', 'tickets.ticketCategory'])->orderBy('created_at', 'desc')->get();
                    @endphp
                    
                    @if($allOrders->count() > 0)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold">
                                    <i class="bi bi-receipt me-2 text-primary"></i>Semua Pesanan ({{ $allOrders->count() }})
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 py-3">No. Pesanan</th>
                                                <th class="py-3">Event</th>
                                                <th class="py-3">Jumlah Tiket</th>
                                                <th class="py-3">Total</th>
                                                <th class="py-3">Status</th>
                                                <th class="py-3">Tanggal</th>
                                                <th class="pe-4 py-3 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($allOrders as $orderItem)
                                                <tr>
                                                    <td class="ps-4">
                                                        <span class="fw-bold text-primary">{{ $orderItem->order_number }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            @php
                                                                $orderImgUrl = $orderItem->event->image_path
                                                                    ? (str_starts_with($orderItem->event->image_path, 'http')
                                                                        ? $orderItem->event->image_path
                                                                        : \App\Helpers\StorageHelper::url($orderItem->event->image_path))
                                                                    : null;
                                                            @endphp
                                                            <img src="{{ $orderImgUrl ?? 'https://via.placeholder.com/50x50?text=E' }}" 
                                                                 class="rounded me-3" 
                                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                                 alt="{{ $orderItem->event->title }}">
                                                            <div>
                                                                <h6 class="mb-0 fw-semibold">{{ Str::limit($orderItem->event->title, 30) }}</h6>
                                                                <small class="text-muted">{{ $orderItem->event->date->format('d M Y') }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-dark">{{ $orderItem->tickets->count() }} tiket</span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold">Rp {{ number_format($orderItem->total_price, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td>
                                                        @if($orderItem->status === 'paid')
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-check-circle me-1"></i>Dibayar
                                                            </span>
                                                        @elseif($orderItem->status === 'pending')
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="bi bi-clock me-1"></i>Menunggu
                                                            </span>
                                                        @elseif($orderItem->status === 'cancelled')
                                                            <span class="badge bg-danger">
                                                                <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                            </span>
                                                        @elseif($orderItem->status === 'expired')
                                                            <span class="badge bg-secondary">
                                                                <i class="bi bi-clock-history me-1"></i>Kedaluwarsa
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($orderItem->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small class="text-muted">{{ $orderItem->created_at->format('d M Y') }}</small>
                                                        <br>
                                                        <small class="text-muted">{{ $orderItem->created_at->format('H:i') }} WIB</small>
                                                    </td>
                                                    <td class="pe-4 text-center">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('order.show', $orderItem->id) }}" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="Lihat Detail">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            @if($orderItem->status === 'paid')
                                                                <a href="{{ route('order.print', $orderItem->id) }}" 
                                                                   class="btn btn-sm btn-outline-success" 
                                                                   target="_blank"
                                                                   title="Cetak Tiket">
                                                                    <i class="bi bi-printer"></i>
                                                                </a>
                                                            @elseif($orderItem->status === 'pending')
                                                                <a href="{{ route('order.show', $orderItem->id) }}" 
                                                                   class="btn btn-sm btn-warning" 
                                                                   title="Bayar Sekarang">
                                                                    <i class="bi bi-credit-card"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Ringkasan Statistik Pemesanan --}}
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="card border-0 bg-success bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="fw-bold text-success mb-0">{{ $allOrders->where('status', 'paid')->count() }}</h3>
                                        <small class="text-muted">Pesanan Sukses</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-warning bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="fw-bold text-warning mb-0">{{ $allOrders->where('status', 'pending')->count() }}</h3>
                                        <small class="text-muted">Menunggu Bayar</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-danger bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="fw-bold text-danger mb-0">{{ $allOrders->where('status', 'cancelled')->count() + $allOrders->where('status', 'expired')->count() }}</h3>
                                        <small class="text-muted">Batal/Expired</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-0 bg-primary bg-opacity-10">
                                    <div class="card-body text-center">
                                        <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($allOrders->where('status', 'paid')->sum('total_price'), 0, ',', '.') }}</h3>
                                        <small class="text-muted">Total Belanja</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-bag-x text-muted opacity-25" style="font-size: 5rem;"></i>
                            </div>
                            <h4 class="fw-bold text-muted">Belum ada pesanan</h4>
                            <p class="text-muted mb-4">Kamu belum pernah memesan tiket event apapun.</p>
                            <a href="{{ url('/events') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-calendar-event me-2"></i>Jelajahi Event
                            </a>
                        </div>
                    @endif
                </div>

                {{-- TAB PROFIL --}}
                <div class="tab-pane fade" id="profil" role="tabpanel">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="profile-form-card shadow-sm p-4 p-md-5">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                                        <i class="bi bi-person-vcard fs-3 text-primary"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">Informasi Pribadi</h4>
                                        <p class="text-muted mb-0 small">Perbarui data diri dan pengaturan akun kamu</p>
                                    </div>
                                </div>

                                <form>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                            <input type="text" class="form-control form-control-custom" value="{{ Auth::user()->name }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Alamat Email</label>
                                            <input type="email" class="form-control form-control-custom" value="{{ Auth::user()->email }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Ubah Kata Sandi</label>
                                            <input type="password" class="form-control form-control-custom" placeholder="Ketikan sandi baru jika ingin diubah">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Konfirmasi Sandi</label>
                                            <input type="password" class="form-control form-control-custom" placeholder="Ulangi sandi baru">
                                        </div>
                                        <div class="col-12 text-end mt-5">
                                            <button type="button" class="btn btn-brand-gradient shadow">
                                                <i class="bi bi-check2-circle me-2"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script>
    // Opsional: Script untuk menjaga tab tetap aktif saat refresh (jika dibutuhkan)
    document.addEventListener('DOMContentLoaded', function() {
        let activeTab = localStorage.getItem('activeDashboardTab');
        if (activeTab) {
            let tabEl = document.querySelector('#' + activeTab);
            if (tabEl) {
                bootstrap.Tab.getOrCreateInstance(tabEl).show();
            }
        }

        const tabLinks = document.querySelectorAll('button[data-bs-toggle="pill"]');
        tabLinks.forEach(item => {
            item.addEventListener('shown.bs.tab', function (event) {
                localStorage.setItem('activeDashboardTab', event.target.id);
            });
        });
    });
</script>
@endsection