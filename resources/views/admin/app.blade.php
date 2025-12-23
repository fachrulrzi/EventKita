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
            --primary-violet: #6366f1; /* Indigo modern */
            --primary-violet-dark: #4f46e5;
            --sidebar-bg: #1e1b4b; /* Deep Navy Violet */
            --bg-light: #f8fafc;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: #1e293b;
        }

        /* Sidebar Styling */
        .sidebar { 
            height: 100vh; 
            background: var(--sidebar-bg); 
            padding-top: 1.5rem; 
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 260px; 
            z-index: 1001;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 0 1.5rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1.5rem;
        }

        .sidebar .nav-link { 
            color: #94a3b8; 
            font-weight: 500; 
            margin: 0.4rem 1rem; 
            padding: 0.8rem 1rem;
            border-radius: 0.75rem; 
            display: flex;
            align-items: center;
            transition: all 0.2s;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            margin-right: 0.75rem;
        }

        .sidebar .nav-link:hover { 
            background-color: rgba(255, 255, 255, 0.05); 
            color: #fff; 
        }

        .sidebar .nav-link.active { 
            background: linear-gradient(135deg, var(--primary-violet) 0%, #8b5cf6 100%);
            color: #fff; 
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        /* Main Content */
        .main-content { 
            margin-left: 260px; 
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Topbar */
        .topbar { 
            background-color: rgba(255, 255, 255, 0.8); 
            backdrop-filter: blur(10px);
            padding: 1rem 2rem; 
            border-bottom: 1px solid #e2e8f0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            position: sticky; 
            top: 0; 
            z-index: 1000;
        }

        /* Profile Dropdown Pill */
        .profile-pill {
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 5px 15px 5px 5px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            text-decoration: none;
            color: #1e293b;
        }

        .profile-pill:hover {
            background: #f1f5f9;
            border-color: var(--primary-violet);
        }

        .admin-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-violet);
        }

        /* Global Admin Card */
        .admin-card {
            background: #fff;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .btn-primary-custom { 
            background: var(--primary-violet); 
            color: #fff; 
            border: none; 
            padding: 0.6rem 1.5rem;
            border-radius: 0.75rem; 
            font-weight: 600; 
            transition: all 0.3s;
        }

        .btn-primary-custom:hover { 
            background: var(--primary-violet-dark); 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            color: #fff;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar { margin-left: -260px; }
            .main-content { margin-left: 0; }
            .sidebar.show { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h4 class="fw-bold mb-0 text-white"><span class="text-primary">🎉</span> EventKita</h4>
            <small class="text-white-50">Administrator Panel</small>
        </div>
        
        <ul class="nav flex-column px-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>
        <li class="nav-item">
            {{-- route('admin.events') memanggil fungsi index di EventController --}}
            {{-- request()->routeIs('admin.event*') membuat menu tetap menyala (active) saat halaman edit/create dibuka --}}
                <a class="nav-link {{ request()->routeIs('admin.event*') ? 'active' : '' }}" href="{{ route('admin.events') }}">
                    <i class="bi bi-calendar-event"></i> Kelola Event
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-collection"></i> Kelola Kategori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.forum.*') ? 'active' : '' }}" href="{{ route('admin.forum.index') }}">
                    <i class="bi bi-chat-left-dots"></i> Moderasi Forum
                </a>
            </li>
            
            <li class="mt-5 px-4">
                <hr class="text-white-50 opacity-25">
                <a href="{{ route('logout') }}" class="text-white-50 text-decoration-none small d-flex align-items-center"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout System
                </a>
            </li>
        </ul>
    </aside>

    <div class="main-content">
        <header class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Halaman Admin')</h5>
            </div>
            
            <div class="dropdown">
                <a id="navbarDropdown" class="profile-pill dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6366f1&color=fff" 
                         alt="Avatar" class="admin-avatar">
                    <span class="fw-bold small d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3" style="border-radius: 12px; min-width: 200px;">
                    <li><h6 class="dropdown-header">Akses Cepat</h6></li>
                    <li><a class="dropdown-item py-2" href="{{ url('/') }}"><i class="bi bi-globe me-2"></i> Lihat Website</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-power me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <main class="p-4 mt-2">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>