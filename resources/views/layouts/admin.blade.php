<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') | EventKita</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1e293b; /* Slate 800 */
            --sidebar-hover: #334155;
            --primary-color: #0d6efd;
            --bg-body: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--sidebar-bg);
            color: #fff;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-brand {
            padding: 25px;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .nav-custom {
            padding: 20px 15px;
        }

        .nav-custom .nav-link {
            color: #94a3b8; /* Slate 400 */
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 5px;
            font-weight: 500;
            transition: 0.2s;
            display: flex;
            align-items: center;
        }

        .nav-custom .nav-link i {
            font-size: 1.2rem;
            transition: 0.2s;
        }

        .nav-custom .nav-link:hover {
            color: #fff;
            background-color: var(--sidebar-hover);
        }

        .nav-custom .nav-link.active {
            color: #fff;
            background-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .nav-custom .nav-link.active i {
            color: #fff;
        }

        /* Main Content Styling */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .admin-topbar {
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .admin-content {
            padding: 30px;
        }

        /* Profile Dropdown */
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .user-pill {
            background: #f1f5f9;
            padding: 5px 15px;
            border-radius: 50px;
            transition: 0.2s;
        }

        .user-pill:hover {
            background: #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .admin-main {
                margin-left: 0;
            }
            .admin-sidebar.show {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="admin-layout">

    <aside class="admin-sidebar">
        <div class="sidebar-brand text-center">
            <a href="{{ url('/') }}" class="text-white text-decoration-none fw-bold">
                <span class="me-1">🎉</span> EventKita
            </a>
        </div>
        
        <div class="nav-custom">
            <small class="text-uppercase text-muted fw-bold mb-3 d-block px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Menu Utama</small>
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-1x2 me-3"></i> Dashboard
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}"
                       href="{{ Route::has('admin.events') ? route('admin.events') : '#' }}">
                        <i class="bi bi-calendar4-event me-3"></i> Kelola Event
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                        <i class="bi bi-tags me-3"></i> Kelola Kategori
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.kota.*') ? 'active' : '' }}" href="{{ route('admin.kota.index') }}">
                        <i class="bi bi-geo-alt me-3"></i> Kelola Kota
                    </a>
                </li>
            </ul>
            
            <hr class="my-4 opacity-10">
            
            <small class="text-uppercase text-muted fw-bold mb-3 d-block px-3" style="font-size: 0.7rem; letter-spacing: 1px;">Sistem</small>
            <a href="{{ route('logout') }}" class="nav-link text-danger" 
               onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                <i class="bi bi-box-arrow-right me-3"></i> Keluar
            </a>
        </div>
    </aside>

    <div class="admin-main">
        
        <header class="admin-topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Halaman Admin')</h5>
            </div>

            <div class="dropdown user-dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-pill d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D6EFD&color=fff" 
                             alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                        <span class="fw-semibold text-dark small d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <i class="bi bi-chevron-down ms-2 small text-muted"></i>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px;">
                    <li><h6 class="dropdown-header">Administrator</h6></li>
                    <li><a class="dropdown-item py-2" href="{{ url('/') }}"><i class="bi bi-house-door me-2"></i> Lihat Website</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                           <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                    </li>
                </ul>
                <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>