@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        --secondary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --info-gradient: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        --card-radius: 1.25rem;
    }

    /* 1. HERO SECTION */
    .admin-hero {
        background: var(--primary-gradient);
        border: none;
        border-radius: var(--card-radius);
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    }
    .admin-hero::before {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .hero-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* 2. STATS CARDS */
    .stats-card {
        background: white;
        border: none;
        border-radius: var(--card-radius);
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
    }
    .stats-icon-bg {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    .bg-soft-primary { background: #e0e7ff; color: #6366f1; }
    .bg-soft-success { background: #d1fae5; color: #10b981; }
    .bg-soft-warning { background: #fef3c7; color: #f59e0b; }
    .bg-soft-danger { background: #fee2e2; color: #ef4444; }

    /* 3. GRADIENT CARDS */
    .gradient-card {
        border-radius: var(--card-radius);
        border: none;
        color: white;
        overflow: hidden;
        position: relative;
    }
    .gradient-card .icon-overlay {
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 8rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    /* 4. ACTIVITY TIMELINE */
    .activity-item {
        position: relative;
        padding-left: 20px;
    }
    .activity-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #f1f5f9;
    }
    .activity-dot {
        position: absolute;
        left: -4px;
        top: 5px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .activity-dot.active { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2); }
    .activity-dot.info { background: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2); }

    /* 5. MODERATION CARD */
    .moderation-card {
        background: var(--secondary-gradient);
        border-radius: var(--card-radius);
        color: white;
        border: none;
    }
    .btn-light-glass {
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(5px);
        transition: 0.2s;
    }
    .btn-light-glass:hover {
        background: white;
        color: #1e293b;
    }
</style>

{{-- ALERTS --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center rounded-3 px-4 py-3" role="alert">
        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
            <i class="bi bi-check-lg"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0">Berhasil!</h6>
            <small>{{ session('success') }}</small>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4 rounded-3 px-4 py-3" role="alert">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-octagon-fill fs-5 me-2"></i>
            <strong>Perhatian!</strong>
        </div>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- HERO SECTION --}}
<div class="card admin-hero mb-4">
    <div class="card-body p-4 p-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <h1 class="display-6 fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-white-50 fs-5 mb-0" style="max-width: 500px;">
                    Kelola event, pantau statistik, dan moderasi komunitas dalam satu dashboard terintegrasi.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- QUICK STATS GRID --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stats-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Event Baru</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $eventsThisWeek }}</h2>
                    <span class="badge bg-success-subtle text-success mt-2 rounded-pill">
                        <i class="bi bi-arrow-up-short"></i> Minggu Ini
                    </span>
                </div>
                <div class="stats-icon-bg bg-soft-primary">
                    <i class="bi bi-calendar-plus"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stats-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Pengguna</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalUsers }}</h2>
                    <small class="text-muted d-block mt-1">Akun Terdaftar</small>
                </div>
                <div class="stats-icon-bg bg-soft-success">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stats-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalCategories }}</h2>
                    <small class="text-muted d-block mt-1">Topik Tersedia</small>
                </div>
                <div class="stats-icon-bg bg-soft-warning">
                    <i class="bi bi-grid"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="stats-card shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Tiket Terjual</p>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalTicketsSold }}</h2>
                    <span class="badge bg-primary-subtle text-primary mt-2 rounded-pill">Live Data</span>
                </div>
                <div class="stats-icon-bg bg-soft-danger">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REVENUE & CITIES (Gradient Cards) --}}
<div class="row g-4 mb-5">
    <div class="col-lg-6">
        <div class="gradient-card shadow-sm" style="background: var(--success-gradient);">
            <i class="bi bi-cash-coin icon-overlay"></i>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="bi bi-wallet2 fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-0 fw-bold ls-1">Total Pendapatan</h6>
                        <h2 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h2>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top border-white border-opacity-10 pt-3">
                    <small class="text-white-50">Akumulasi dari {{ $totalTicketsSold }} transaksi</small>
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="gradient-card shadow-sm" style="background: var(--info-gradient);">
            <i class="bi bi-map icon-overlay"></i>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="bi bi-geo-alt fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-0 fw-bold ls-1">Cakupan Wilayah</h6>
                        <h2 class="fw-bold mb-0">{{ $totalCities }} <span class="fs-5 fw-normal">Kota</span></h2>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top border-white border-opacity-10 pt-3">
                    <small class="text-white-50">Lokasi event tersebar di seluruh Indonesia</small>
                    <i class="bi bi-pin-map-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODERATION BANNER --}}
<div class="card moderation-card shadow mb-5">
    <div class="card-body p-4 p-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0 text-center text-lg-start">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-2">
                    <span class="fs-1 me-3">🛡️</span>
                    <h3 class="fw-bold mb-0">Moderasi Forum & Komunitas</h3>
                </div>
                <p class="text-white-50 mb-0 ms-lg-5 ps-lg-2">
                    Jaga kualitas diskusi dengan memantau dan menghapus konten yang melanggar aturan komunitas EventKita.
                </p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ route('admin.forum.index') }}" class="btn btn-light-glass btn-lg rounded-pill px-4 fw-bold">
                    Buka Panel Moderasi <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- BOTTOM SECTION: Activity Timeline Only --}}
<div class="row g-4">
    {{-- Card "Kelola Data Event" DIHAPUS --}}

    {{-- Activity Timeline (Dipindah jadi Full Width / col-12) --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">Aktivitas Terbaru</h6>
            </div>
            <div class="card-body p-4">
                <div class="timeline-wrapper">
                    @forelse($recentOrders as $order)
                        <div class="activity-item pb-4">
                            <div class="activity-dot active"></div>
                            <p class="mb-1 fw-bold text-dark small">Tiket Terjual</p>
                            <span class="text-muted small d-block">
                                {{ $order->user->name }} membeli tiket <strong>{{ $order->event->title }}</strong>
                            </span>
                            <span class="text-xs text-muted fst-italic">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                    @endforelse
                    
                    @forelse($recentEvents as $event)
                        <div class="activity-item pb-4">
                            <div class="activity-dot info"></div>
                            <p class="mb-1 fw-bold text-dark small">Event Baru Dibuat</p>
                            <span class="text-muted small d-block">
                                <strong>{{ $event->title }}</strong> ditambahkan ke kategori {{ $event->category->name }}
                            </span>
                            <span class="text-xs text-muted fst-italic">{{ $event->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        @if($recentOrders->isEmpty())
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-1 opacity-25"></i>
                                <p class="small text-muted mt-2">Belum ada aktivitas tercatat.</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- WEEKLY SCHEDULE --}}
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
        <h5 class="fw-bold mb-0"><i class="bi bi-calendar-week me-2 text-primary"></i>Jadwal Event Minggu Ini</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            @forelse($upcomingEvents as $event)
                <div class="col-md-6 col-lg-4">
                    <div class="d-flex align-items-center p-3 border rounded-3 h-100 bg-light">
                        <div class="text-center me-3 px-3 border-end">
                            <h4 class="fw-bold text-primary mb-0">{{ $event->date->format('d') }}</h4>
                            <small class="text-uppercase fw-bold text-muted">{{ $event->date->format('M') }}</small>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="fw-bold text-dark text-truncate mb-1">{{ $event->title }}</h6>
                            <div class="small text-muted d-flex align-items-center">
                                <i class="bi bi-clock me-1"></i> {{ $event->time }}
                                <span class="mx-2">|</span>
                                <i class="bi bi-geo-alt me-1"></i> {{ $event->cityRelation->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="badge bg-{{ $event->status === 'published' ? 'success' : 'secondary' }} rounded-circle p-2" 
                                  title="{{ ucfirst($event->status) }}">
                                <span class="visually-hidden">{{ $event->status }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://illustrations.popsy.co/amber/calendar.svg" alt="Empty Calendar" style="width: 150px; opacity: 0.6;" class="mb-3">
                    <p class="text-muted fw-bold">Tidak ada jadwal event untuk minggu ini.</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="card-footer bg-white border-0 text-center pb-4">
        <small class="text-muted">Menampilkan event yang akan datang dalam 7 hari ke depan.</small>
    </div>
</div>

{{-- MODALS DAN JS TELAH DIHAPUS --}}
@endsection