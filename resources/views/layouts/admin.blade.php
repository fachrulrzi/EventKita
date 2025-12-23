<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') | EventKita</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --bg-body: #f1f5f9;
            --text-muted: #a5b4fc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* ============ SIDEBAR ============ */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
        }

        /* Brand Header */
        .sidebar-brand {
            padding: 24px 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-brand a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #fff;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 12px;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .brand-text {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 20px 16px;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 12px;
            margin-bottom: 12px;
            margin-top: 8px;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            margin-bottom: 6px;
            border-radius: 12px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: transparent;
            border-radius: 0 4px 4px 0;
            transition: all 0.25s ease;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.25rem;
            margin-right: 14px;
            transition: all 0.25s ease;
        }

        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9) 0%, rgba(168, 85, 247, 0.9) 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }

        .sidebar-nav .nav-link.active::before {
            background: #fff;
        }

        .sidebar-nav .nav-link.active i {
            color: #fff;
        }

        /* Nav Badge */
        .nav-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        .nav-link.active .nav-badge {
            background: rgba(255, 255, 255, 0.25);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .sidebar-footer .logout-btn {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            color: #fca5a5;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            transition: all 0.25s ease;
        }

        .sidebar-footer .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #fecaca;
            transform: translateY(-2px);
        }

        .sidebar-footer .logout-btn i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        /* ============ MAIN CONTENT ============ */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
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
            padding: 6px 16px 6px 6px;
            border-radius: 50px;
            transition: 0.2s;
            border: 1px solid #e2e8f0;
        }

        .user-pill:hover {
            background: #e2e8f0;
            border-color: var(--primary-color);
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

        /* Scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>
<body class="admin-layout">

    <aside class="admin-sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">
                <div class="brand-icon">🎉</div>
                <div>
                    <div class="brand-text">EventKita</div>
                    <div class="brand-subtitle">Admin Panel</div>
                </div>
            </a>
        </div>
        
        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>
            
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            
            <a class="nav-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}"
               href="{{ Route::has('admin.events') ? route('admin.events') : '#' }}">
                <i class="bi bi-calendar-event-fill"></i>
                <span>Kelola Event</span>
            </a>

            <a class="nav-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                <i class="bi bi-tags-fill"></i>
                <span>Kelola Kategori</span>
            </a>

            <a class="nav-link {{ request()->routeIs('admin.kota.*') ? 'active' : '' }}" href="{{ route('admin.kota.index') }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Kelola Kota</span>
            </a>

            <div class="nav-section-title mt-4">Lainnya</div>
            
            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                <i class="bi bi-globe"></i>
                <span>Lihat Website</span>
                <i class="bi bi-box-arrow-up-right ms-auto" style="font-size: 0.75rem;"></i>
            </a>
        </nav>

        <!-- Footer / Logout -->
        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="logout-btn" 
               onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                <i class="bi bi-box-arrow-left"></i>
                <span>Keluar dari Admin</span>
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
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" 
                             alt="Avatar" class="rounded-circle me-2" width="32" height="32">
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