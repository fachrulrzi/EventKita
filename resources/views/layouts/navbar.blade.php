<style>
    /* Custom Styling khusus Navbar */
    .navbar-main {
        backdrop-filter: blur(15px);
        background-color: rgba(255, 255, 255, 0.9);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 12px 0;
    }
    
    .navbar-brand {
        font-size: 1.5rem;
        letter-spacing: -0.5px;
    }

    .search-container {
        position: relative;
        max-width: 500px;
        width: 100%;
    }

    .search-input {
        background-color: #f1f3f5;
        border: none;
        border-radius: 50px;
        padding: 10px 20px 10px 45px;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .search-input:focus {
        background-color: #fff;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        width: 105%;
    }

    .search-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
    }

    .nav-link-custom {
        font-weight: 600;
        color: #495057;
        padding: 8px 16px !important;
        border-radius: 8px;
        transition: 0.2s;
    }

    .nav-link-custom:hover {
        background-color: #f8f9fa;
        color: #0d6efd;
    }

    .user-pill-nav {
        background-color: #f8f9fa;
        padding: 4px 12px 4px 6px;
        border-radius: 50px;
        border: 1px solid #e9ecef;
        transition: 0.2s;
    }

    .user-pill-nav:hover {
        background-color: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .avatar-nav {
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-radius: 15px;
        margin-top: 15px;
        padding: 10px;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 10px 15px;
        font-weight: 500;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-main">
    <div class="container">
        <a class="navbar-brand fw-bold text-dark" href="{{ url('/') }}">
            <span class="text-primary">🎉</span> EventKita
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            
            <div class="mx-auto search-container d-none d-lg-block">
                <form action="{{ route('search') }}" method="GET">
                    <div class="position-relative">
                        <i class="bi bi-search search-icon"></i>
                        <input type="search" class="form-control search-input" 
                               name="q" placeholder="Mau nonton konser apa hari ini?" 
                               value="{{ request('q') }}">
                    </div>
                </form>
            </div>

            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link nav-link-custom" href="{{ route('forum.index') }}">
                        <i class="bi bi-chat-dots me-1"></i> Forum
                    </a>
                </li>

                @guest
                    @if (Route::has('login'))
                        <li class="nav-item ms-lg-3">
                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark fw-bold">Login</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary px-4 rounded-pill shadow-sm fw-bold">Register</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown ms-lg-3">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle user-pill-nav d-flex align-items-center" 
                           href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff" 
                                 alt="Avatar" class="rounded-circle avatar-nav me-2" width="30" height="30">
                            
                            <span class="fw-bold text-dark small d-none d-md-inline">{{ Str::words(Auth::user()->name, 1, '') }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end animated fadeIn" aria-labelledby="navbarDropdown">
                            <div class="px-3 py-2 border-bottom mb-2 d-lg-none text-center">
                                <small class="text-muted d-block">Masuk sebagai</small>
                                <span class="fw-bold">{{ Auth::user()->name }}</span>
                            </div>

                            @if(Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard Admin
                                </a>
                            @else
                                <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                    <i class="bi bi-person-circle me-2"></i> Dashboard Saya
                                </a>
                            @endif
                            
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-heart me-2"></i> Event Favorit
                            </a>

                            <hr class="dropdown-divider">
                            
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
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