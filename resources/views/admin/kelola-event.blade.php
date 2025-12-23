@extends('layouts.admin')

@section('title', 'Kelola Event')
@section('page-title', 'Kelola Event')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #6366f1;
        --border-radius-lg: 1rem;
    }

    /* 1. HERO CARD */
    .hero-card {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        border: none;
        border-radius: var(--border-radius-lg);
        color: white;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        position: relative;
        overflow: hidden;
    }
    .hero-card::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }

    /* 2. STATS CARD */
    .stat-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* 3. TABLE STYLING */
    .table-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .event-title {
        font-weight: 600;
        color: #1e293b;
        display: block;
    }
    .event-loc {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* 4. BADGES */
    .badge-status {
        padding: 6px 12px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .badge-published { background-color: #dcfce7; color: #166534; }
    .badge-draft { background-color: #f1f5f9; color: #475569; }
    .badge-featured { background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); color: white; border: none; }

    /* 5. MODAL & FORM */
    .modal-content-custom {
        border-radius: 1.5rem;
        border: none;
    }
    
    /* 6. CURSOR POINTER */
    .cursor-pointer {
        cursor: pointer;
    }
    .modal-header-custom {
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 30px;
    }
    .form-control, .form-select {
        border-radius: 0.75rem;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
</style>

{{-- ALERTS --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center rounded-3 px-4" role="alert">
        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
            <i class="bi bi-check-lg"></i>
        </div>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center rounded-3 px-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-4 me-3 text-danger"></i>
        <div><strong>Error!</strong> {{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- HERO SECTION --}}
<div class="card hero-card mb-4">
    <div class="card-body p-4 p-md-5 d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h3 class="fw-bold mb-1"><i class="bi bi-calendar-event me-2"></i>Manajemen Event</h3>
            <p class="text-white-50 mb-0">Kelola semua event, kategori tiket, dan status publikasi dengan mudah.</p>
        </div>
        <button class="btn btn-light rounded-pill px-4 py-2 fw-bold shadow-sm text-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Event
        </button>
    </div>
</div>

{{-- STATS GRID --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Total Event</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $events->count() }}</h3>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-layers-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Published</p>
                    <h3 class="fw-bold mb-0 text-success">{{ $events->where('status', 'published')->count() }}</h3>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Draft</p>
                    <h3 class="fw-bold mb-0 text-secondary">{{ $events->where('status', 'draft')->count() }}</h3>
                </div>
                <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted small fw-bold text-uppercase mb-1">Featured</p>
                    <h3 class="fw-bold mb-0 text-warning">{{ $events->where('is_featured', true)->count() }}</h3>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DATA TABLE --}}
<div class="card table-card mb-5">
    <div class="card-header bg-white py-3 px-4 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">Daftar Event</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Info Event</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Harga Mulai</th>
                        <th class="text-center">Status</th>
                        <th class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            <td class="ps-4">
                                <span class="event-title">{{ $event->title }}</span>
                                <span class="event-loc"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($event->location, 30) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal px-2 py-1">
                                    {{ $event->category->name }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-calendar3 me-2"></i> {{ $event->date->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">
                                    Rp {{ number_format($event->ticketCategories->min('price'), 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($event->is_featured)
                                    <span class="badge badge-status badge-featured mb-1">Featured</span>
                                    <br>
                                @endif
                                @if($event->status == 'published')
                                    <span class="badge badge-status badge-published">Published</span>
                                @else
                                    <span class="badge badge-status badge-draft">Draft</span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-sm btn-light border text-primary rounded-circle" 
                                            onclick="editEvent({{ $event->id }})" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.event.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger rounded-circle" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="bi bi-calendar-x text-muted opacity-25" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2 fw-semibold">Belum ada data event tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- MODAL TAMBAH EVENT --}}
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Event Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-uppercase text-muted">Nama Event <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Konser Musik Akbar" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-uppercase text-muted">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Kota <span class="text-danger">*</span></label>
                            <select name="city_id" class="form-select" required>
                                <option value="">Pilih Kota</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Alamat Lengkap</label>
                            <input type="text" name="location" class="form-control" placeholder="Gedung Serbaguna, Jl. Merdeka No. 1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Jam Mulai</label>
                            <input type="time" name="time_start" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Link Website</label>
                            <input type="url" name="website_url" class="form-control" placeholder="https://">
                        </div>

                        {{-- Ticket Section --}}
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border border-dashed mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold mb-0 text-dark"><i class="bi bi-ticket-perforated-fill me-2 text-primary"></i>Kategori Tiket</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" onclick="addTicketCategory()">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah
                                    </button>
                                </div>
                                <div id="ticketCategoriesContainer">
                                    {{-- Default Item --}}
                                    <div class="ticket-category-item card border-0 shadow-sm mb-3">
                                        <div class="card-body p-3">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="text" name="ticket_categories[0][name]" class="form-control form-control-sm" placeholder="Nama (e.g. VIP)" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="ticket_categories[0][price]" class="form-control form-control-sm" placeholder="Harga (Rp)" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="number" name="ticket_categories[0][stock]" class="form-control form-control-sm" placeholder="Stok">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeTicketCategory(this)" disabled><i class="bi bi-trash"></i></button>
                                                </div>
                                                <div class="col-12 mt-2">
                                                    <input type="text" name="ticket_categories[0][description]" class="form-control form-control-sm" placeholder="Deskripsi tiket (opsional)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Banner Event <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-uppercase text-muted">Deskripsi</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 bg-white border rounded-3 shadow-sm">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_featured" value="1" id="isFeatured" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark cursor-pointer" for="isFeatured">
                                    Set sebagai Featured Event
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Simpan Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT EVENT --}}
<div class="modal fade" id="updateEventModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateEventForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">Nama Event</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Kategori</label>
                            <select class="form-select" name="category_id" id="edit_category_id" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Tanggal</label>
                            <input type="date" name="date" id="edit_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Kota</label>
                            <select name="city_id" id="edit_city_id" class="form-select" required>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Lokasi</label>
                            <input type="text" name="location" id="edit_location" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Jam Mulai</label>
                            <input type="time" name="time_start" id="edit_time_start" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">Link Website</label>
                            <input type="url" name="website_url" id="edit_website_url" class="form-control">
                        </div>

                        {{-- Ticket Edit Container --}}
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border border-dashed mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label fw-bold mb-0 text-dark"><i class="bi bi-ticket-perforated-fill me-2 text-primary"></i>Edit Tiket</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" onclick="addEditTicketCategory()">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah
                                    </button>
                                </div>
                                <div id="editTicketCategoriesContainer"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Banner Event</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted fst-italic">Biarkan kosong jika tidak ingin mengubah.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">Deskripsi</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch p-3 bg-white border rounded-3 shadow-sm">
                                <input class="form-check-input ms-0 me-3" type="checkbox" name="is_featured" value="1" id="edit_is_featured" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                                <label class="form-check-label fw-bold text-dark cursor-pointer" for="edit_is_featured">Featured Event</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Update Event</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- JAVASCRIPT --}}
@push('scripts')
<script>
    // PREPARE DATA FOR JS
    @php
        $eventsJson = $events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'category_id' => $event->category_id,
                'city_id' => $event->city_id,
                'date' => $event->date->format('Y-m-d'),
                'location' => $event->location,
                'time_start' => $event->time_start,
                'website_url' => $event->website_url,
                'description' => $event->description,
                'is_featured' => $event->is_featured,
                'ticket_categories' => $event->ticketCategories->map(function($tc) {
                    return [
                        'id' => $tc->id,
                        'category_name' => $tc->category_name,
                        'price' => $tc->price,
                        'stock' => $tc->stock,
                        'description' => $tc->description,
                    ];
                })->toArray(),
            ];
        })->toArray();
    @endphp

    const eventsData = @json($eventsJson);
    let ticketCategoryIndex = 1;
    let editTicketCategoryIndex = 0;

    // --- ADD TICKET LOGIC ---
    function addTicketCategory() {
        const container = document.getElementById('ticketCategoriesContainer');
        const newCategory = `
            <div class="ticket-category-item card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="ticket_categories[${ticketCategoryIndex}][name]" class="form-control form-control-sm" placeholder="Nama" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="ticket_categories[${ticketCategoryIndex}][price]" class="form-control form-control-sm" placeholder="Harga" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="ticket_categories[${ticketCategoryIndex}][stock]" class="form-control form-control-sm" placeholder="Stok">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeTicketCategory(this)"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="col-12 mt-2">
                            <input type="text" name="ticket_categories[${ticketCategoryIndex}][description]" class="form-control form-control-sm" placeholder="Deskripsi">
                        </div>
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

    // --- EDIT EVENT LOGIC ---
    function addEditTicketCategory() {
        const container = document.getElementById('editTicketCategoriesContainer');
        editTicketCategoryIndex++; // Increment
        
        const newCategory = `
            <div class="ticket-category-item card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <input type="hidden" name="ticket_categories[${editTicketCategoryIndex}][id]" value="">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="ticket_categories[${editTicketCategoryIndex}][name]" class="form-control form-control-sm" placeholder="Nama" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="ticket_categories[${editTicketCategoryIndex}][price]" class="form-control form-control-sm" placeholder="Harga" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="ticket_categories[${editTicketCategoryIndex}][stock]" class="form-control form-control-sm" placeholder="Stok">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)"><i class="bi bi-trash"></i></button>
                        </div>
                        <div class="col-12 mt-2">
                            <input type="text" name="ticket_categories[${editTicketCategoryIndex}][description]" class="form-control form-control-sm" placeholder="Deskripsi">
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newCategory);
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
        document.getElementById('edit_date').value = event.date;
        document.getElementById('edit_city_id').value = event.city_id || '';
        document.getElementById('edit_location').value = event.location || '';
        document.getElementById('edit_time_start').value = event.time_start || '';
        document.getElementById('edit_website_url').value = event.website_url || '';
        document.getElementById('edit_description').value = event.description || '';
        document.getElementById('edit_is_featured').checked = event.is_featured;
        
        // Populate Ticket Categories
        const container = document.getElementById('editTicketCategoriesContainer');
        container.innerHTML = '';
        editTicketCategoryIndex = 0; 
        
        if (event.ticket_categories && event.ticket_categories.length > 0) {
            event.ticket_categories.forEach((ticket, index) => {
                editTicketCategoryIndex = index; 
                
                const ticketHtml = `
                    <div class="ticket-category-item card border-0 shadow-sm mb-3">
                        <div class="card-body p-3">
                            <input type="hidden" name="ticket_categories[${index}][id]" value="${ticket.id}">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="ticket_categories[${index}][name]" value="${ticket.category_name}" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="ticket_categories[${index}][price]" value="${ticket.price}" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="ticket_categories[${index}][stock]" value="${ticket.stock || ''}" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)"><i class="bi bi-trash"></i></button>
                                </div>
                                <div class="col-12 mt-2">
                                    <input type="text" name="ticket_categories[${index}][description]" value="${ticket.description || ''}" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', ticketHtml);
            });
        }
        
        new bootstrap.Modal(document.getElementById('updateEventModal')).show();
    }
</script>
@endpush