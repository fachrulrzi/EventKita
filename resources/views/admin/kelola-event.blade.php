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
    .event-image-preview {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
    }
    .badge-featured {
        position: absolute;
        top: 10px;
        right: 10px;
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
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 1rem;">
        <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
        <div><strong>Error!</strong> {{ session('error') }}</div>
    </div>
@endif

<!-- Header Section -->
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

<!-- Statistics Cards -->
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

<!-- Events Grid -->
<div class="row g-4">
    @forelse($events as $event)
        <div class="col-lg-4 col-md-6">
            <div class="card event-card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <!-- Event Image -->
                    <div class="position-relative">
                        @if($event->image_path)
                            @php
                                $adminEventImgUrl = (str_starts_with($event->image_path, 'http')) 
                                    ? $event->image_path 
                                    : 'https://compact-lounge-fhpy7wqjz9.storage.railway.app/' . $event->image_path;
                            @endphp
                            <img src="{{ $adminEventImgUrl }}" class="event-image-preview" alt="{{ $event->title }}">
                        @else
                            <div class="event-image-preview bg-light d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        
                        @if($event->is_featured)
                            <div class="badge-featured">
                                <i class="bi bi-star-fill me-1"></i>FEATURED
                            </div>
                        @endif
                    </div>

                    <!-- Event Content -->
                    <div class="p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-{{ $event->status === 'published' ? 'success' : 'warning' }} rounded-pill">
                                {{ ucfirst($event->status) }}
                            </span>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                {{ $event->category->name }}
                            </span>
                        </div>

                        <h5 class="fw-bold mb-2">{{ Str::limit($event->title, 40) }}</h5>
                        
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-calendar3 me-1"></i>{{ $event->date->format('d M Y') }}
                            </small>
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-clock me-1"></i>{{ $event->time }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="bi bi-geo-alt me-1"></i>{{ $event->cityRelation->name ?? $event->location }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted fw-bold">Kategori Tiket:</small>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @foreach($event->ticketCategories as $ticket)
                                    <span class="badge bg-light text-dark border">{{ $ticket->name }} - Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-pill flex-grow-1" onclick="editEvent({{ $event->id }})">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="deleteEvent({{ $event->id }})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <img src="https://illustrations.popsy.co/violet/event.svg" alt="empty" style="width: 200px;" class="mb-4 opacity-75">
                    <h5 class="fw-bold mb-2">Belum Ada Event</h5>
                    <p class="text-muted mb-4">Mulai buat event pertama Anda sekarang!</p>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addEventModal">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Event
                    </button>
                </div>
            </div>
        </div>
    @endforelse
</div>

<!-- Include Modal Tambah Event dari dashboard.blade.php -->
@include('admin.partials.event-modal')

<!-- Include Modal Edit Event dari dashboard.blade.php -->
@include('admin.partials.event-edit-modal')

<!-- Form Delete Event (Hidden) -->
<form id="deleteEventForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
// Sinkronisasi data dari PHP ke JS
const eventsData = @json($events);

function editEvent(eventId) {
    const event = eventsData.find(e => e.id === eventId);
    if (!event) return;

    // Isi form edit modal
    document.getElementById('editEventForm').action = `/admin/event/${eventId}`;
    document.querySelector('[name="edit_title"]').value = event.title;
    document.querySelector('[name="edit_category_id"]').value = event.category_id;
    document.querySelector('[name="edit_date"]').value = event.date.split('T')[0];
    document.querySelector('[name="edit_city_id"]').value = event.city_id || '';
    document.querySelector('[name="edit_location"]').value = event.location || '';
    document.querySelector('[name="edit_time"]').value = event.time || '';
    document.querySelector('[name="edit_website_url"]').value = event.website_url || '';
    document.querySelector('[name="edit_description"]').value = event.description || '';
    document.querySelector('[name="edit_status"]').value = event.status;
    document.getElementById('editIsFeatured').checked = event.is_featured;

    // Load ticket categories untuk edit
    const container = document.getElementById('editTicketCategoriesContainer');
    container.innerHTML = '';
    
    if (event.ticket_categories && event.ticket_categories.length > 0) {
        event.ticket_categories.forEach((ticket, index) => {
            const ticketHtml = `
                <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
                    <input type="hidden" name="ticket_categories[${index}][id]" value="${ticket.id}">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small">Nama Kategori</label>
                            <input type="text" name="ticket_categories[${index}][name]" class="form-control border-0 bg-white" value="${ticket.name}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Harga (Rp)</label>
                            <input type="number" name="ticket_categories[${index}][price]" class="form-control border-0 bg-white" value="${ticket.price}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Stok (Opsional)</label>
                            <input type="number" name="ticket_categories[${index}][stock]" class="form-control border-0 bg-white" value="${ticket.stock || ''}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeEditTicketCategory(this)" ${index === 0 ? 'disabled' : ''}>
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
    new bootstrap.Modal(document.getElementById('editEventModal')).show();
}

function deleteEvent(eventId) {
    if (confirm('Apakah Anda yakin ingin menghapus event ini? Semua data terkait akan hilang.')) {
        const form = document.getElementById('deleteEventForm');
        form.action = `/admin/event/${eventId}`;
        form.submit();
    }
}

// Counter untuk form add ticket category
let ticketCategoryIndex = 1;
let editTicketCategoryIndex = 0;

// Fungsi Add Ticket Category (Form Tambah Event)
function addTicketCategory() {
    const container = document.getElementById('ticketCategoriesContainer');
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="ticket_categories[${ticketCategoryIndex}][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[${ticketCategoryIndex}][price]" class="form-control border-0 bg-white" placeholder="0" required>
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

// Fungsi Add Ticket Category (Form Edit Event)
function addEditTicketCategory() {
    const container = document.getElementById('editTicketCategoriesContainer');
    editTicketCategoryIndex = container.querySelectorAll('.ticket-category-item').length;
    
    const newCategory = `
        <div class="ticket-category-item card border-0 bg-light p-3 mb-3">
            <input type="hidden" name="ticket_categories[${editTicketCategoryIndex}][id]" value="">
            <div class="row g-2">
                <div class="col-md-4">
                    <label class="form-label small">Nama Kategori</label>
                    <input type="text" name="ticket_categories[${editTicketCategoryIndex}][name]" class="form-control border-0 bg-white" placeholder="e.g. VIP, Regular" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Harga (Rp)</label>
                    <input type="number" name="ticket_categories[${editTicketCategoryIndex}][price]" class="form-control border-0 bg-white" placeholder="0" required>
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
    const container = document.getElementById('editTicketCategoriesContainer');
    if (container.querySelectorAll('.ticket-category-item').length > 1) {
        button.closest('.ticket-category-item').remove();
    }
}
</script>
@endsection
