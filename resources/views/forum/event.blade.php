@extends('layouts.app')

@section('title', 'Forum Diskusi - ' . $event->title)

@section('content')
<style>
    .forum-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .discussion-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: white;
        margin-bottom: 15px;
        border-left: 5px solid transparent;
    }
    .discussion-card:hover {
        transform: translateX(10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-left-color: #0d6efd;
    }
    .discussion-card.pinned {
        border-left-color: #ffc107;
        background-color: #fffdf5;
    }
    .reply-badge {
        background: #f0f4f8;
        color: #0d6efd;
        padding: 10px 15px;
        border-radius: 12px;
        min-width: 70px;
        text-align: center;
    }
    .user-avatar {
        width: 40px;
        height: 40px;
        background: #e9ecef;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: bold;
    }
    .btn-create-discussion {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }
    .modal-content {
        border-radius: 20px;
        border: none;
    }
    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <nav aria-label="breadcrumb" class="mb-3">
                <a href="{{ route('event.detail', $event->slug) }}" class="btn btn-link text-decoration-none text-muted p-0">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Detail Event
                </a>
            </nav>

            <div class="forum-header d-flex flex-wrap justify-content-between align-items-center shadow-sm">
                <div class="mb-3 mb-md-0">
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-2">Community Space</span>
                    <h2 class="fw-bold mb-1">Forum Diskusi</h2>
                    <p class="text-muted mb-0">Event: <span class="fw-semibold text-dark">{{ $event->title }}</span></p>
                </div>
                <button type="button" class="btn btn-primary btn-create-discussion" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                    <i class="bi bi-plus-lg me-2"></i> Mulai Diskusi
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($discussions->count() > 0)
                <div class="discussion-container">
                    @foreach($discussions as $discussion)
                        <div class="card discussion-card shadow-sm {{ $discussion->is_pinned ? 'pinned' : '' }}">
                            <a href="{{ route('forum.show', $discussion->id) }}" class="text-decoration-none text-dark">
                                <div class="card-body p-4">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex gap-3">
                                            <div class="user-avatar shadow-sm">
                                                {{ strtoupper(substr($discussion->user->name, 0, 1)) }}
                                            </div>
                                            
                                            <div>
                                                <div class="d-flex align-items-center mb-1">
                                                    @if($discussion->is_pinned)
                                                        <span class="badge bg-warning text-dark me-2 small">
                                                            <i class="bi bi-pin-angle-fill"></i> Pinned
                                                        </span>
                                                    @endif
                                                    <h5 class="mb-0 fw-bold discussion-title">{{ $discussion->title }}</h5>
                                                </div>
                                                <p class="text-muted mb-3" style="font-size: 0.95rem;">
                                                    {{ Str::limit($discussion->content, 140) }}
                                                </p>
                                                <div class="d-flex align-items-center gap-3 small text-muted">
                                                    <span><i class="bi bi-person me-1"></i> {{ $discussion->user->name }}</span>
                                                    <span><i class="bi bi-clock me-1"></i> {{ $discussion->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="reply-badge shadow-sm d-none d-sm-block">
                                            <div class="h5 fw-bold mb-0 text-primary">{{ $discussion->replies_count }}</div>
                                            <div class="small text-muted" style="font-size: 0.7rem; text-uppercase; font-weight: 700;">Balasan</div>
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
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/amber/chatting.svg" alt="empty forum" style="width: 250px;" class="mb-4">
                    <h4 class="fw-bold">Belum Ada Diskusi</h4>
                    <p class="text-muted">Jadilah yang pertama untuk bertanya atau berbagi info tentang event ini!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="createDiscussionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <form action="{{ route('forum.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold fs-4">Buat Diskusi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold small text-muted text-uppercase">Subjek Diskusi</label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="Apa yang ingin kamu bahas?" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label for="content" class="form-label fw-bold small text-muted text-uppercase">Detail Pertanyaan/Info</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="6" 
                                  placeholder="Tuliskan detail diskusi di sini..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">
                        <i class="bi bi-send me-2"></i> Posting Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection