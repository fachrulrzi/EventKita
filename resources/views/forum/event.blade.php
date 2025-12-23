@extends('layouts.app')

@section('title', 'Forum Diskusi - ' . $event->title)

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. HEADER AREA */
    .forum-header-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 30px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        position: relative;
        overflow: hidden;
    }
    .forum-header-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 6px; height: 100%;
        background: linear-gradient(to bottom, var(--primary-color), #6366f1);
    }

    /* 2. DISCUSSION CARD */
    .discussion-card {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        background: white;
        transition: all 0.25s cubic-bezier(0.075, 0.82, 0.165, 1);
        position: relative;
    }
    .discussion-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: rgba(13, 110, 253, 0.3);
    }
    
    /* Pinned Styling */
    .discussion-card.pinned {
        background: #fffbf0; /* Soft Yellow for pinned */
        border-color: #ffeeba;
    }
    .discussion-card.pinned .pin-icon {
        color: #ffc107;
        transform: rotate(45deg);
    }

    /* Avatar Styling (Mempertahankan Logika Initials) */
    .avatar-initial {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
        color: #495057;
        font-weight: 700;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .pinned .avatar-initial {
        background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
        color: #fff;
    }

    /* Reply Counter */
    .reply-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 12px;
        padding: 8px 16px;
        min-width: 70px;
        border: 1px solid #e9ecef;
    }
    .reply-count {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--primary-color);
        line-height: 1;
    }
    .reply-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        color: #adb5bd;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 4px;
    }

    /* 3. MODAL & FORM */
    .modal-content-custom {
        border-radius: 20px;
        border: none;
        overflow: hidden;
    }
    .modal-header-custom {
        background: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
    }
    .form-control-custom {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 12px 15px;
        border-radius: 10px;
    }
    .form-control-custom:focus {
        background: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    /* Breadcrumb Button */
    .btn-back-link {
        color: #6c757d;
        font-weight: 500;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
    }
    .btn-back-link:hover {
        color: var(--primary-color);
        transform: translateX(-3px);
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            
            {{-- Breadcrumb Nav --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <a href="{{ route('event.detail', $event->slug) }}" class="text-decoration-none btn-back-link">
                    <i class="bi bi-arrow-left-circle me-2 fs-5"></i> Kembali ke Detail Event
                </a>
            </nav>

            {{-- Header Section --}}
            <div class="forum-header-card mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                            <i class="bi bi-people-fill me-1"></i> Community Space
                        </span>
                    </div>
                    <h2 class="fw-bold mb-1 text-dark">Forum Diskusi</h2>
                    <p class="text-muted mb-0">
                        Topik untuk event: <span class="fw-bold text-dark">{{ $event->title }}</span>
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                        <i class="bi bi-plus-circle me-2"></i> Buat Topik Baru
                    </button>
                </div>
            </div>

            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4 rounded-3 d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Discussion List --}}
            @if($discussions->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($discussions as $discussion)
                        <div class="discussion-card {{ $discussion->is_pinned ? 'pinned shadow-sm' : '' }}">
                            <a href="{{ route('forum.show', $discussion->id) }}" class="text-decoration-none text-dark d-block p-4">
                                <div class="row align-items-start g-0">
                                    
                                    {{-- Avatar Column --}}
                                    <div class="col-auto pe-3">
                                        <div class="avatar-initial">
                                            {{-- Logika Initials Dipertahankan --}}
                                            {{ strtoupper(substr($discussion->user->name, 0, 1)) }}
                                        </div>
                                    </div>

                                    {{-- Content Column --}}
                                    <div class="col">
                                        <div class="mb-1 d-flex align-items-center flex-wrap gap-2">
                                            @if($discussion->is_pinned)
                                                <span class="badge bg-warning text-dark border border-warning rounded-pill px-2" title="Pinned Thread">
                                                    <i class="bi bi-pin-angle-fill pin-icon"></i> Pinned
                                                </span>
                                            @endif
                                            <h5 class="fw-bold mb-0 text-break">{{ $discussion->title }}</h5>
                                        </div>
                                        
                                        <p class="text-secondary mb-3 small text-break" style="line-height: 1.6;">
                                            {{ Str::limit($discussion->content, 140) }}
                                        </p>

                                        <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.85rem;">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-1 text-primary opacity-50"></i> 
                                                <span class="fw-medium">{{ $discussion->user->name }}</span>
                                            </span>
                                            <span class="d-none d-sm-inline opacity-25">|</span>
                                            <span class="d-flex align-items-center" title="{{ $discussion->created_at->format('d M Y H:i') }}">
                                                <i class="bi bi-clock me-1"></i> 
                                                {{ $discussion->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Stats Column (Desktop) --}}
                                    <div class="col-auto ps-3 d-none d-sm-block">
                                        <div class="reply-badge">
                                            <span class="reply-count">{{ $discussion->replies_count }}</span>
                                            <span class="reply-label">Balasan</span>
                                        </div>
                                    </div>
                                    
                                    {{-- Stats (Mobile Only) --}}
                                    <div class="col-12 mt-3 d-sm-none border-top pt-2">
                                        <div class="d-flex align-items-center text-primary fw-bold small">
                                            <i class="bi bi-chat-text me-2"></i> {{ $discussion->replies_count }} Balasan
                                        </div>
                                    </div>

                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $discussions->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <div class="mb-4">
                        <i class="bi bi-chat-square-quote text-secondary opacity-10" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Belum Ada Diskusi</h4>
                    <p class="text-muted mb-4">Ruang ini masih sepi. Jadilah yang pertama memulai obrolan!</p>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                        Mulai Diskusi Sekarang
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL CREATE DISCUSSION --}}
<div class="modal fade" id="createDiscussionModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-content-custom shadow-lg">
            <form action="{{ route('forum.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                
                <div class="modal-header modal-header-custom">
                    <div>
                        <h5 class="modal-title fw-bold text-dark">Buat Topik Diskusi Baru</h5>
                        <p class="mb-0 small text-muted">Diskusikan hal menarik seputar event ini.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold text-dark">Judul Diskusi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-custom form-control-lg @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="Contoh: Info parkir di lokasi event?" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2">
                        <label for="content" class="form-label fw-bold text-dark">Detail Pertanyaan/Info <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="6" 
                                  placeholder="Jelaskan lebih detail apa yang ingin kamu bahas..." required>{{ old('content') }}</textarea>
                        <div class="form-text text-muted text-end small mt-1">Gunakan bahasa yang sopan dan jelas.</div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                        <i class="bi bi-send-fill me-2"></i> Posting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection