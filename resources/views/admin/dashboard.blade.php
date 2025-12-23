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
                <span class="hero-badge mb-3 d-inline-block">
                    <i class="bi bi-stars me-1"></i> Admin Panel v2.0
                </span>
                <h1 class="display-6 fw-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-white-50 fs-5 mb-0" style="max-width: 500px;">
                    Kelola event, pantau statistik, dan moderasi komunitas dalam satu dashboard terintegrasi.
                </p>
            </div>

            {{-- Productivity Stats --}}
            <div class="col-lg-5">
                @php
                    $totalAllEvents = \App\Models\Event::count();
                    $activeEvents = \App\Models\Event::where('date', '>=', now()->toDateString())->count();
                    $activeRate = $totalAllEvents > 0 ? ($activeEvents / $totalAllEvents) * 100 : 0;
                @endphp

                <div class="bg-white bg-opacity-10 rounded-4 p-4 backdrop-blur">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <p class="text-white-50 text-uppercase fw-bold small mb-1">Status Sistem</p>
                            <h3 class="fw-bold mb-0 text-white">{{ $activeEvents }} <span class="fs-6 text-white-50 fw-normal">Event Aktif</span></h3>
                        </div>
                        <div class="text-end">
                            <span class="display-6 fw-bold text-white">{{ number_format($activeRate, 0) }}%</span>
                        </div>
                    </div>
                    
                    <div class="progress" style="height: 8px; background: rgba(0,0,0,0.2); border-radius: 10px;">
                        <div class="progress-bar bg-white" role="progressbar" 
                             style="width: {{ $activeRate }}%; border-radius: 10px;" 
                             aria-valuenow="{{ $activeRate }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    
                    <div class="mt-3 d-flex gap-3 text-white-50 small">
                        <span><i class="bi bi-database me-1"></i> Total Data: <strong>{{ $totalAllEvents }}</strong></span>
                        <span><i class="bi bi-server me-1"></i> Server: <strong>Online</strong></span>
                    </div>
                </div>
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

{{-- BOTTOM SECTION: Quick Actions & Timeline --}}
<div class="row g-4">
    {{-- Left: Event Management CTA --}}
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-5 text-center d-flex flex-column justify-content-center align-items-center">
                <div class="bg-primary bg-opacity-10 p-4 rounded-circle mb-3">
                    <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold text-dark">Kelola Data Event</h4>
                <p class="text-muted mb-4" style="max-width: 400px;">
                    Tambah, edit, atau hapus event yang terdaftar di platform. Pastikan informasi selalu up-to-date.
                </p>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.events') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        Lihat Semua Event
                    </a>
                    {{-- Tombol Tambah --}}
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Baru
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Activity Timeline --}}
    <div class="col-xl-4">
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
{{-- MODAL TAMBAH EVENT --}}
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle-fill text-primary me-2"></i> Tambah Event Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Nama Event <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control bg-light border-0" placeholder="Contoh: Konser Harmoni Bangsa" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select bg-light border-0" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Pelaksanaan <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota / Lokasi <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select bg-light border-0" required>
                                <option value="">Pilih Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat Lengkap</label>
                            <input type="text" name="location" class="form-control bg-light border-0" placeholder="e.g. Gedung Sate, Bandung">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="time_start" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link Website</label>
                            <input type="url" name="website_url" class="form-control bg-light border-0" placeholder="https://example.com">
                        </div>
                        
                        {{-- Ticket Category Section --}}
                        <div class="col-12">
                            <hr class="my-3 border-secondary border-opacity-10">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0 text-primary"><i class="bi bi-ticket-perforated-fill me-2"></i>Kategori Tiket</label>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addTicketCategory()">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                                </button>
                            </div>
                            <div id="ticketCategoriesContainer">
                                {{-- Default Item 1 --}}
                                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted">Nama Kategori</label>
                                            <input type="text" name="ticket_categories[0][name]" class="form-control border-0 bg-white shadow-sm" placeholder="e.g. VIP" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Harga (Rp)</label>
                                            <input type="number" name="ticket_categories[0][price]" class="form-control border-0 bg-white shadow-sm" placeholder="0" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Stok</label>
                                            <input type="number" name="ticket_categories[0][stock]" class="form-control border-0 bg-white shadow-sm" placeholder="Unlimited">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm w-100 shadow-sm" onclick="removeTicketCategory(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <input type="text" name="ticket_categories[0][description]" class="form-control border-0 bg-white shadow-sm" placeholder="Deskripsi tiket (opsional)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Banner Event <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*" required>
                            <small class="text-muted">Rekomendasi: 1200x600px (Maks 5MB)</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Event</label>
                            <textarea name="description" class="form-control bg-light border-0" rows="3" placeholder="Jelaskan detail event..."></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 d-flex align-items-center">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_featured" value="1" id="isFeatured" style="width: 2.5em; height: 1.25em;">
                                <div>
                                    <label class="form-check-label fw-bold d-block" for="isFeatured">Pasang sebagai Featured</label>
                                    <small class="text-muted">Event akan muncul di slide utama halaman depan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 text-muted fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL UPDATE EVENT --}}
<div class="modal fade" id="updateEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="border-radius: 1.5rem;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateEventForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    {{-- Form Fields untuk Edit (Sama dengan Tambah, beda ID) --}}
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Nama Event</label>
                            <input type="text" name="title" id="edit_title" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Kategori</label>
                            <select class="form-select bg-light border-0" name="category_id" id="edit_category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" id="edit_date" class="form-control bg-light border-0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota</label>
                            <select name="city_id" id="edit_city_id" class="form-select bg-light border-0" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lokasi Detail</label>
                            <input type="text" name="location" id="edit_location" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai</label>
                            <input type="time" name="time_start" id="edit_time_start" class="form-control bg-light border-0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link Website</label>
                            <input type="url" name="website_url" id="edit_website_url" class="form-control bg-light border-0">
                        </div>
                        
                        {{-- Ticket Edit Section --}}
                        <div class="col-12">
                            <hr class="my-3 border-secondary border-opacity-10">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0 text-primary"><i class="bi bi-ticket-perforated-fill me-2"></i>Kategori Tiket</label>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addEditTicketCategory()">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <div id="editTicketCategoriesContainer"></div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Banner Event</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="edit_description" class="form-control bg-light border-0" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 bg-light rounded-3 border-0 d-flex align-items-center">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_featured" value="1" id="edit_is_featured" style="width: 2.5em; height: 1.25em;">
                                <label class="form-check-label fw-bold" for="edit_is_featured">Featured Event</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 text-muted fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
const eventsData = @json($events); // Pastikan $events dikirim dari controller
let ticketCategoryIndex = 1;
let editTicketCategoryIndex = 0;

function addTicketCategory() {
    const container = document.getElementById('ticketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Nama Kategori</label>
                    <input type="text" name="ticket_categories[${ticketCategoryIndex}][name]" class="form-control border-0 bg-white shadow-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[${ticketCategoryIndex}][price]" class="form-control border-0 bg-white shadow-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Stok</label>
                    <input type="number" name="ticket_categories[${ticketCategoryIndex}][stock]" class="form-control border-0 bg-white shadow-sm">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100 shadow-sm" onclick="removeTicketCategory(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newCategory);
    ticketCategoryIndex++;
}

function removeTicketCategory(button) {
    button.closest('.ticket-category-item').remove();
}

function addEditTicketCategory() {
    const container = document.getElementById('editTicketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Nama</label>
                    <input type="text" name="ticket_categories[${editTicketCategoryIndex}][name]" class="form-control border-0 bg-white shadow-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Harga</label>
                    <input type="number" name="ticket_categories[${editTicketCategoryIndex}][price]" class="form-control border-0 bg-white shadow-sm" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Stok</label>
                    <input type="number" name="ticket_categories[${editTicketCategoryIndex}][stock]" class="form-control border-0 bg-white shadow-sm">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100 shadow-sm" onclick="removeEditTicketCategory(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newCategory);
    editTicketCategoryIndex++;
}

function removeEditTicketCategory(button) {
    button.closest('.ticket-category-item').remove();
}

function editEvent(eventId) {
    const event = eventsData.find(e => e.id === eventId);
    if (!event) return alert('Data event tidak ditemukan!');
    
    document.getElementById('updateEventForm').action = `/admin/event/${eventId}`;
    document.getElementById('edit_title').value = event.title;
    document.getElementById('edit_category_id').value = event.category_id;
    document.getElementById('edit_date').value = event.date.split('T')[0];
    document.getElementById('edit_city_id').value = event.city_id || '';
    document.getElementById('edit_location').value = event.location || '';
    document.getElementById('edit_time_start').value = event.time_start || '';
    document.getElementById('edit_website_url').value = event.website_url || '';
    document.getElementById('edit_description').value = event.description || '';
    document.getElementById('edit_is_featured').checked = event.is_featured;
    
    // Ticket handling
    const container = document.getElementById('editTicketCategoriesContainer');
    container.innerHTML = '';
    editTicketCategoryIndex = 0;
    
    if (event.ticket_categories && event.ticket_categories.length > 0) {
        event.ticket_categories.forEach((ticket, index) => {
            const categoryHtml = `
                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                    <div class="row g-2">
                        <input type="hidden" name="ticket_categories[${index}][id]" value="${ticket.id}">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Nama</label>
                            <input type="text" name="ticket_categories[${index}][name]" value="${ticket.category_name}" class="form-control border-0 bg-white shadow-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Harga</label>
                            <input type="number" name="ticket_categories[${index}][price]" value="${ticket.price}" class="form-control border-0 bg-white shadow-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Stok</label>
                            <input type="number" name="ticket_categories[${index}][stock]" value="${ticket.stock || ''}" class="form-control border-0 bg-white shadow-sm">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm w-100 shadow-sm" onclick="removeEditTicketCategory(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', categoryHtml);
            editTicketCategoryIndex++;
        });
    }
    
    const modal = new bootstrap.Modal(document.getElementById('updateEventModal'));
    modal.show();
}
</script>
@endsection