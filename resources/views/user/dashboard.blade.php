@extends('layouts.app')

@section('title', 'Dashboard Anggota - EventKita')
@section('body-class', 'bg-dashboard')

@section('content')
<section class="dashboard-section py-5">
    <div class="container">

        {{-- Header User --}}
        <div class="text-center mb-5">
            <div class="position-relative d-inline-block">
                <div class="dashboard-avatar mx-auto mb-3">
                    <i class="bi bi-person-fill text-primary fs-1"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-1 text-dark">{{ Auth::user()->name }} 👋</h2>
            <p class="text-muted mb-4">Kelola event favorit, kehadiran, dan profil kamu di sini</p>

            {{-- Statistik Ringkas --}}
            <div class="row justify-content-center g-3 mb-4">
                <div class="col-4 col-md-2">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <i class="bi bi-star-fill text-warning fs-4"></i>
                            <p class="fw-semibold mb-0">5 Favorit</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <i class="bi bi-calendar2-event text-success fs-4"></i>
                            <p class="fw-semibold mb-0">2 Akan Hadir</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <i class="bi bi-person-lines-fill text-primary fs-4"></i>
                            <p class="fw-semibold mb-0">Profil</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigasi Tab --}}
            <ul class="nav nav-pills justify-content-center shadow-sm bg-white rounded-4 d-inline-flex px-3 py-2" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold px-4" id="favorit-tab" data-bs-toggle="pill" data-bs-target="#favorit" type="button">
                        <i class="bi bi-heart-fill text-danger me-1"></i> Favorit
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold px-4" id="going-tab" data-bs-toggle="pill" data-bs-target="#going" type="button">
                        <i class="bi bi-ticket-perforated-fill text-success me-1"></i> Akan Hadir
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold px-4" id="profil-tab" data-bs-toggle="pill" data-bs-target="#profil" type="button">
                        <i class="bi bi-person-badge-fill text-primary me-1"></i> Profil Saya
                    </button>
                </li>
            </ul>
        </div>

        {{-- Konten Tab --}}
        <div class="tab-content" id="dashboardTabsContent">

            {{-- TAB FAVORIT --}}
            <div class="tab-pane fade show active" id="favorit" role="tabpanel">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @php
                        $favoritEvents = [
                            ['judul' => 'Festival Musik Nusantara', 'tanggal' => '10 Nov 2025', 'kategori' => 'Musik', 'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg'],
                            ['judul' => 'Pesta Kuliner Nusantara', 'tanggal' => '25 Nov 2025', 'kategori' => 'Kuliner', 'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2023/04/12/IMG-20230412-WA0023.jpg'],
                            ['judul' => 'Konser Indie Jakarta', 'tanggal' => '28 Nov 2025', 'kategori' => 'Musik', 'gambar' => 'https://cdn.rri.co.id/berita/Palembang/o/1729861047903-IMG-20241025-WA0035/00671dx4219z1bu.jpeg'],
                        ];
                    @endphp

                    @foreach ($favoritEvents as $event)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden hover-scale">
                                <img src="{{ $event['gambar'] }}" class="card-img-top card-img-cover" alt="{{ $event['judul'] }}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold">{{ $event['judul'] }}</h5>
                                    <p class="text-muted small mb-2"><i class="bi bi-calendar-event"></i> {{ $event['tanggal'] }}</p>
                                    <span class="badge badge-gradient mb-3">{{ $event['kategori'] }}</span>
                                    <button class="btn btn-sm btn-outline-danger w-100 mt-auto rounded-3">
                                        <i class="bi bi-trash"></i> Hapus Favorit
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- TAB AKAN HADIR --}}
            <div class="tab-pane fade" id="going" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            <div>
                                <h6 class="fw-semibold mb-0">Pameran Teknologi AI</h6>
                                <small class="text-muted">15 Nov 2025 — ICE BSD</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle"></i> Batal Hadir
                            </button>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                            <div>
                                <h6 class="fw-semibold mb-0">Jakarta Marathon</h6>
                                <small class="text-muted">20 Nov 2025 — GBK</small>
                            </div>
                            <button class="btn btn-sm btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle"></i> Batal Hadir
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- TAB PROFIL --}}
            <div class="tab-pane fade" id="profil" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-circle text-primary me-2"></i> Profil Saya</h5>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control rounded-3" value="{{ Auth::user()->name }} ">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control rounded-3" value="{{ Auth::user()->email }} ">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Kata Sandi</label>
                                <input type="password" class="form-control rounded-3" value="********">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="button" class="btn btn-brand-gradient fw-semibold w-100 rounded-3">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
