@extends('layouts.app')

@php
    use Illuminate\Support\Str;

    $eventLibrary = [
        'konser-nada-senja' => [
            'title' => 'Konser "Nada Senja"',
            'category' => 'Musik',
            'hero_image' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
            'date_text' => 'Sabtu, 15 November 2025',
            'time_text' => '19.00 WIB - 23.00 WIB',
            'location' => 'Lapangan Banteng, Jakarta',
            'price' => 'Rp75.000 - Rp250.000',
            'site_url' => 'https://www.synchronizefestival.com/',
            'description' => 'Konser musik indie terbesar akhir tahun dengan tata panggung immersive, kolaborasi lintas musisi, dan experience booth dari brand lokal.',
            'tags' => ['Indie', 'Festival', 'Outdoor'],
            'schedule' => [
                ['label' => 'Soundcheck & Gate Open', 'time' => '16.00 WIB'],
                ['label' => 'Opening Act', 'time' => '18.30 WIB'],
                ['label' => 'Main Performance', 'time' => '19.00 - 22.30 WIB'],
            ],
            'threads' => [
                ['name' => 'Rama', 'badge' => 'Tiket', 'time' => '2 jam lalu', 'message' => 'Ada bundle tiket untuk 4 orang?'],
                ['name' => 'Panitia Event', 'badge' => 'Panitia', 'time' => '1 jam lalu', 'message' => 'Bundle keluarga tersedia di tiket kategori Silver, cek situs resmi ya!'],
            ],
        ],
        'festival-kuliner-nusantara' => [
            'title' => 'Festival Kuliner Nusantara',
            'category' => 'Kuliner',
            'hero_image' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
            'date_text' => 'Selasa, 10 Desember 2025',
            'time_text' => '10.00 WIB - 22.00 WIB',
            'location' => 'Kota Kasablanka, Jakarta',
            'price' => 'Gratis (registrasi online)',
            'site_url' => 'https://www.synchronizefestival.com/',
            'description' => 'Temukan 70+ tenant kuliner khas nusantara, kelas memasak langsung bersama chef ternama, dan area family picnic.',
            'tags' => ['Kuliner', 'Family Friendly', 'Gratis'],
            'schedule' => [
                ['label' => 'Registrasi Ulang', 'time' => '09.00 WIB'],
                ['label' => 'Demo Masak Chef Rara', 'time' => '13.00 WIB'],
                ['label' => 'Penutupan & Live Music', 'time' => '20.00 WIB'],
            ],
            'threads' => [
                ['name' => 'Sari', 'badge' => 'Akomodasi', 'time' => 'Kemarin', 'message' => 'Apakah tersedia musala dan area menyusui?'],
                ['name' => 'Panitia Event', 'badge' => 'Panitia', 'time' => '2 jam lalu', 'message' => 'Tersedia lengkap, lokasinya dekat area informasi utama.'],
            ],
        ],
        'pameran-seni-kontemporer' => [
            'title' => 'Pameran Seni Kontemporer',
            'category' => 'Seni',
            'hero_image' => 'https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp',
            'date_text' => 'Senin, 18 November 2025',
            'time_text' => '09.00 WIB - 20.00 WIB',
            'location' => 'Galeri Nasional, Bandung',
            'price' => 'Rp50.000',
            'site_url' => 'https://www.synchronizefestival.com/',
            'description' => 'Eksplorasi karya seniman muda dengan instalasi interaktif dan tur privat bersama kurator.',
            'tags' => ['Seni', 'Eksibisi', 'Kurasi'],
            'schedule' => [
                ['label' => 'Tur Pagi', 'time' => '10.00 WIB'],
                ['label' => 'Artist Talk', 'time' => '14.00 WIB'],
                ['label' => 'Workshop Mini', 'time' => '16.00 WIB'],
            ],
            'threads' => [
                ['name' => 'Deni', 'badge' => 'Workshop', 'time' => '30 menit lalu', 'message' => 'Workshop mini perlu membawa alat sendiri?'],
                ['name' => 'Panitia Event', 'badge' => 'Panitia', 'time' => 'Baru saja', 'message' => 'Semua alat disediakan, cukup lakukan registrasi ulang 30 menit sebelum sesi.'],
            ],
        ],
    ];

    $normalizedSlug = Str::slug($slug);
    $event = $eventLibrary[$normalizedSlug] ?? $eventLibrary['konser-nada-senja'];
