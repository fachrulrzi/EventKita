

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top p-2">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">🎉 EventKita</a>

        <!-- Toggle button (mobile view) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar content -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Search bar in center -->
            <form class="d-flex mx-auto w-50" action="{{ route('search') }}" role="search">
                <input class="form-control me-2" type="search" placeholder="Cari event seru di sini..." name="q">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <!-- Right side (Auth buttons) -->
            <ul class="navbar-nav ms-auto align-items-center">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-outline-custom ms-2">Login</a>
                        </li>
                    @endif
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-primary-custom ms-2">Register</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown ms-3">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center fw-bold" href="#"
                            role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <!-- Foto Profil -->
                               <img src="https://static.vecteezy.com/system/resources/previews/048/216/761/non_2x/modern-male-avatar-with-black-hair-and-hoodie-illustration-free-png.png"
                                   alt="Foto Profil" class="rounded-circle me-2 avatar-thumb" width="32" height="32">

                            <!-- Nama User -->
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('user.dashboard') }}">Dashboard</a>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
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

