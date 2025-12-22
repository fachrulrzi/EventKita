@extends('layouts.admin')

@section('title', 'Kelola Forum')
@section('page-title', 'Moderasi Forum & Diskusi')

@section('content')
<style>
    /* Custom CSS untuk Index Forum Admin */
    .filter-card {
        border: none;
        border-radius: 15px;
        background: #fff;
    }
    .status-card-admin {
        border: none;
        border-radius: 15px;
        padding: 20px;
        background: #fff;
        transition: transform 0.3s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .status-card-admin:hover {
        transform: translateY(-5px);
    }
    .status-icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }
    .discussion-row {
        background: #fff;
        border-radius: 12px;
        transition: all 0.2s;
        border: 1px solid #f1f5f9;
    }
    .discussion-row:hover {
        border-color: #6366f1;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .discussion-row.pinned {
        background: #fffdf5;
        border-left: 4px solid #f59e0b;
    }
    .bulk-action-bar {
        background: #f8fafc;
        border-radius: 10px;
        padding: 12px 20px;
        border: 1px dashed #cbd5e1;
    }
    .action-btn-group .btn {
        width: 35px;
        height: 35px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>

<div class="container-fluid pb-5">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card filter-card shadow-sm mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-muted text-uppercase small" style="letter-spacing: 1px;">Filter & Pencarian</h6>
            <form method="GET" action="{{ route('admin.forum.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Cari judul atau nama user..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="event_id" class="form-select bg-light">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select bg-light">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Judul</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_order" class="form-select bg-light">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm">
                        <i class="bi bi-sliders"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="status-card-admin shadow-sm">
                <div class="status-icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-chat-left-dots"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ $discussions->total() }}</h2>
                <p class="text-muted small fw-bold text-uppercase mb-0">Total Diskusi</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card-admin shadow-sm">
                <div class="status-icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-pin-angle"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ $discussions->where('is_pinned', true)->count() }}</h2>
                <p class="text-muted small fw-bold text-uppercase mb-0">Diskusi Dipasang</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card-admin shadow-sm">
                <div class="status-icon-box bg-success-subtle text-success">
                    <i class="bi bi-reply"></i>
                </div>
                <h2 class="fw-bold mb-0">{{ $discussions->sum(fn($d) => $d->replies->count()) }}</h2>
                <p class="text-muted small fw-bold text-uppercase mb-0">Total Balasan</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            <form id="bulkForm" method="POST" action="{{ route('admin.forum.bulk-destroy') }}">
                @csrf
                <div class="bulk-action-bar d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" id="selectAll" class="form-check-input border-secondary">
                        <label class="form-check-label fw-bold text-dark ms-2" for="selectAll">Pilih Semua Diskusi</label>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Hapus semua diskusi yang dipilih?')">
                        <i class="bi bi-trash3 me-2"></i> Hapus Masal
                    </button>
                </div>

                <h5 class="fw-bold mb-4 text-dark"><i class="bi bi-list-ul me-2 text-primary"></i> Daftar Diskusi Aktif</h5>
                
                <div class="discussion-list-container">
                    @forelse($discussions as $discussion)
                        <div class="discussion-row p-3 mb-3 shadow-sm {{ $discussion->is_pinned ? 'pinned' : '' }}">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                                <div class="d-flex gap-3 flex-grow-1">
                                    <div class="pt-1">
                                        <input class="form-check-input discussion-checkbox border-secondary" type="checkbox" name="discussion_ids[]" value="{{ $discussion->id }}" form="bulkForm">
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            @if($discussion->is_pinned)
                                                <span class="badge bg-warning text-dark small py-1 px-2"><i class="bi bi-pin-fill"></i> PINNED</span>
                                            @endif
                                            <a href="{{ route('admin.forum.show', $discussion->id) }}" class="text-decoration-none">
                                                <h6 class="fw-bold mb-0 text-dark">{{ $discussion->title }}</h6>
                                            </a>
                                        </div>
                                        <p class="text-muted small mb-2 text-truncate" style="max-width: 600px;">
                                            {{ Str::limit($discussion->content, 120) }}
                                        </p>
                                        <div class="d-flex flex-wrap gap-3 mt-2">
                                            <span class="small text-muted"><i class="bi bi-person me-1"></i> {{ $discussion->user->name }}</span>
                                            <span class="small text-muted"><i class="bi bi-calendar3 me-1"></i> {{ $discussion->created_at->format('d M Y') }}</span>
                                            <span class="small text-muted"><i class="bi bi-calendar-event me-1"></i> {{ $discussion->event ? $discussion->event->title : 'Umum' }}</span>
                                            <span class="badge bg-light text-primary border rounded-pill px-3">{{ $discussion->replies->count() }} balasan</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="action-btn-group d-flex gap-2">
                                    <a href="{{ route('admin.forum.show', $discussion->id) }}" class="btn btn-primary" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.forum.toggle-pin', $discussion->id) }}">
                                        @csrf
                                        <button type="submit" class="btn {{ $discussion->is_pinned ? 'btn-warning' : 'btn-outline-warning' }}" title="{{ $discussion->is_pinned ? 'Unpin' : 'Pin' }}">
                                            <i class="bi bi-pin-angle-fill"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forum.destroy', $discussion->id) }}" onsubmit="return confirm('Hapus diskusi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 bg-light rounded-4">
                            <i class="bi bi-chat-square-dots text-muted opacity-25" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3 fw-bold">Tidak ada diskusi yang ditemukan.</p>
                            <a href="{{ route('admin.forum.index') }}" class="btn btn-link text-primary text-decoration-none">Reset Filter</a>
                        </div>
                    @endforelse
                </div>
            </form>

            <div class="mt-5 d-flex justify-content-center">
                {{ $discussions->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select All Checkbox Logic
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.discussion-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
            // Visual feedback: toggle class on parent row
            const row = checkbox.closest('.discussion-row');
            if (this.checked) {
                row.style.borderColor = '#6366f1';
                row.style.background = '#f5f3ff';
            } else {
                row.style.borderColor = '#f1f5f9';
                row.style.background = (row.classList.contains('pinned')) ? '#fffdf5' : '#fff';
            }
        });
    });
</script>
@endpush

@endsection