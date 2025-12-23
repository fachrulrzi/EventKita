@extends('layouts.admin')

@section('title', 'Moderasi Diskusi')
@section('page-title', 'Detail & Moderasi Diskusi')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #6366f1; /* Admin Theme Color */
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --bg-soft: #f8fafc;
        --border-radius-lg: 1rem;
    }

    /* 1. HEADER SECTION */
    .moderation-header {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 20px 25px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 25px;
    }

    /* 2. MAIN CARD */
    .discussion-card {
        background: white;
        border-radius: var(--border-radius-lg);
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .author-section {
        background: var(--bg-soft);
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
    }
    .author-avatar {
        width: 48px;
        height: 48px;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .discussion-content {
        font-size: 1.05rem;
        line-height: 1.7;
        color: #334155;
        background: #fff;
        padding: 24px;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        margin-top: 20px;
    }

    /* 3. REPLIES SECTION */
    .reply-item {
        transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
        padding: 20px;
    }
    .reply-item:last-child {
        border-bottom: none;
    }
    .reply-item:hover {
        background-color: #f8fafc;
    }
    .reply-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* 4. BUTTONS & BADGES */
    .btn-action {
        border-radius: 50px;
        font-weight: 600;
        padding: 8px 20px;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-pin-active {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fcd34d;
    }
    .btn-pin-active:hover {
        background-color: #fde68a;
    }
    
    .btn-destructive {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .btn-destructive:hover {
        background-color: #fee2e2;
    }

    .badge-meta {
        background: #f1f5f9;
        color: #64748b;
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
    }

    /* Sidebar Sticky */
    .sticky-sidebar {
        position: sticky;
        top: 100px;
    }
</style>

<div class="container-fluid pb-5">
    
    {{-- HEADER & BREADCRUMB --}}
    <div class="moderation-header d-flex flex-wrap justify-content-between align-items-center gap-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.forum.index') }}" class="text-decoration-none text-muted">Moderasi Forum</a></li>
                <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Detail Diskusi</li>
            </ol>
        </nav>
        <a href="{{ route('admin.forum.index') }}" class="btn btn-light btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center rounded-3 px-4 py-3" role="alert">
            <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <strong>Berhasil!</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- MAIN CONTENT --}}
        <div class="col-lg-8">
            <div class="discussion-card mb-4">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Title & Meta --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($discussion->is_pinned)
                                    <span class="badge bg-warning text-dark border border-warning rounded-pill px-2">
                                        <i class="bi bi-pin-fill me-1"></i> PINNED
                                    </span>
                                @endif
                                <span class="badge-meta">
                                    <i class="bi bi-calendar-event me-1"></i> {{ $discussion->event ? $discussion->event->title : 'Topik Umum' }}
                                </span>
                            </div>
                            <h2 class="fw-bold text-dark mb-0 lh-sm">{{ $discussion->title }}</h2>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.forum.toggle-pin', $discussion->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-action {{ $discussion->is_pinned ? 'btn-pin-active' : 'btn-light border' }} shadow-sm">
                                    <i class="bi bi-pin-angle{{ $discussion->is_pinned ? '-fill' : '' }}"></i> 
                                    {{ $discussion->is_pinned ? 'Lepas Pin' : 'Pin' }}
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.forum.destroy', $discussion->id) }}" onsubmit="return confirm('PERINGATAN: Menghapus diskusi akan menghapus SEMUA balasan. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-action btn-destructive shadow-sm">
                                    <i class="bi bi-trash3"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Author Info --}}
                    <div class="author-section d-flex align-items-center gap-3 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name) }}&background=6366f1&color=fff&bold=true" 
                             alt="Avatar" class="rounded-circle author-avatar">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold text-dark">{{ $discussion->user->name }}</h6>
                            <small class="text-muted">{{ $discussion->user->email }}</small>
                        </div>
                        <div class="text-end d-none d-sm-block">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Diposting</small>
                            <div class="fw-semibold small text-dark">{{ $discussion->created_at->format('d M Y, H:i') }}</div>
                        </div>
                    </div>

                    {{-- Content Body --}}
                    <div class="discussion-content shadow-sm">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $discussion->content }}</p>
                    </div>
                </div>
            </div>

            {{-- REPLIES SECTION --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                        <i class="bi bi-chat-square-text-fill text-primary me-2"></i> 
                        Balasan User <span class="badge bg-primary-subtle text-primary ms-2 rounded-pill">{{ $discussion->replies->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @forelse($discussion->replies as $reply)
                        <div class="reply-item d-flex gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random" 
                                 alt="Avatar" class="reply-avatar shadow-sm flex-shrink-0">
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <div>
                                        <span class="fw-bold text-dark me-2">{{ $reply->user->name }}</span>
                                        <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    {{-- Delete Reply Button --}}
                                    <form method="POST" action="{{ route('admin.forum.reply.destroy', $reply->id) }}" onsubmit="return confirm('Hapus balasan ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm text-danger p-0 border-0" title="Hapus Komentar" style="opacity: 0.6; transition: 0.2s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.6">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                                <p class="mb-0 text-secondary small" style="line-height: 1.5;">{{ $reply->content }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-chat-left-dots text-muted opacity-25" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2 mb-0 small">Belum ada balasan pada diskusi ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- SIDEBAR INFO --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-sidebar">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase text-muted small mb-4 ls-1">Informasi Status</h6>
                    
                    <div class="mb-4">
                        <small class="text-muted d-block mb-2">Status Prioritas</small>
                        @if($discussion->is_pinned)
                            <div class="p-3 bg-warning bg-opacity-10 border border-warning rounded-3 text-center">
                                <i class="bi bi-pin-angle-fill text-warning fs-4 d-block mb-1"></i>
                                <span class="fw-bold text-dark">DISKUSI DIPRIORITASKAN</span>
                            </div>
                        @else
                            <div class="p-3 bg-light border rounded-3 text-center">
                                <span class="fw-bold text-muted">DISKUSI STANDAR</span>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span class="text-muted small">ID Diskusi</span>
                        <span class="fw-mono fw-bold text-dark">#{{ $discussion->id }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted small">Total Interaksi</span>
                        <span class="fw-bold text-primary">{{ $discussion->replies->count() }} Balasan</span>
                    </div>

                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 mt-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-shield-check text-primary fs-5"></i>
                            <div>
                                <h6 class="fw-bold text-primary mb-1">Tips Moderasi</h6>
                                <p class="small text-muted mb-0 lh-sm">
                                    Hapus diskusi yang mengandung SARA, ujaran kebencian, atau spam untuk menjaga komunitas tetap sehat.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection