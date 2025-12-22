@extends('layouts.app')

@section('title', $discussion->title)

@section('content')
<style>
    /* Custom Styling untuk Thread Diskusi */
    .discussion-main-card {
        border: none;
        border-radius: 20px;
        background: white;
    }
    .reply-bubble {
        border-radius: 18px;
        background-color: #f8f9fa;
        transition: all 0.2s ease;
        border: 1px solid rgba(0,0,0,0.03);
    }
    .reply-bubble:hover {
        background-color: #f1f3f5;
    }
    .user-avatar-main {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border: 2px solid #0d6efd;
    }
    .user-avatar-reply {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
    .badge-event {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        font-weight: 600;
        border: 1px solid rgba(13, 110, 253, 0.2);
    }
    .content-area {
        font-size: 1.05rem;
        line-height: 1.6;
        color: #343a40;
    }
    .reply-form-card {
        border-radius: 20px;
        border: 2px solid #eef2f7;
    }
    .btn-delete {
        opacity: 0.5;
        transition: opacity 0.2s;
    }
    .btn-delete:hover {
        opacity: 1;
    }
</style>

<div class="container py-5 mt-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">
            
            <div class="mb-4">
                @if($discussion->event_id)
                    <a href="{{ route('forum.event', $discussion->event->slug) }}" class="btn btn-link text-decoration-none text-muted p-0">
                        <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali ke Forum Event
                    </a>
                @else
                    <a href="{{ route('forum.index') }}" class="btn btn-link text-decoration-none text-muted p-0">
                        <i class="bi bi-arrow-left-circle-fill me-2"></i> Kembali ke Forum Umum
                    </a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card discussion-main-card shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @if($discussion->is_pinned)
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill small">
                                <i class="bi bi-pin-angle-fill me-1"></i> Pinned Post
                            </span>
                        @endif
                        @if($discussion->event_id)
                            <span class="badge badge-event px-3 py-2 rounded-pill small">
                                <i class="bi bi-calendar-event me-1"></i> {{ $discussion->event->title }}
                            </span>
                        @endif
                    </div>

                    <h2 class="fw-bold mb-4">{{ $discussion->title }}</h2>
                    
                    <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name) }}&background=0D6EFD&color=fff" 
                             alt="User Avatar" class="rounded-circle user-avatar-main me-3 shadow-sm">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-dark fs-5">{{ $discussion->user->name }}</div>
                            <div class="text-muted small">
                                <i class="bi bi-clock me-1"></i> Diulas {{ $discussion->created_at->diffForHumans() }}
                            </div>
                        </div>
                        @if(Auth::id() === $discussion->user_id)
                            <div class="ms-auto">
                                <form action="{{ route('forum.destroy', $discussion->id) }}" method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus diskusi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 btn-delete">
                                        <i class="bi bi-trash3 me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                    
                    <div class="content-area">
                        <p style="white-space: pre-wrap;">{{ $discussion->content }}</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 bg-transparent">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-chat-left-text-fill text-primary me-2"></i> 
                        Balasan <span class="text-muted">({{ $discussion->replies_count }})</span>
                    </h4>
                </div>

                <div class="card reply-form-card shadow-sm mb-5">
                    <div class="card-body p-4">
                        <form action="{{ route('forum.reply.store', $discussion->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold text-muted small text-uppercase">Tuliskan Tanggapanmu</label>
                                <textarea class="form-control border-0 bg-light @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="4" 
                                          placeholder="Bagikan pemikiran atau jawabanmu di sini..." 
                                          style="border-radius: 12px;" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="replies-list">
                    @if($discussion->replies->count() > 0)
                        @foreach($discussion->replies as $reply)
                            <div class="d-flex mb-4 gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=random" 
                                     alt="Avatar" class="rounded-circle user-avatar-reply shadow-sm mt-1">
                                <div class="flex-grow-1">
                                    <div class="reply-bubble p-3 p-md-4 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="fw-bold text-dark">{{ $reply->user->name }}</span>
                                                <span class="mx-2 text-muted small">•</span>
                                                <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if(Auth::id() === $reply->user_id)
                                                <form action="{{ route('forum.reply.destroy', $reply->id) }}" method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus balasan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 btn-delete" title="Hapus balasan">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <p class="mb-0 text-dark-emphasis" style="white-space: pre-wrap;">{{ $reply->content }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-5 bg-white rounded-4 border shadow-sm mb-4">
                            <i class="bi bi-chat-square-dots text-muted opacity-25" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">Belum ada diskusi di sini. Ayo mulai percakapan!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection