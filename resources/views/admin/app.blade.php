<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Panel') | EventKita</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* --- VARIABLES --- */
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --sidebar-active: linear-gradient(90deg, #6366f1 0%, #818cf8 100%);
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --bg-light: #f8fafc; /* Lebih soft dari putih biasa */
            --text-dark: #334155;
            --primary-violet: #6366f1;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* --- SIDEBAR STYLING --- */
        .sidebar {
            height: 100vh;
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            background-image: linear-gradient(to bottom, #0f172a, #1e1b4b); 
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.15);
        }

        /* Header Sidebar */
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .brand-logo {
            background: rgba(99, 102, 241, 0.2);
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            color: #818cf8;
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
        }

        /* Navigation Area */
        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .menu-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 700;
            margin: 1.5rem 0 0.5rem 0.8rem;
        }

        .sidebar .nav-link {
            color: var(--sidebar-text);
            font-weight: 500;
            padding: 0.85rem 1rem;
            margin-bottom: 0.4rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
            color: rgba(255,255,255,0.6);
            transition: 0.2s;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar .nav-link:hover i { color: #fff; }

        /* Active State */
        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }
        .sidebar .nav-link.active i { color: #fff; }

        /* Sidebar Footer (User Profile) */
        .sidebar-footer {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            margin-top: auto;
        }

        .user-card {
            display: flex;
            align-items: center;
            padding: 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.2s;
            cursor: pointer;
            border: 1px solid transparent;
        }
        .user-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* --- MAIN CONTENT & TOPBAR --- */
        .main-content { 
            margin-left: var(--sidebar-width); 
            min-height: 100vh;
            transition: margin-left 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .topbar { 
            background-color: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 1rem 2rem; 
            border-bottom: 1px solid rgba(0,0,0,0.05); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            position: sticky; 
            top: 0; 
            z-index: 1040;
        }

        /* --- MOBILE RESPONSIVE --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(3px);
            z-index: 1045;
            opacity: 0;
            transition: opacity 0.3s;
        }

        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-overlay.show { display: block; opacity: 1; }
            .topbar { padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- Overlay Mobile --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">
        {{-- Header --}}
        <div class="sidebar-header d-flex align-items-center gap-3">
            <div class="brand-logo">
                <i class="bi bi-rocket-takeoff-fill fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-white mb-0" style="letter-spacing: -0.5px;">EventKita</h5>
                <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-10 rounded-pill" style="font-size: 0.65rem;">ADMIN v2.0</span>
            </div>
        </div>
        
        {{-- Navigation --}}
        <div class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="menu-label">Overview</li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-label">Management</li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.events*') ? 'active' : '' }}" href="{{ route('admin.events') }}">
                        <i class="bi bi-calendar2-heart-fill"></i> <span>Kelola Event</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.kategori*') ? 'active' : '' }}" href="{{ route('admin.kategori.index') }}">
                        <i class="bi bi-bookmarks-fill"></i> <span>Kategori</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.forum*') ? 'active' : '' }}" href="{{ route('admin.forum.index') }}">
                        <i class="bi bi-chat-left-quote-fill"></i> <span>Moderasi Forum</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.kota*') ? 'active' : '' }}" href="{{ route('admin.kota.index') }}">
                        <i class="bi bi-geo-alt-fill"></i> <span>Kelola Kota</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Footer Profile --}}
        <div class="sidebar-footer">
            <div class="dropdown dropup">
                <div class="user-card d-flex align-items-center gap-3" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="position-relative">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true" 
                             alt="User" class="rounded-circle border border-2 border-white border-opacity-25" width="38" height="38">
                        <span class="position-absolute bottom-0 end-0 bg-success border border-dark rounded-circle p-1"></span>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="text-white mb-0 text-truncate" style="font-size: 0.85rem;">{{ Auth::user()->name }}</h6>
                        <small class="text-white-50 d-block" style="font-size: 0.7rem;">Administrator</small>
                    </div>
                    <i class="bi bi-chevron-up text-white-50" style="font-size: 0.8rem;"></i>
                </div>

                <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0 mb-2 p-2 rounded-4 w-100">
                    <li><a class="dropdown-item rounded-2 small py-2" href="{{ url('/') }}"><i class="bi bi-globe me-2"></i> Lihat Website</a></li>
                    <li><hr class="dropdown-divider border-secondary opacity-25 my-1"></li>
                    <li>
                        <a class="dropdown-item rounded-2 text-danger small py-2" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-white border d-lg-none me-3 shadow-sm rounded-circle" id="sidebarToggle" style="width: 40px; height: 40px;">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark">@yield('page-title', 'Halaman Admin')</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="#" class="text-secondary position-relative p-2">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </a>
            </div>
        </header>

        <main class="p-4">
            @yield('content')
        </main>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>