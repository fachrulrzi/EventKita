@extends('layouts.app')

@section('title', $discussion->title)

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
        --border-radius-xl: 1.25rem;
    }

    /* 1. DISCUSSION MAIN CARD */
    .discussion-main-card {
        border: none;
        border-radius: var(--border-radius-xl);
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    /* Decorative Line */
    .discussion-main-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 6px;
        background: linear-gradient(90deg, var(--primary-color), #6366f1);
    }
    
    .badge-pinned {
        background-color: #fff8e1;
        color: #b7791f;
        border: 1px solid #fbd38d;
    }
    
    .badge-event {
        background-color: rgba(13, 110, 253, 0.08);
        color: var(--primary-color);
        border: 1px solid rgba(13, 110, 253, 0.15);
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Avatar Main */
    .user-avatar-main {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* Content Typography */
    .content-area {
        font-size: 1.05rem;
        line-height: 1.75;
        color: #374151;
    }

    /* 2. REPLY FORM CARD */
    .reply-form-card {
        border-radius: var(--border-radius-lg);
        border: 2px solid #f1f5f9;
        background-color: #fff;
        transition: 0.3s;
    }
    .reply-form-card:focus-within {
        border-color: rgba(13, 110, 253, 0.4);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .form-control-reply {
        background-color: transparent;
        border: none;
        resize: none;
    }
    .form-control-reply:focus {
        background-color: transparent;
        box-shadow: none;
    }

    /* 3. REPLY BUBBLES */
    .reply-item {
        margin-bottom: 24px;
        animation: fadeIn 0.5s ease;
    }
    .user-avatar-reply {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .reply-bubble {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0 16px 16px 16px;
        padding: 20px;
        position: relative;
    }
    /* Arrow Bubble */
    .reply-bubble::before {
        content: '';
        position: absolute;
        top: 0; left: -8px;
        width: 0; height: 0;
        border-style: solid;
        border-width: 0 10px 10px 0;
        border-color: transparent #f8fafc transparent transparent;
    }
    /* Fix for border */
    .reply-bubble::after {
        content: '';
        position: absolute;
        top: -1px; left: -9px;
        width: 0; height: 0;
        border-style: solid;
        border-width: 0 10px 10px 0;
        border-color: transparent #e2e8f0 transparent transparent;
        z-index: -1;
    }

    .reply-author-badge {
        background: #e0e7ff;
        color: #4338ca;
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-left: 8px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Buttons */
    .btn-delete {
        color: #dc2626;
        opacity: 0.6;
        transition: 0.2s;
    }
    .btn-delete:hover {
        opacity: 1;
        background-color: #fee2e2;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5 mt-3">
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            
            {{-- BREADCRUMB / BACK LINK --}}
            <div class="mb-4">
                @if($discussion->event_id)
                    <a href="{{ route('forum.event', $discussion->event->slug) }}" class="btn btn-link text-decoration-none text-secondary p-0 fw-medium">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Forum Event
                    </a>
                @else
                    <a href="{{ route('forum.index') }}" class="btn btn-link text-decoration-none text-secondary p-0 fw-medium">
                        <i class="bi bi-arrow-left me-2"></i> Kembali ke Forum Umum
                    </a>
                @endif
            </div>

            {{-- ALERT SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4 rounded-4 d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill fs-4 me-3 text-success"></i>
                    <div>
                        <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 1. MAIN DISCUSSION CARD --}}
            <article class="card discussion-main-card mb-5">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Header: Badges & Title --}}
                    <header class="mb-4 border-bottom pb-4">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($discussion->is_pinned)
                                <span class="badge badge-pinned px-3 py-2 rounded-pill d-flex align-items-center gap-1 shadow-sm">
                                    <i class="bi bi-pin-angle-fill"></i> Pinned
                                </span>
                            @endif
                            @if($discussion->event_id)
                                <a href="{{ route('event.detail', $discussion->event->slug) }}" class="text-decoration-none">
                                    <span class="badge badge-event px-3 py-2 rounded-pill d-flex align-items-center gap-1">
                                        <i class="bi bi-calendar-event"></i> {{ $discussion->event->title }}
                                    </span>
                                </a>
                            @endif
                        </div>
                        <h1 class="fw-bold mb-3 text-dark lh-sm">{{ $discussion->title }}</h1>
                        
                        {{-- Author Info --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name) }}&background=0D6EFD&color=fff&bold=true" 
                                     alt="User Avatar" class="rounded-circle user-avatar-main">
                                <div>
                                    <div class="fw-bold text-dark fs-5">{{ $discussion->user->name }}</div>
                                    <div class="text-muted small">
                                        <i class="bi bi-clock me-1"></i> Diposting {{ $discussion->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Button (Author Only) --}}
                            @if(Auth::id() === $discussion->user_id)
                                <form action="{{ route('forum.destroy', $discussion->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus diskusi ini? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-delete rounded-pill px-3 py-2 fw-semibold border-0" title="Hapus Diskusi">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </header>

                    {{-- Body Content --}}
                    <div class="content-area text-break">
                        <p style="white-space: pre-wrap;">{{ $discussion->content }}</p>
                    </div>
                </div>
            </article>

            {{-- 2. REPLY SECTION --}}
            <section>
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-chat-dots-fill text-primary me-2"></i> 
                        Balasan <span class="text-muted fs-5 fw-normal">({{ $discussion->replies_count }})</span>
                    </h4>
                </div>

                {{-- Reply Form --}}
                <div class="card reply-form-card shadow-sm mb-5">
                    <div class="card-body p-4">
                        <form action="{{ route('forum.reply.store', $discussion->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold text-muted small text-uppercase">Tuliskan Tanggapanmu</label>
                                <textarea class="form-control form-control-reply" id="content" name="content" rows="4" 
                                          placeholder="Bagikan pemikiran, jawaban, atau saranmu di sini..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <small class="text-muted"><i class="bi bi-info-circle me-1"></i> Gunakan bahasa yang sopan.</small>
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                    <i class="bi bi-send-fill me-2"></i> Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Replies List --}}
                <div class="replies-list">
                    @if($discussion->replies->count() > 0)
                        @foreach($discussion->replies as $reply)
                            <div class="reply-item d-flex gap-3">
                                {{-- Avatar Reply --}}
                                <div class="flex-shrink-0 d-none d-sm-block">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random&bold=true" 
                                         alt="Avatar" class="rounded-circle user-avatar-reply">
                                </div>
                                
                                {{-- Bubble Content --}}
                                <div class="flex-grow-1">
                                    <div class="reply-bubble shadow-sm">
                                        
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="d-flex align-items-center">
                                                {{-- Mobile Avatar --}}
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random&bold=true" 
                                                     alt="Avatar" class="rounded-circle user-avatar-reply d-sm-none me-2" style="width: 32px; height: 32px;">
                                                
                                                <span class="fw-bold text-dark">{{ $reply->user->name }}</span>
                                                @if($reply->user_id === $discussion->user_id)
                                                    <span class="reply-author-badge">Author</span>
                                                @endif
                                                <span class="mx-2 text-muted small">•</span>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>

                                            {{-- Delete Reply --}}
                                            @if(Auth::id() === $reply->user_id)
                                                <form action="{{ route('forum.reply.destroy', $reply->id) }}" method="POST" 
                                                      onsubmit="return confirm('Hapus balasan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link btn-sm btn-delete p-0 text-decoration-none" title="Hapus Balasan">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div class="text-dark opacity-75 text-break" style="white-space: pre-wrap;">{{ $reply->content }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Empty State Replies --}}
                        <div class="text-center py-5 bg-white rounded-4 border border-dashed mb-5">
                            <div class="mb-3">
                                <i class="bi bi-chat-square-text text-muted opacity-25" style="font-size: 3rem;"></i>
                            </div>
                            <p class="text-muted mb-0">Belum ada balasan. Jadilah yang pertama menanggapi!</p>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection