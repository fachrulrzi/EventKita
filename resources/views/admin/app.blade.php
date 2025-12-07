<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Panel') | EventKita</title>

  <!-- CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-violet: #8C00FF;
      --primary-violet-dark: #450693;
      --bg-light: #f8f9fa;
    }
    body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }
    .sidebar { height: 100vh; background-color: #fff; border-right: 1px solid #eee; padding-top: 1rem; position: fixed; top: 0; left: 0; width: 250px; z-index: 1001; }
    .sidebar .nav-link { color: #212529; font-weight: 500; margin: .25rem 0; border-radius: .5rem; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { background-color: var(--primary-violet); color: #fff; }
    .main-content { margin-left: 250px; }
    .topbar { background-color: #fff; padding: .75rem 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000;}
    .status-card { border-radius: 1rem; padding: 1.25rem; background-color: #fff; box-shadow: 0 3px 10px rgba(0,0,0,0.05); text-align: center; }
    .status-card i { font-size: 1.75rem; color: var(--primary-violet); }
    .btn-primary-custom { background-color: var(--primary-violet); color: #fff; border: none; border-radius: 50rem; font-weight: 600; }
    .btn-primary-custom:hover { background-color: var(--primary-violet-dark); color: #fff; }
    .dropdown-item:active { background-color: var(--primary-violet); }
  </style>
  @stack('styles')
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="px-3 mb-4">
      <h4 class="fw-bold text-center text-brand">🎉 EventKita</h4>
    </div>
    <ul class="nav flex-column px-3">
      <!-- Gunakan helper 'request()->routeIs()' untuk menandai link aktif secara dinamis -->
      <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-calendar-event me-2"></i> Kelola Event</a></li>
      <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-tags me-2"></i> Kelola Kategori</a></li>
    </ul>
  </div>

  <div class="main-content">
    <!-- Topbar (Header Konten) -->
    <header class="topbar">
      <h5 class="mb-0 fw-bold">@yield('page-title', 'Halaman Admin')</h5>
      
      <!-- Dropdown User yang Anda minta -->
      <div class="dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle fw-semibold" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-person-circle fs-5 me-1"></i> {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
      </div>

    </header>

    <!-- Konten Halaman akan dimasukkan di sini -->
    <main class="p-3">
      @yield('content')
    </main>
    
  </div>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>