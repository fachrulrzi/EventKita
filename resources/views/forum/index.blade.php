@extends('layouts.app')

@section('title', 'Forum Diskusi Umum - EventKita')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. HERO BANNER */
    .forum-banner {
        background: linear-gradient(135deg, #0d6efd 0%, #3b82f6 100%);
        color: white;
        border-radius: var(--border-radius-lg);
        padding: 40px 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(13, 110, 253, 0.25);
        position: relative;
        overflow: hidden;
    }
    /* Dekorasi Background Abstrak */
    .forum-banner::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        pointer-events: none;
    }

    /* 2. DISCUSSION ITEM */
    .discussion-item {
        background: white;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        transition: all 0.25s cubic-bezier(0.075, 0.82, 0.165, 1);
        margin-bottom: 16px;
        padding: 24px;
        display: block;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .discussion-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: rgba(13, 110, 253, 0.3);
        color: inherit;
    }
    
    /* Pinned Style */
    .discussion-item.pinned {
        background-color: #fffbf0; /* Soft Yellow */
        border-color: #ffeeba;
    }
    .discussion-item.pinned::before {
        content: '';
        position: absolute;
        left: 0; top: 20px; bottom: 20px;
        width: 4px;
        background-color: #ffc107;
        border-radius: 0 4px 4px 0;
    }

    /* Avatar Style */
    .user-avatar-circle {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #4b5563;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 1.2rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .pinned .user-avatar-circle {
        background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
        color: white;
    }

    /* Reply Box */
    .reply-count-box {
        background: #f8fafc;
        padding: 10px 18px;
        border-radius: 12px;
        text-align: center;
        min-width: 80px;
        border: 1px solid #e2e8f0;
    }

    /* Buttons */
    .btn-add-topic {
        background: white;
        color: var(--primary-color);
        font-weight: 700;
        border: none;
        padding: 12px 28px;
        border-radius: 50px;
        transition: 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-add-topic:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        color: var(--primary-color);
    }

    /* Modal Styling */
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
</style>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-10 col-xl-9 mx-auto">
            
            {{-- HEADER BANNER --}}
            <div class="forum-banner d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-2 text-white">
                        <i class="bi bi-chat-quote-fill me-2 opacity-75"></i> Forum Umum
                    </h2>
                    <p class="mb-0 text-white-50 fs-5">Ruang diskusi santai seputar event, komunitas, dan hobi.</p>
                </div>
                <div class="position-relative z-1">
                    <button type="button" class="btn btn-add-topic" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                        <i class="bi bi-plus-lg me-1"></i> Mulai Diskusi
                    </button>
                </div>
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

            {{-- DISCUSSION LIST --}}
            @if($discussions->count() > 0)
                <div class="discussion-list d-flex flex-column gap-3">
                    @foreach($discussions as $discussion)
                        <a href="{{ route('forum.show', $discussion->id) }}" class="discussion-item {{ $discussion->is_pinned ? 'pinned shadow-sm' : 'shadow-sm' }}">
                            <div class="d-flex align-items-start align-items-md-center justify-content-between flex-wrap gap-3">
                                
                                <div class="d-flex align-items-start gap-3 flex-grow-1">
                                    {{-- Avatar --}}
                                    <div class="user-avatar-circle flex-shrink-0">
                                        {{ strtoupper(substr($discussion->user->name, 0, 1)) }}
                                    </div>
                                    
                                    {{-- Content --}}
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                            @if($discussion->is_pinned)
                                                <span class="badge bg-warning text-dark border border-warning rounded-pill px-2">
                                                    <i class="bi bi-pin-fill me-1"></i> Pinned
                                                </span>
                                            @endif
                                            <h5 class="fw-bold mb-0 text-dark text-break">{{ $discussion->title }}</h5>
                                        </div>
                                        
                                        <p class="text-secondary mb-2 d-none d-md-block text-break" style="line-height: 1.5;">
                                            {{ Str::limit($discussion->content, 120) }}
                                        </p>
                                        
                                        <div class="small text-muted d-flex align-items-center flex-wrap gap-3">
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-person-circle me-1 text-primary opacity-50"></i> 
                                                <span class="fw-medium">{{ $discussion->user->name }}</span>
                                            </span>
                                            <span class="d-none d-sm-inline opacity-25">|</span>
                                            <span class="d-flex align-items-center">
                                                <i class="bi bi-clock me-1"></i> 
                                                {{ $discussion->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Reply Stats --}}
                                <div class="reply-count-box">
                                    <div class="h5 fw-bold mb-0 text-primary">{{ $discussion->replies_count }}</div>
                                    <div class="small text-muted fw-bold" style="font-size: 0.65rem; text-uppercase; letter-spacing: 0.5px;">Balasan</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- PAGINATION --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $discussions->links() }}
                </div>
            @else
                {{-- EMPTY STATE --}}
                <div class="text-center py-5 bg-white rounded-4 border shadow-sm">
                    <div class="mb-4">
                        <i class="bi bi-chat-square-quote text-secondary opacity-10" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Belum ada obrolan nih...</h4>
                    <p class="text-muted mb-4">Ayo mulai diskusi pertama dan ajak teman-temanmu bergabung!</p>
                    <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createDiscussionModal">
                        Buat Diskusi Sekarang
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
                
                <div class="modal-header modal-header-custom">
                    <div>
                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-primary me-2"></i>Buat Topik Baru</h5>
                        <p class="mb-0 small text-muted">Mulai percakapan seru dengan komunitas.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold text-dark">Judul Diskusi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg form-control-custom @error('title') is-invalid @enderror" 
                               id="title" name="title" placeholder="Contoh: Rekomendasi tempat nongkrong asik?" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-2">
                        <label for="content" class="form-label fw-bold text-dark">Isi Diskusi <span class="text-danger">*</span></label>
                        <textarea class="form-control form-control-custom @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="6" 
                                  placeholder="Tuliskan detail atau pertanyaanmu secara jelas di sini..." 
                                  required>{{ old('content') }}</textarea>
                        <div class="form-text text-end small mt-1">Pastikan konten mematuhi pedoman komunitas.</div>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-sm">
                        <i class="bi bi-send-fill me-2"></i> Posting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection