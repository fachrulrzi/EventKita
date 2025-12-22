@extends('layouts.admin')

@section('title', 'Moderasi Diskusi')
@section('page-title', 'Detail & Moderasi Diskusi')

@section('content')
<style>
    /* Custom Styling khusus Moderasi */
    .moderation-header {
        background: white;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #e2e8f0;
    }
    .discussion-main-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .discussion-content-box {
        background-color: #f8fafc;
        border-radius: 15px;
        padding: 20px;
        border: 1px solid #f1f5f9;
        font-size: 1.05rem;
        line-height: 1.6;
        color: #334155;
    }
    .author-avatar {
        width: 50px;
        height: 50px;
        border: 3px solid #eef2f7;
    }
    .reply-item {
        transition: background-color 0.2s;
        border-radius: 12px;
        padding: 15px;
    }
    .reply-item:hover {
        background-color: #f8fafc;
    }
    .meta-badge {
        background: #f1f5f9;
        color: #64748b;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 50px;
    }
    .btn-pin {
        border-radius: 50px;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-destructive {
        background-color: #fff1f2;
        color: #e11d48;
        border: 1px solid #fecdd3;
    }
    .btn-destructive:hover {
        background-color: #e11d48;
        color: white;
    }
</style>

<div class="container-fluid pb-5">
    <div class="moderation-header d-flex flex-wrap justify-content-between align-items-center mb-4 shadow-sm">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.forum.index') }}" class="text-decoration-none">Moderasi Forum</a></li>
                <li class="breadcrumb-item active fw-bold">Detail Diskusi</li>
            </ol>
        </nav>
        <a href="{{ route('admin.forum.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card discussion-main-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            @if($discussion->is_pinned)
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-3 shadow-sm">
                                    <i class="bi bi-pin-angle-fill me-1"></i> DIPASANG (PINNED)
                                </span>
                            @endif
                            <h2 class="fw-bold text-dark mb-1">{{ $discussion->title }}</h2>
                            <span class="meta-badge">
                                <i class="bi bi-calendar-event me-1"></i> {{ $discussion->event ? $discussion->event->title : 'Umum' }}
                            </span>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.forum.toggle-pin', $discussion->id) }}">
                                @csrf
                                <button type="submit" class="btn {{ $discussion->is_pinned ? 'btn-warning' : 'btn-outline-warning' }} btn-pin shadow-sm">
                                    <i class="bi bi-pin-angle-fill"></i> {{ $discussion->is_pinned ? 'Lepas Pin' : 'Pasang Pin' }}
                                </button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.forum.destroy', $discussion->id) }}" onsubmit="return confirm('PERINGATAN: Menghapus diskusi akan menghapus SEMUA balasan. Lanjutkan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-destructive btn-pin shadow-sm">
                                    <i class="bi bi-trash3"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name) }}&background=6366f1&color=fff" 
                             alt="Avatar" class="rounded-circle author-avatar me-3 shadow-sm">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $discussion->user->name }}</h6>
                            <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $discussion->user->email }}</small>
                        </div>
                        <div class="ms-auto text-end d-none d-md-block">
                            <small class="text-muted d-block fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Tanggal Posting</small>
                            <span class="fw-semibold small">{{ $discussion->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="discussion-content-box shadow-inner">
                        <p class="mb-0" style="white-space: pre-wrap;">{{ $discussion->content }}</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-chat-left-dots-fill text-primary me-2"></i> 
                        Balasan User <span class="badge bg-light text-primary border ms-2">{{ $discussion->replies->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="px-4 pb-4">
                        @forelse($discussion->replies as $reply)
                            <div class="reply-item border-bottom d-flex justify-content-between align-items-start gap-3">
                                <div class="d-flex gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random" 
                                         alt="Avatar" class="rounded-circle shadow-sm mt-1" width="40" height="40">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="fw-bold text-dark">{{ $reply->user->name }}</span>
                                            <span class="text-muted small">•</span>
                                            <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-secondary" style="font-size: 0.95rem;">{{ $reply->content }}</p>
                                    </div>
                                </div>
                                
                                <form method="POST" action="{{ route('admin.forum.reply.destroy', $reply->id) }}" onsubmit="return confirm('Hapus balasan ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle p-2" title="Hapus Balasan">
                                        <i class="bi bi-trash3 fs-6"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-square-dots text-muted opacity-25" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3 mb-0">Belum ada balasan dalam diskusi ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 20px; top: 100px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-4" style="letter-spacing: 1px;">Informasi Tambahan</h6>
                    
                    <div class="mb-4">
                        <small class="text-muted d-block mb-1">Status Diskusi</small>
                        @if($discussion->is_pinned)
                            <span class="badge w-100 bg-warning text-dark py-2 rounded-pill fw-bold">DIPRIORITASKAN</span>
                        @else
                            <span class="badge w-100 bg-light text-muted border py-2 rounded-pill fw-bold">STANDAR</span>
                        @endif
                    </div>

                    <div class="p-3 bg-light rounded-4 mb-3 border">
                        <small class="text-muted d-block">ID Diskusi</small>
                        <span class="fw-bold">#{{ $discussion->id }}</span>
                    </div>

                    <div class="p-3 bg-light rounded-4 border">
                        <small class="text-muted d-block">Rasio Balasan</small>
                        <span class="fw-bold text-primary">{{ $discussion->replies->count() }} Komentar</span>
                    </div>

                    <hr class="my-4 opacity-10">

                    <div class="bg-indigo-soft p-3 rounded-4" style="background: #f5f3ff;">
                        <h6 class="fw-bold text-indigo" style="color: #6366f1;"><i class="bi bi-shield-check me-2"></i>Tips Moderasi</h6>
                        <p class="small text-muted mb-0">Hapus diskusi yang melanggar SARA, ujaran kebencian, atau spam untuk menjaga komunitas tetap sehat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection