@extends('layouts.admin')

@section('title', 'Kelola Event')
@section('page-title', 'Kelola Event')

@section('content')
<style>
    .event-card {
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }
    .event-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .badge-featured {
        background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
    }
</style>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 1rem;">
        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
        <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 1rem;">
        <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
        <div><strong>Error!</strong> {{ session('error') }}</div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="text-white fw-bold mb-2"><i class="bi bi-calendar-event me-2"></i>Manajemen Event</h4>
                <p class="text-white-50 mb-0">Kelola semua event, kategori tiket, dan status publikasi</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-light btn-lg rounded-pill fw-bold shadow" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Event
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Total Event</p>
                        <h3 class="fw-bold mb-0">{{ $events->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-calendar-event text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Published</p>
                        <h3 class="fw-bold mb-0">{{ $events->where('status', 'published')->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-check-circle text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Draft</p>
                        <h3 class="fw-bold mb-0">{{ $events->where('status', 'draft')->count() }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-pencil-square text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1 text-uppercase fw-bold">Featured</p>
                        <h3 class="fw-bold mb-0">{{ $events->where('is_featured', true)->count() }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-star-fill text-danger fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 px-4 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">Data Event Terdaftar</h5>
                    <small class="text-muted">Kelola seluruh status event dan iklan di sini</small>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Event</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Harga</th>
                                <th class="py-3 text-center">Status</th>
                                <th class="py-3 text-center pe-4">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $event->title }}</div>
                                    <small class="text-muted">{{ Str::limit($event->location, 30) }}</small>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary border px-2 py-1">{{ $event->category->name }}</span></td>
                                <td><i class="bi bi-calendar3 me-2"></i>{{ $event->date->format('d M Y') }}</td>
                                <td class="fw-bold text-dark">Rp {{ number_format($event->ticketCategories->min('price'), 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($event->is_featured)
                                        <span class="badge bg-warning text-dark rounded-pill px-2">Featured</span>
                                    @endif
                                    @if($event->status == 'published')
                                        <span class="badge bg-success rounded-pill px-2">Published</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-2">Draft</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" onclick="editEvent({{ $event->id }})" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('admin.event.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 opacity-25 d-block mb-3"></i>
                                    Belum ada data event tersedia.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="text-muted small fw-bold text-uppercase mb-1">Kategori</p>
                        <small class="text-muted small">Kategori Aktif</small>
                    </div>
                    <button class="btn btn-sm btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah
                    </button>
                </div>

                @isset($categories)
                    <div class="list-group list-group-flush">
                        @forelse($categories->take(8) as $cat)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <div class="fw-bold">{{ $cat->name }}</div>
                                        <small class="text-muted">{{ $cat->slug }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-update-url="{{ route('admin.kategori.update', $cat) }}" data-name="{{ $cat->name }}" data-description="{{ $cat->description }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">Belum ada kategori.</div>
                        @endforelse
                    </div>
                @else
                    <div class="text-center py-3">
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-primary btn-sm">Kelola Kategori</a>
                    </div>
                @endisset

            </div>
        </div>
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
                                <div>
                                    <label class="form-label fw-bold mb-0"><i class="bi bi-ticket-perforated-fill text-primary me-2"></i>Kategori Tiket</label>
                                    <small class="text-muted d-block">Opsional - kosongkan jika event gratis</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill" onclick="addTicketCategory()">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                            <div id="ticketCategoriesContainer">
                                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="form-label small">Nama Kategori</label>
                                            <input type="text" name="ticket_categories[0][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small">Harga (Rp)</label>
                                            <input type="number" name="ticket_categories[0][price]" class="form-control border-0 bg-white" placeholder="0">
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
                                <div>
                                    <label class="form-label fw-bold mb-0"><i class="bi bi-ticket-perforated-fill text-primary me-2"></i>Kategori Tiket</label>
                                    <small class="text-muted d-block">Opsional - kosongkan jika event gratis</small>
                                </div>
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

{{-- MODAL TAMBAH KATEGORI (Sidebar) --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="categoryForm" method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Konser Musik" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan jenis event ini..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Icon Kategori</label>
                        <input type="file" name="icon" accept="image/*" class="form-control">
                        <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks 2MB.</small>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT KATEGORI (Sidebar) --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="modal-title fw-bold">Perbarui Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Ubah Icon</label>
                        <input type="file" name="icon" accept="image/*" class="form-control">
                        <small class="text-muted d-block mt-2">Biarkan kosong jika tidak ingin mengubah icon.</small>
                    </div>
                    <div class="mt-2 text-end">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow">Perbarui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
<script>
// Sinkronisasi data dari PHP ke JS
const eventsData = @json($eventsJson);

console.log('Events Data:', eventsData); // Debug log

// Counter untuk form add ticket category
let ticketCategoryIndex = 1;
let editTicketCategoryIndex = 0;

function addTicketCategory() {
    const container = document.getElementById('ticketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="ticket_categories[${ticketCategoryIndex}][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[${ticketCategoryIndex}][price]" class="form-control border-0 bg-white" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Stok (Opsional)</label>
                    <input type="number" name="ticket_categories[${ticketCategoryIndex}][stock]" class="form-control border-0 bg-white" placeholder="Unlimited">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeTicketCategory(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="col-12">
                    <label class="form-label small">Deskripsi (Opsional)</label>
                    <input type="text" name="ticket_categories[${ticketCategoryIndex}][description]" class="form-control border-0 bg-white" placeholder="Detail manfaat kategori ini">
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
    // Hitung index baru berdasarkan jumlah item yang ada untuk menghindari duplikasi ID
    const currentItems = container.querySelectorAll('.ticket-category-item').length;
    // Gunakan timestamp untuk memastikan index unik jika perlu, atau lanjutkan counter
    editTicketCategoryIndex++; 
    
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <input type="hidden" name="ticket_categories[${editTicketCategoryIndex}][id]" value="">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="ticket_categories[${editTicketCategoryIndex}][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[${editTicketCategoryIndex}][price]" class="form-control border-0 bg-white" placeholder="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Stok (Opsional)</label>
                    <input type="number" name="ticket_categories[${editTicketCategoryIndex}][stock]" class="form-control border-0 bg-white" placeholder="Unlimited">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="col-12">
                    <label class="form-label small">Deskripsi (Opsional)</label>
                    <input type="text" name="ticket_categories[${editTicketCategoryIndex}][description]" class="form-control border-0 bg-white" placeholder="Detail manfaat kategori ini">
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newCategory);
}

function removeEditTicketCategory(button) {
    // Tambahkan input hidden untuk menandai kategori dihapus (opsional jika backend handle delete)
    // Untuk simplifikasi saat ini, kita hapus elemennya dari DOM. 
    // Backend akan sync ulang berdasarkan ID yang dikirim.
    // Jika ID ada di input hidden, kita harus kirim ID tersebut ke backend untuk dihapus (soft delete logic).
    
    const item = button.closest('.ticket-category-item');
    const idInput = item.querySelector('input[type="hidden"]');
    
    if (idInput && idInput.value) {
        // Jika ini kategori lama yang dihapus, tambahkan input hidden ke form utama untuk delete
        const form = document.getElementById('updateEventForm');
        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_categories[]';
        deleteInput.value = idInput.value;
        form.appendChild(deleteInput);
    }
    
    item.remove();
}

function editEvent(eventId) {
    const event = eventsData.find(e => e.id === eventId);
    if (!event) return;

    // Isi form edit modal
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

    // Load ticket categories untuk edit
    const container = document.getElementById('editTicketCategoriesContainer');
    container.innerHTML = '';
    
    // Reset index counter based on existing data
    editTicketCategoryIndex = 0;

    if (event.ticket_categories && event.ticket_categories.length > 0) {
        event.ticket_categories.forEach((ticket, index) => {
            // Kita gunakan index loop agar urutannya rapi dari 0
            editTicketCategoryIndex = index; 
            
            const ticketHtml = `
                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                    <input type="hidden" name="ticket_categories[${index}][id]" value="${ticket.id}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small">Nama Kategori</label>
                            <input type="text" name="ticket_categories[${index}][name]" class="form-control border-0 bg-white" value="${ticket.category_name}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Harga (Rp)</label>
                            <input type="number" name="ticket_categories[${index}][price]" class="form-control border-0 bg-white" value="${ticket.price}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Stok (Opsional)</label>
                            <input type="number" name="ticket_categories[${index}][stock]" class="form-control border-0 bg-white" value="${ticket.stock || ''}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Deskripsi (Opsional)</label>
                            <input type="text" name="ticket_categories[${index}][description]" class="form-control border-0 bg-white" value="${ticket.description || ''}">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', ticketHtml);
        });
    }

    // Show modal
    new bootstrap.Modal(document.getElementById('updateEventModal')).show();
}

// Logic Modal Edit Kategori (untuk sidebar)
const editCategoryModal = document.getElementById('editCategoryModal');
if (editCategoryModal) {
    editCategoryModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const updateUrl = button.getAttribute('data-update-url');
        const name = button.getAttribute('data-name') || '';
        const description = button.getAttribute('data-description') || '';

        const form = document.getElementById('editCategoryForm');
        form.action = updateUrl;
        form.querySelector('input[name="name"]').value = name;
        form.querySelector('textarea[name="description"]').value = description;
    });
}
</script>
@endpush