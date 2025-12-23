<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') | EventKita</title>

    {{-- FONTS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS LIBRARIES --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --primary-color: #4f46e5;
            --primary-light: #e0e7ff;
            --bg-body: #f8fafc;
            --sidebar-bg: #1e1b4b; /* Dark Indigo */
            --sidebar-active: #4338ca;
            --text-color: #334155;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-color);
            overflow-x: hidden;
        }

        /* 1. SIDEBAR STYLING */
        .admin-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: linear-gradient(180deg, #1e1b4b 0%, #312e81 100%);
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.1);
        }

        /* Brand */
        .sidebar-brand {
            padding: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .brand-wrapper {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            gap: 12px;
        }
        .brand-icon {
            width: 42px;
            height: 42px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            backdrop-filter: blur(5px);
        }
        .brand-text h4 { font-size: 1.1rem; font-weight: 700; margin: 0; letter-spacing: 0.5px; }
        .brand-text span { font-size: 0.7rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; }

        /* Navigation */
        .sidebar-nav {
            flex: 1;
            padding: 20px 15px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        .nav-section {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            font-weight: 700;
            letter-spacing: 1px;
            margin: 15px 0 8px 12px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
        }
        .nav-link i { font-size: 1.2rem; margin-right: 12px; transition: 0.2s; }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.08);
            color: white;
            transform: translateX(3px);
        }
        
        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color) 0%, #6366f1 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0,0,0,0.1);
        }
        .logout-btn-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: 0.2s;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .logout-btn-sidebar:hover {
            background: #ef4444;
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
        }

        /* 2. MAIN CONTENT AREA */
        .admin-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Topbar with Glassmorphism */
        .admin-topbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .user-dropdown-btn {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            transition: 0.2s;
            color: #334155;
        }
        .user-dropdown-btn:hover, .user-dropdown-btn[aria-expanded="true"] {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        /* Content Padding */
        .admin-content {
            padding: 30px;
            flex: 1;
        }

        /* Responsive Logic */
        @media (max-width: 991.98px) {
            .admin-sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .admin-sidebar.show { margin-left: 0; box-shadow: 10px 0 50px rgba(0,0,0,0.5); }
            .admin-main { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

    {{-- SIDEBAR --}}
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}" class="brand-wrapper">
                <div class="brand-icon">🎉</div>
                <div class="brand-text">
                    <h4>EventKita</h4>
                    <span>Admin Dashboard</span>
                </div>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            
            {{-- Logic Active State Dipertahankan --}}
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
            
            {{-- Logic Route Check Dipertahankan --}}
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

            <div class="nav-section mt-3">Akses Cepat</div>
            
            <a class="nav-link" href="{{ url('/') }}" target="_blank">
                <i class="bi bi-globe"></i>
                <span>Lihat Website</span>
                <i class="bi bi-box-arrow-up-right ms-auto small opacity-50"></i>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('logout') }}" class="logout-btn-sidebar" 
               onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                <i class="bi bi-box-arrow-left me-2"></i> Keluar
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT WRAPPER --}}
    <div class="admin-main">
        
        {{-- TOPBAR --}}
        <header class="admin-topbar">
            <div class="d-flex align-items-center">
                {{-- Mobile Toggle --}}
                <button class="btn btn-light border d-lg-none me-3 shadow-sm" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold text-dark d-none d-sm-block">@yield('page-title', 'Halaman Admin')</h5>
            </div>

            {{-- User Profile Dropdown --}}
            <div class="dropdown">
                <a class="user-dropdown-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff&bold=true" 
                         alt="Avatar" class="rounded-circle" width="32" height="32">
                    <span class="fw-semibold small d-none d-md-block">{{ Auth::user()->name }}</span>
                    <i class="bi bi-chevron-down small opacity-50"></i>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 12px; min-width: 200px;">
                    <li><h6 class="dropdown-header small text-uppercase">Akun Administrator</h6></li>
                    <li>
                        <a class="dropdown-item rounded-2 py-2" href="{{ url('/') }}">
                            <i class="bi bi-house-door me-2 text-primary"></i> Ke Beranda Utama
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        {{-- Logout Logic Dipertahankan --}}
                        <a class="dropdown-item rounded-2 py-2 text-danger fw-medium" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                           <i class="bi bi-power me-2"></i> Log Out
                        </a>
                    </li>
                </ul>
                {{-- Form Logout Hidden --}}
                <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </header>

        {{-- DYNAMIC CONTENT --}}
        <main class="admin-content">
            @yield('content')
        </main>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple script untuk menutup sidebar jika klik di luar (Mobile)
        document.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.admin-sidebar');
            const toggleBtn = document.querySelector('.btn-light'); // Target tombol toggle mobile
            
            if (window.innerWidth < 992 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>