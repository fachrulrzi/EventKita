{{-- INTERNAL STYLES KHUSUS NAVBAR --}}
<style>
    .navbar-main {
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        background-color: rgba(255, 255, 255, 0.85);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 0.8rem 0;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        font-weight: 800;
        font-size: 1.4rem;
        letter-spacing: -0.5px;
        color: #1e1b4b !important;
    }
    
    /* Search Bar Styles */
    .search-wrapper {
        position: relative;
        width: 100%;
        max-width: 480px;
    }
    .search-input {
        background-color: #f1f5f9;
        border: 2px solid transparent;
        border-radius: 50px;
        padding: 10px 20px 10px 48px;
        font-size: 0.95rem;
        transition: all 0.25s ease;
        width: 100%;
    }
    .search-input:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }
    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        pointer-events: none;
    }

    /* Nav Links */
    .nav-link-custom {
        font-weight: 600;
        color: #475569;
        padding: 8px 16px !important;
        border-radius: 50px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .nav-link-custom:hover {
        background-color: #f1f5f9;
        color: #0d6efd;
    }

    /* User Profile Pill */
    .user-pill-btn {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 4px 14px 4px 4px;
        border-radius: 50px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .user-pill-btn:hover, .user-pill-btn[aria-expanded="true"] {
        border-color: #cbd5e1;
        background: #f8fafc;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .user-avatar {
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Dropdown Animation */
    .dropdown-menu-animate {
        border: none;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-radius: 16px;
        margin-top: 10px;
        padding: 8px;
        animation: dropdownFade 0.2s ease;
    }
    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .dropdown-item {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        color: #334155;
    }
    .dropdown-item:hover {
        background-color: #f1f5f9;
        color: #0d6efd;
    }
</style>

<nav class="navbar navbar-expand-lg fixed-top navbar-main">
    <div class="container">
        {{-- BRAND --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <span style="font-size: 1.5rem;">🎉</span> EventKita
        </a>

        {{-- MOBILE TOGGLER --}}
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            
            {{-- SEARCH BAR (Hidden on mobile, block on lg up) --}}
            <div class="mx-lg-auto my-3 my-lg-0 search-wrapper">
                <form action="{{ route('search') }}" method="GET">
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="search" class="form-control search-input" 
                               name="q" placeholder="Cari konser, workshop, atau festival..." 
                               value="{{ request('q') }}">
                    </div>
                </form>
            </div>

            {{-- RIGHT NAV --}}
            <ul class="navbar-nav align-items-center ms-auto">
                <li class="nav-item me-2">
                    <a class="nav-link nav-link-custom" href="{{ route('forum.index') }}">
                        <i class="bi bi-chat-right-dots-fill text-primary"></i> Forum
                    </a>
                </li>

                @guest
                    <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark fw-bold px-3">Login</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Daftar</a>
                            </li>
                        @endif
                    </div>
                @else
                    <li class="nav-item dropdown ms-lg-2 mt-3 mt-lg-0">
                        <a id="navbarDropdown" class="nav-link p-0 user-pill-btn" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d6efd&color=fff&bold=true" 
                                 alt="Avatar" class="rounded-circle user-avatar" width="34" height="34">
                            <span class="fw-bold text-dark small d-none d-md-block pe-2">{{ Str::limit(Auth::user()->name, 10) }}</span>
                            <i class="bi bi-chevron-down small text-muted me-2"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate" aria-labelledby="navbarDropdown">
                            {{-- Mobile User Info --}}
                            <div class="px-3 py-2 d-lg-none border-bottom mb-2">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Login sebagai</small>
                                <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                            </div>

                            @if(Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard Admin
                                </a>
                            @else
                                <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                    <i class="bi bi-grid-fill me-2 text-primary"></i> Dashboard Saya
                                </a>
                            @endif
                            
                            {{-- Logic Route Event Favorit (Jika ada) --}}
                            {{-- <a class="dropdown-item" href="#"> --}}
                                {{-- <i class="bi bi-heart-fill me-2 text-danger"></i> Event Favorit
                            </a> --}}

                            <div class="dropdown-divider my-2"></div>
                            
                            <a class="dropdown-item text-danger fw-semibold" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Keluar
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>