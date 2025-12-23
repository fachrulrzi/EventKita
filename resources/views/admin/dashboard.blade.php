@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<style>
    /* 1. Custom CSS - UI Dashboard Modern */
    :root {
        --primary-violet: #6366f1;
        --secondary-slate: #1e293b;
    }

    /* Hero Section */
    .admin-hero {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border: none;
        border-radius: 1.5rem;
        color: white;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2);
    }
    .admin-hero-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .admin-hero-title { font-weight: 800; }
    
    /* Progress Circle */
    .admin-progress-circle {
        width: 70px;
        height: 70px;
        border: 5px solid rgba(255,255,255,0.2);
        border-top: 5px solid #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-weight: 700;
    }

    /* Quick Cards Stats */
    .admin-quick-card {
        background: white;
        border-radius: 1.25rem;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
    }
    .admin-quick-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    }
    .admin-pill-icon {
        width: 50px;
        height: 50px;
        background: #f5f3ff;
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.25rem;
    }

    /* Forum Moderation Card */
    .moderation-card-premium {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border: none;
        border-radius: 1.5rem;
        color: white;
    }

    .btn-light-custom {
        background: white;
        color: #312e81;
        font-weight: 700;
        border-radius: 50px;
        padding: 10px 25px;
        transition: 0.3s;
    }
    .btn-light-custom:hover {
        background: #f8fafc;
        transform: scale(1.05);
    }
</style>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 1rem;">
        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="border-radius: 1rem;">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div class="fw-bold">Mohon periksa kesalahan berikut:</div>
        </div>
        <ul class="mb-0 small">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card admin-hero mb-4 shadow-lg">
    <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <div class="mb-4 mb-md-0">
            <span class="admin-hero-badge mb-3 d-inline-block">Update Terkini</span>
            <h1 class="admin-hero-title h2 mb-2">Halo, {{ Auth::user()->name }} 👋</h1>
            <p class="text-white-50 mb-0 fs-6">Kelola seluruh ekosistem EventKita dengan kendali penuh hari ini.</p>
        </div>

@php
    $totalAllEvents = \App\Models\Event::count();
    // Menghitung event yang tanggalnya hari ini atau ke depan
    $activeEvents = \App\Models\Event::where('date', '>=', now()->toDateString())->count();
    
    $activeRate = $totalAllEvents > 0 ? ($activeEvents / $totalAllEvents) * 100 : 0;
@endphp

<div class="text-center bg-white bg-opacity-10 p-4 rounded-4" style="backdrop-filter: blur(10px); min-width: 180px;">
    <p class="text-white-50 small mb-2 fw-bold text-uppercase">Event Aktif</p>
    
    <div class="admin-progress-circle" style="background: conic-gradient(#fff {{ $activeRate }}%, transparent 0); -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 6px), #fff 0); mask: radial-gradient(farthest-side, transparent calc(100% - 6px), #fff 0); border: none;">
        <span class="text-white fw-bold" style="-webkit-mask: none; mask: none;">{{ number_format($activeRate, 0) }}%</span>
    </div>
    
    <small class="text-white fw-bold d-block">{{ $activeEvents }} dari {{ $totalAllEvents }} Event</small>
    <small class="text-white-50" style="font-size: 10px;">Sedang berjalan/mendatang</small>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-3 col-sm-6">
        <div class="admin-quick-card shadow-sm">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Event Baru</p>
                <h4 class="fw-bold mb-0 text-dark">+{{ $eventsThisWeek }}</h4>
                <small class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i> Minggu ini</small>
            </div>
            <div class="admin-pill-icon"><i class="bi bi-lightning-charge-fill"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="admin-quick-card shadow-sm">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Total User</p>
                <h4 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h4>
                <small class="text-muted small">Member Terdaftar</small>
            </div>
            <div class="admin-pill-icon"><i class="bi bi-people-fill"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="admin-quick-card shadow-sm">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                <h4 class="fw-bold mb-0 text-dark">{{ $totalCategories }}</h4>
                <small class="text-muted small">Kategori Aktif</small>
            </div>
            <div class="admin-pill-icon"><i class="bi bi-grid-fill"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="admin-quick-card shadow-sm">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Tiket Terjual</p>
                <h4 class="fw-bold mb-0 text-dark">{{ $totalTicketsSold }}</h4>
                <small class="text-primary fw-bold">Live Data</small>
            </div>
            <div class="admin-pill-icon"><i class="bi bi-ticket-perforated-fill"></i></div>
        </div>
    </div>
</div>

{{-- Revenue & Additional Stats --}}
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 1.25rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Total Pendapatan</p>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-4" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-cash-stack" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <small class="text-white-50"><i class="bi bi-graph-up-arrow me-1"></i>Dari {{ $totalTicketsSold }} tiket terjual</small>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 1.25rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="text-white-50 small fw-bold text-uppercase mb-1">Total Kota</p>
                        <h3 class="fw-bold mb-0">{{ $totalCities }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-4" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
                <small class="text-white-50"><i class="bi bi-pin-map me-1"></i>Lokasi event tersedia</small>
            </div>
        </div>
    </div>
</div>

<div class="card moderation-card-premium mb-5 shadow">
    <div class="card-body p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-4 mb-lg-0">
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                    <div class="bg-white bg-opacity-10 p-3 rounded-4 me-3">
                        <i class="bi bi-shield-lock-fill text-white fs-1"></i>
                    </div>
                    <div>
                        <h4 class="text-white fw-bold mb-1">Moderasi Forum & Diskusi</h4>
                        <p class="text-white-50 mb-0">Hapus konten tidak pantas dan jaga kebersihan komunitas EventKita.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ route('admin.forum.index') }}" class="btn btn-light-custom btn-lg shadow">
                    Buka Panel Moderasi <i class="bi bi-arrow-right-short ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="admin-quick-card shadow-sm border-0">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Total Event</p>
                <h3 class="fw-bold mb-0 text-dark">0</h3>
            </div>
            <div class="admin-pill-icon"><i class="bi bi-calendar-event"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-quick-card shadow-sm border-0">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Event Iklan</p>
                <h3 class="fw-bold mb-0 text-dark">0</h3>
            </div>
            <div class="admin-pill-icon text-warning"><i class="bi bi-bullseye"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-quick-card shadow-sm border-0">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                <h3 class="fw-bold mb-0 text-dark">0</h3>
            </div>
            <div class="admin-pill-icon text-success"><i class="bi bi-tags"></i></div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="admin-quick-card shadow-sm border-0">
            <div>
                <p class="text-muted small fw-bold text-uppercase mb-1">Pengunjung</p>
                <h3 class="fw-bold mb-0 text-dark">0</h3>
            </div>
            <div class="admin-pill-icon text-danger"><i class="bi bi-people"></i></div>
        </div>
    </div>
</div> -->

<div class="row g-4">
    <div class="col-xxl-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 text-center">
                <h5 class="fw-bold mb-2 text-dark">Data Event Terdaftar</h5>
                <p class="text-muted mb-3">Daftar event dan kontrol publikasi sekarang dipindahkan ke halaman terpisah.</p>
                <a href="{{ route('admin.events') }}" class="btn btn-primary rounded-pill px-4 fw-bold">Buka Kelola Event</a>
            </div>
        </div>
    </div>

    <div class="col-xxl-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-activity me-2 text-primary"></i>Aktivitas Terbaru</h6>
                <div class="timeline-container">
                    @forelse($recentOrders as $order)
                        <div class="d-flex mb-3 pb-3 border-bottom gap-3">
                            <div class="bg-success rounded-circle flex-shrink-0" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                            <div class="flex-grow-1">
                                <p class="mb-1 fw-bold small text-dark">Order Baru</p>
                                <small class="text-muted d-block">{{ $order->user->name }} membeli tiket <strong>{{ $order->event->title }}</strong></small>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                    @endforelse
                    
                    @forelse($recentEvents as $event)
                        <div class="d-flex mb-3 pb-3 @if(!$loop->last) border-bottom @endif gap-3">
                            <div class="bg-primary rounded-circle flex-shrink-0" style="width: 12px; height: 12px; margin-top: 5px;"></div>
                            <div class="flex-grow-1">
                                <p class="mb-1 fw-bold small text-dark">Event Baru Dibuat</p>
                                <small class="text-muted d-block"><strong>{{ $event->title }}</strong> ({{ $event->category->name }})</small>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $event->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        @if($recentOrders->isEmpty())
                            <div class="text-center py-3">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted small mb-0 mt-2">Belum ada aktivitas</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-calendar-check me-2 text-primary"></i>Jadwal Minggu Ini</h6>
                @forelse($upcomingEvents as $event)
                    <div class="d-flex gap-3 mb-3 pb-3 @if(!$loop->last) border-bottom @endif">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-3 text-center p-2" style="width: 50px;">
                                <div class="fw-bold text-primary" style="font-size: 1.25rem;">{{ $event->date->format('d') }}</div>
                                <small class="text-muted" style="font-size: 0.7rem;">{{ $event->date->format('M') }}</small>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-bold small text-dark">{{ $event->title }}</p>
                            <small class="text-muted d-block"><i class="bi bi-geo-alt me-1"></i>{{ $event->cityRelation->name ?? $event->location }}</small>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $event->time }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="badge bg-{{ $event->status === 'published' ? 'success' : 'warning' }} rounded-pill">{{ ucfirst($event->status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <img src="https://illustrations.popsy.co/slate/calendar.svg" alt="empty" style="width: 100px;" class="mb-3 opacity-75">
                        <p class="text-muted small fw-bold mb-0">Belum ada event minggu ini</p>
                        <small class="text-muted">Jadwal akan muncul otomatis</small>
                    </div>
                @endforelse
            </div>
        </div>
        
        <p class="text-muted small text-center mt-5">© 2025 EventKita — Secured Panel</p>
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
                        <div class="col-12">
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0"><i class="bi bi-ticket-perforated-fill text-primary me-2"></i>Kategori Tiket</label>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" onclick="addTicketCategory()">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <div id="ticketCategoriesContainer">
                                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Nama Kategori</label>
                                            <input type="text" name="ticket_categories[0][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Harga (Rp)</label>
                                            <input type="number" name="ticket_categories[0][price]" class="form-control border-0 bg-white" placeholder="0" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Stok</label>
                                            <input type="number" name="ticket_categories[0][stock]" class="form-control border-0 bg-white" placeholder="Unlimited">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeTicketCategory(this)" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small">Deskripsi</label>
                                            <input type="text" name="ticket_categories[0][description]" class="form-control border-0 bg-white" placeholder="Detail kategori">
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
                            <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_featured" value="1" id="isFeatured">
                                <label class="form-check-label fw-bold" for="isFeatured">Pasang sebagai Featured</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
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
                        <div class="col-12">
                            <hr class="my-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label fw-bold mb-0"><i class="bi bi-ticket-perforated-fill text-primary me-2"></i>Kategori Tiket</label>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" onclick="addEditTicketCategory()">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <div id="editTicketCategoriesContainer"></div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Banner Event</label>
                            <input type="file" name="image" class="form-control bg-light border-0" accept="image/*">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="edit_description" class="form-control bg-light border-0" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 bg-light rounded-3 border-0">
                                <input class="form-check-input ms-0 me-2" type="checkbox" name="is_featured" value="1" id="edit_is_featured">
                                <label class="form-check-label fw-bold" for="edit_is_featured">Featured</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
const eventsData = @json($events);
let ticketCategoryIndex = 1;
let editTicketCategoryIndex = 0;

function addTicketCategory() {
    const container = document.getElementById('ticketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="ticket_categories[\${ticketCategoryIndex}][name]" class="form-control border-0 bg-white" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[\${ticketCategoryIndex}][price]" class="form-control border-0 bg-white" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Stok</label>
                    <input type="number" name="ticket_categories[\${ticketCategoryIndex}][stock]" class="form-control border-0 bg-white">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeTicketCategory(this)">
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
                    <label class="form-label small">Nama</label>
                    <input type="text" name="ticket_categories[\${editTicketCategoryIndex}][name]" class="form-control border-0 bg-white" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga</label>
                    <input type="number" name="ticket_categories[\${editTicketCategoryIndex}][price]" class="form-control border-0 bg-white" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Stok</label>
                    <input type="number" name="ticket_categories[\${editTicketCategoryIndex}][stock]" class="form-control border-0 bg-white">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)">
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
    
    // Reset dan isi ticket categories
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
                            <label class="form-label small">Nama</label>
                            <input type="text" name="ticket_categories[${index}][name]" value="${ticket.category_name}" class="form-control border-0 bg-white" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Harga</label>
                            <input type="number" name="ticket_categories[${index}][price]" value="${ticket.price}" class="form-control border-0 bg-white" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Stok</label>
                            <input type="number" name="ticket_categories[${index}][stock]" value="${ticket.stock || ''}" class="form-control border-0 bg-white">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)">
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