@endphp

@section('title', $event['title'] . ' | EventKita')
@section('body-class', 'bg-brand-surface')

@section('content')
<section class="event-detail-hero" style="background-image: url('{{ $event['hero_image'] }}');">
    <div class="event-detail-overlay">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <a href="{{ url('/') }}" class="text-white-50 text-decoration-none small">Beranda</a>
                <span class="text-white-50">/</span>
                <a href="{{ route('kategori') }}" class="text-white-50 text-decoration-none small">Event</a>
                <span class="text-white-50">/</span>
                <span class="text-white fw-semibold">{{ $event['title'] }}</span>
            </div>
            <span class="badge badge-custom mb-2">{{ $event['category'] }}</span>
            <h1 class="display-5 fw-bold text-white mb-3">{{ $event['title'] }}</h1>
            <div class="event-detail-meta">
                <div><i class="bi bi-calendar-event"></i> {{ $event['date_text'] }}</div>
                <div><i class="bi bi-clock"></i> {{ $event['time_text'] }}</div>
                <div><i class="bi bi-geo-alt"></i> {{ $event['location'] }}</div>
            </div>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="{{ $event['site_url'] }}" target="_blank" class="btn btn-primary-custom">
                    <i class="bi bi-ticket-perforated me-1"></i> Pesan Tiket
                </a>
                <button class="btn btn-outline-custom">
                    <i class="bi bi-share"></i> Bagikan
                </button>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="detail-info-card mb-4">
                    <h4 class="fw-bold mb-3">Tentang Event</h4>
                    <p class="text-muted">{{ $event['description'] }}</p>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        @foreach ($event['tags'] as $tag)
                            <span class="detail-tag">#{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="detail-info-card mb-4">
                    <h4 class="fw-bold mb-3">Informasi Penting</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-info-pill">
                                <p class="text-muted mb-1">Lokasi</p>
                                <p class="fw-semibold mb-0">{{ $event['location'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill">
                                <p class="text-muted mb-1">Harga</p>
                                <p class="fw-semibold mb-0">{{ $event['price'] }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="detail-info-pill">
                                <p class="text-muted mb-1">Jam</p>
                                <p class="fw-semibold mb-0">{{ $event['time_text'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="detail-info-card mb-4">
                    <h4 class="fw-bold mb-3">Forum Diskusi</h4>
                    @foreach ($event['threads'] as $thread)
                        <div class="forum-thread">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $thread['name'] }}</p>
                                    <small class="text-muted">{{ $thread['time'] }}</small>
                                </div>
                                <span class="badge bg-light text-secondary">{{ $thread['badge'] }}</span>
                            </div>
                            <p class="mt-2 mb-0 text-muted">{{ $thread['message'] }}</p>
                        </div>
                    @endforeach

                    @auth
                        <form class="forum-reply-form" method="POST" action="#">
                            @csrf
                            <label class="form-label fw-semibold small">Tinggalkan pertanyaan</label>
                            <textarea class="form-control rounded-3 mb-2" rows="3" placeholder="Contoh: ada parkir khusus?"></textarea>
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="bi bi-send me-1"></i> Kirim Pertanyaan
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border forum-guest-alert" role="alert">
                            <i class="bi bi-chat-dots me-2"></i>
                            <a href="{{ route('login') }}" class="text-brand fw-semibold text-decoration-none">Login</a>
                            untuk ikut berdiskusi mengenai event ini.
                        </div>
                    @endauth
                </div>
            </div>

            <div class="col-lg-4">
                <div class="detail-side-card mb-4">
                    <h5 class="fw-bold mb-3">Timeline Hari-H</h5>
                    @foreach ($event['schedule'] as $slot)
                        <div class="detail-timeline-item">
                            <div>
                                <p class="fw-semibold mb-0">{{ $slot['label'] }}</p>
                                <small class="text-muted">{{ $slot['time'] }}</small>
                            </div>
                            <i class="bi bi-chevron-right text-muted"></i>
                        </div>
                    @endforeach
                </div>

                <div class="detail-side-card">
                    <h5 class="fw-bold mb-3">Butuh Bantuan?</h5>
                    <p class="text-muted mb-3">Hubungi tim EventKita untuk pengajuan kerjasama atau booth.</p>
                    <a href="https://wa.me/6285884653526" target="_blank" class="btn btn-success w-100">
                        <i class="bi bi-whatsapp me-1"></i> Chat Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
