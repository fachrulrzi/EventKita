@extends('layouts.admin')

@section('title', 'Kelola Forum')
@section('page-title', 'Moderasi Forum & Diskusi')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #6366f1;
        --border-radius-lg: 1rem;
    }

    /* 1. FILTER CARD */
    .filter-card {
        background: white;
        border-radius: var(--border-radius-lg);
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .form-control-filter, .form-select-filter {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        border-radius: 8px;
        transition: 0.2s;
    }
    .form-control-filter:focus, .form-select-filter:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* 2. STATUS CARDS */
    .status-card-admin {
        background: white;
        border: none;
        border-radius: var(--border-radius-lg);
        padding: 24px;
        transition: transform 0.2s;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .status-card-admin:hover {
        transform: translateY(-3px);
    }
    .status-icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
    }

    /* 3. DISCUSSION LIST ROW */
    .discussion-row {
        background: white;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }
    .discussion-row:hover {
        border-color: var(--primary-color);
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.1);
        z-index: 1;
    }
    /* Pinned Style */
    .discussion-row.pinned {
        background-color: #fffbeb; /* Soft Yellow */
        border-color: #fcd34d;
    }
    .discussion-row.pinned::after {
        content: '';
        position: absolute;
        left: 0; top: 12px; bottom: 12px;
        width: 4px;
        background-color: #f59e0b;
        border-radius: 0 4px 4px 0;
    }

    /* 4. BULK ACTION BAR */
    .bulk-action-bar {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 12px 20px;
    }

    /* Action Buttons */
    .action-btn-group .btn {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
    }
    .action-btn-group .btn:hover {
        transform: scale(1.1);
    }
</style>

<div class="container-fluid pb-5">
    
    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4 px-4 py-3 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- FILTER SECTION --}}
    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3 text-uppercase text-muted small ls-1">Filter & Pencarian</h6>
            <form method="GET" action="{{ route('admin.forum.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 border text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control form-control-filter border-start-0 ps-0" 
                               placeholder="Cari judul diskusi atau nama user..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="event_id" class="form-select form-select-filter">
                        <option value="">Semua Event & Umum</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ Str::limit($event->title, 30) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select form-select-filter">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Tanggal</option>
                        <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Judul</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_order" class="form-select form-select-filter">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100 h-100 rounded-3 shadow-sm" title="Terapkan Filter">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="status-card-admin">
                <div class="status-icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-chat-dots-fill"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">{{ $discussions->total() }}</h2>
                    <p class="text-muted small fw-bold text-uppercase mb-0 ls-1">Total Diskusi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card-admin">
                <div class="status-icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-pin-angle-fill"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">{{ $discussions->where('is_pinned', true)->count() }}</h2>
                    <p class="text-muted small fw-bold text-uppercase mb-0 ls-1">Diskusi Dipasang</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="status-card-admin">
                <div class="status-icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-reply-all-fill"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0 text-dark">{{ $discussions->sum(fn($d) => $d->replies->count()) }}</h2>
                    <p class="text-muted small fw-bold text-uppercase mb-0 ls-1">Total Balasan</p>
                </div>
            </div>
        </div>
    </div>

    {{-- DISCUSSION LIST CARD --}}
    <div class="card border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body p-4">
            
            {{-- BULK ACTIONS FORM --}}
            <form id="bulkForm" method="POST" action="{{ route('admin.forum.bulk-destroy') }}">
                @csrf
                
                <div class="bulk-action-bar d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" id="selectAll" class="form-check-input border-secondary" style="cursor: pointer;">
                        <label class="form-check-label fw-bold text-dark ms-2" for="selectAll" style="cursor: pointer;">
                            Pilih Semua Diskusi
                        </label>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm" 
                            onclick="return confirm('Yakin ingin menghapus semua diskusi yang dipilih secara permanen?')">
                        <i class="bi bi-trash3 me-2"></i> Hapus Terpilih
                    </button>
                </div>

                <h5 class="fw-bold mb-4 text-dark d-flex align-items-center">
                    <i class="bi bi-list-ul me-2 text-primary"></i> Daftar Diskusi Aktif
                </h5>
                
                <div class="discussion-list-container">
                    @forelse($discussions as $discussion)
                        <div class="discussion-row p-3 mb-3 {{ $discussion->is_pinned ? 'pinned' : '' }}">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                
                                {{-- Checkbox & Content Wrapper --}}
                                <div class="d-flex gap-3 flex-grow-1">
                                    <div class="pt-1 ps-2">
                                        <input class="form-check-input discussion-checkbox border-secondary" type="checkbox" name="discussion_ids[]" value="{{ $discussion->id }}" form="bulkForm">
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        {{-- Header: Pinned & Title --}}
                                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                            @if($discussion->is_pinned)
                                                <span class="badge bg-warning text-dark border border-warning rounded-pill px-2" title="Pinned Thread">
                                                    <i class="bi bi-pin-fill"></i> PINNED
                                                </span>
                                            @endif
                                            <a href="{{ route('admin.forum.show', $discussion->id) }}" class="text-decoration-none text-dark hover-primary">
                                                <h6 class="fw-bold mb-0 text-break">{{ $discussion->title }}</h6>
                                            </a>
                                        </div>

                                        {{-- Snippet Content --}}
                                        <p class="text-secondary small mb-2 text-truncate" style="max-width: 650px;">
                                            {{ Str::limit($discussion->content, 120) }}
                                        </p>

                                        {{-- Meta Info --}}
                                        <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
                                            <span class="badge bg-light text-secondary border fw-normal">
                                                <i class="bi bi-person me-1"></i> {{ $discussion->user->name }}
                                            </span>
                                            <span class="small text-muted">
                                                <i class="bi bi-clock me-1"></i> {{ $discussion->created_at->format('d M Y') }}
                                            </span>
                                            <span class="small text-muted">
                                                <i class="bi bi-calendar-event me-1"></i> 
                                                {{ $discussion->event ? Str::limit($discussion->event->title, 20) : 'Umum' }}
                                            </span>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2">
                                                {{ $discussion->replies->count() }} balasan
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Action Buttons --}}
                                <div class="action-btn-group d-flex gap-2">
                                    <a href="{{ route('admin.forum.show', $discussion->id) }}" class="btn btn-primary bg-primary bg-opacity-10 text-primary border-0" title="Lihat Detail & Moderasi">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    
                                    {{-- Pin Toggle Form --}}
                                    <form method="POST" action="{{ route('admin.forum.toggle-pin', $discussion->id) }}">
                                        @csrf
                                        <button type="submit" class="btn {{ $discussion->is_pinned ? 'btn-warning text-white' : 'btn-light border text-warning' }}" 
                                                title="{{ $discussion->is_pinned ? 'Lepas Pin' : 'Pasang Pin' }}">
                                            <i class="bi bi-pin-angle-fill"></i>
                                        </button>
                                    </form>
                                    
                                    {{-- Delete Form --}}
                                    <form method="POST" action="{{ route('admin.forum.destroy', $discussion->id) }}" onsubmit="return confirm('Hapus diskusi ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger bg-danger bg-opacity-10 text-danger border-0" title="Hapus Diskusi">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @empty
                        {{-- Empty State --}}
                        <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                            <i class="bi bi-chat-square-quote text-muted opacity-25" style="font-size: 4rem;"></i>
                            <p class="text-muted mt-3 mb-0 fw-semibold">Tidak ada diskusi yang ditemukan sesuai filter.</p>
                            <a href="{{ route('admin.forum.index') }}" class="btn btn-link text-primary text-decoration-none mt-2">Reset Filter</a>
                        </div>
                    @endforelse
                </div>
            </form>

            {{-- PAGINATION --}}
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
            
            // Visual feedback: highlight row
            const row = checkbox.closest('.discussion-row');
            if (this.checked) {
                row.style.borderColor = '#6366f1';
                row.style.backgroundColor = '#f5f3ff';
            } else {
                row.style.borderColor = '#f1f5f9';
                // Restore pinned background if needed
                if (row.classList.contains('pinned')) {
                    row.style.backgroundColor = '#fffbeb';
                    row.style.borderColor = '#fcd34d';
                } else {
                    row.style.backgroundColor = '#fff';
                }
            }
        });
    });

    // Individual Checkbox Logic (Optional: update visuals on click)
    document.querySelectorAll('.discussion-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const row = this.closest('.discussion-row');
            if (this.checked) {
                row.style.borderColor = '#6366f1';
                row.style.backgroundColor = '#f5f3ff';
            } else {
                row.style.borderColor = '#f1f5f9';
                if (row.classList.contains('pinned')) {
                    row.style.backgroundColor = '#fffbeb';
                    row.style.borderColor = '#fcd34d';
                } else {
                    row.style.backgroundColor = '#fff';
                }
            }
        });
    });
</script>
@endpush

@endsection