@extends('layouts.app')

@section('title', 'Forum Diskusi Umum - EventKita')

@section('content')
<style>
    /* Custom Styling for General Forum */
    .forum-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #00d2ff 100%);
        color: white;
        border-radius: 20px;
        padding: 50px 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
    }
    .discussion-item {
        background: white;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        padding: 20px;
        display: block;
        text-decoration: none;
        color: inherit;
    }
    .discussion-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        border-left: 5px solid #0d6efd;
        color: inherit;
    }
    .discussion-item.pinned {
        background-color: #fff9e6;
        border-left: 5px solid #ffc107;
    }
    .user-avatar-circle {
        width: 45px;
        height: 45px;
        background: #eef2f7;
        color: #0d6efd;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.1rem;
    }
    .reply-count-box {
        background: #f8f9fa;
        padding: 8px 15px;
        border-radius: 10px;
        text-align: center;
        min-width: 80px;
    }
    .btn-add-topic {
        background: white;
        color: #0d6efd;
        font-weight: 700;
        border: none;
        padding: 12px 25px;
        border-radius: 12px;
        transition: 0.3s;
    }
    .btn-add-topic:hover {
        background: #f8f9fa;
        transform: scale(1.05);
    }
    .modal-content {
        border-radius: 20px;
        border: none;
    }
</style>

<div class="container py-5 mt-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            
            <div class="forum-banner d-flex flex-wrap justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="fw-bold mb-2"><i class="bi bi-chat-quote-fill me-2"></i> Forum Umum</h1>
                    <p class="mb-0 opacity-75 fs-5">Ruang diskusi santai seputar event, komunitas, dan hobi.</p>
                </div>
                <button type="button" class="btn btn-add-topic shadow-sm" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                    <i class="bi bi-plus-lg me-1"></i> Mulai Diskusi Baru
                </button>
            </div>

            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert" style="border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($discussions->count() > 0)
                <div class="discussion-list">
                    @foreach($discussions as $discussion)
                        <a href="{{ route('forum.show', $discussion->id) }}" class="discussion-item shadow-sm {{ $discussion->is_pinned ? 'pinned' : '' }}">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="user-avatar-circle shadow-sm">
                                        {{ strtoupper(substr($discussion->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            @if($discussion->is_pinned)
                                                <span class="badge bg-warning text-dark small"><i class="bi bi-pin-fill"></i> Pinned</span>
                                            @endif
                                            <h5 class="fw-bold mb-0">{{ $discussion->title }}</h5>
                                        </div>
                                        <p class="text-muted mb-2 d-none d-md-block">{{ Str::limit($discussion->content, 120) }}</p>
                                        <div class="small text-muted">
                                            <span class="me-3"><i class="bi bi-person me-1"></i> {{ $discussion->user->name }}</span>
                                            <span><i class="bi bi-clock me-1"></i> {{ $discussion->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="reply-count-box border">
                                    <div class="h5 fw-bold mb-0 text-primary">{{ $discussion->replies_count }}</div>
                                    <div class="small text-muted fw-bold" style="font-size: 0.65rem; text-uppercase;">Balasan</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-5 d-flex justify-content-center">
                    {{ $discussions->links() }}
                </div>
            @else
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <div class="mb-4">
                        <i class="bi bi-chat-left-dots text-muted opacity-25" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Belum ada obrolan nih...</h4>
                    <p class="text-muted">Ayo mulai diskusi pertama dan ajak teman-temanmu bergabung!</p>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                        Buat Diskusi Sekarang
                    </button>
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
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold fs-4"><i class="bi bi-pencil-square text-primary me-2"></i>Topik Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold text-muted small text-uppercase">Judul Diskusi</label>
                        <input type="text" class="form-control form-control-lg bg-light border-0 @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="Apa yang ingin kamu bahas?" 
                               value="{{ old('title') }}" required style="border-radius: 12px;">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold text-muted small text-uppercase">Isi Diskusi</label>
                        <textarea class="form-control bg-light border-0 @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="6" 
                                  placeholder="Tuliskan detail atau pertanyaanmu secara jelas di sini..." 
                                  required style="border-radius: 12px;">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold shadow">
                        <i class="bi bi-send-fill me-2"></i> Posting Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection