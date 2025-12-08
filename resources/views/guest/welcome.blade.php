@extends('layouts.app')

@section('title', 'EventKita - Temukan Event Hiburan Terbaik!')
@section('content')
         <section class="hero-section">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    <div class="carousel-item hero-carousel-item active">
                        <img src="https://nnc-media.netralnews.com/2025/08/IMG-Netral-News-Admin-68-6J5H0GLMIW.jpg" class="d-block w-100" alt="Penonton konser">
                    </div>
                    @foreach (range(1, 3) as $index)
                        <div class="carousel-item hero-carousel-item">
                            <img src="https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp" class="d-block w-100" alt="Penonton festival">
                        </div>
                    @endforeach
                </div>
                <div class="hero-carousel-caption d-none d-md-block text-center text-white">
                    <h1 class="display-4 fw-bold hero-title">Temukan Event Paling Seru!</h1>
                    <p class="fs-5 col-md-8 mx-auto hero-subtitle">Semua informasi event hiburan, konser, dan festival ada di sini.</p>
                    <a href="#event-pilihan" class="btn btn-primary-custom btn-lg mt-3">
                        <i class="bi bi-arrow-down-circle"></i> Lihat Event
                    </a>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                </button>
            </div>
        </section>

        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold">Telusuri Kategori</h2>
                @php
                    $placeholderIcon = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='80' height='80'><rect width='100%' height='100%' fill='%23e9ecef'/><text x='50%' y='55%' font-size='14' font-family='Arial' fill='%236c757d' text-anchor='middle'>ICON</text></svg>";
                @endphp
                <div class="row text-center g-4">
                    @forelse ($categories as $category)
                        <div class="col-6 col-md-2 category-bubble">
                            <a href="{{ route('kategori') }}" class="text-decoration-none">
                                <img src="{{ $category->icon_path ? \Illuminate\Support\Facades\Storage::url($category->icon_path) : $placeholderIcon }}" class="rounded-circle mb-2" alt="{{ $category->name }}" width="80" height="80">
                                <h6 class="kategori-title">{{ $category->name }}</h6>
                            </a>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada kategori yang tersedia.</p>
                    @endforelse
                </div>
            </div>
        </section>



            @php
                $featuredEvents = [
                    [
                        'judul' => 'Konser "Nada Senja"',
                        'waktu' => '15 Nov 2025 - Lapangan Banteng',
                        'kategori' => 'Musik',
                        'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                        'slug' => 'konser-nada-senja',
                        'link' => 'https://www.synchronizefestival.com/',
                    ],
                    [
                        'judul' => 'Pekan Startup Nusantara',
                        'waktu' => '01 Des 2025 - ICE BSD',
                        'kategori' => 'Teknologi',
                        'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                        'slug' => 'pekan-startup-nusantara',
                        'link' => 'https://www.futurefest.id/',
                    ],
                    [
                        'judul' => 'Festival Kuliner Nusantara',
                        'waktu' => '10 Des 2025 - Kota Kasablanka',
                        'kategori' => 'Kuliner',
                        'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                        'slug' => 'festival-kuliner-nusantara',
                        'link' => 'https://www.tasteofarchipelago.com/',
                    ],
                    [
                        'judul' => 'Pameran Seni Kontemporer',
                        'waktu' => '18 Nov 2025 - Galeri Nasional',
                        'kategori' => 'Seni',
                        'gambar' => 'https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp',
                        'slug' => 'pameran-seni-kontemporer',
                        'link' => 'https://www.galerinasional.or.id/',
                    ],
                    [
                        'judul' => 'Jogja Jazz Fest',
                        'waktu' => '28 Nov 2025 - Taman Budaya',
                        'kategori' => 'Musik',
                        'gambar' => 'https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp',
                        'slug' => 'jogja-jazz-fest',
                        'link' => 'https://www.jogjazzfest.com/',
                    ],
                    [
                        'judul' => 'Marathon Kota Hujan',
                        'waktu' => '05 Nov 2025 - Kota Bandung',
                        'kategori' => 'Olahraga',
                        'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                        'slug' => 'marathon-kota-hujan',
                        'link' => 'https://www.bandungrunnersclub.com/',
                    ],
                ];
            @endphp

            <section id="event-pilihan" class="album-section bg-soft py-5">
                <div class="container">
                    <h2 class="pb-2 border-bottom mb-4 fw-bold">Event Pilihan Minggu Ini</h2>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                        @foreach ($featuredEvents as $event)
                            <div class="col">
                                <div class="card event-card h-100">
                                    <img src="{{ $event['gambar'] }}" class="card-img-top" alt="{{ $event['judul'] }}">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $event['judul'] }}</h5>
                                        <p class="card-text small text-muted">{{ $event['waktu'] }}</p>
                                        <p class="card-text">Nikmati pengalaman spesial yang dikurasi oleh EventKita.</p>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a href="{{ route('event.detail', ['slug' => $event['slug']]) }}"
                                                    class="btn btn-sm btn-outline-custom">
                                                    Lihat Detail
                                                </a>
                                                <a href="{{ $event['link'] }}" target="_blank"
                                                    class="btn btn-sm btn-primary-custom ms-2">Situs Resmi</a>
                                            </div>
                                            <span class="badge badge-custom">{{ $event['kategori'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

        <section class="py-5 bg-white">
            <div class="container">
                <h2 class="text-center mb-4 fw-bold">Temukan di Kotamu</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-3">
                  @foreach (['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta'] as $kota)
                    <div class="col">
                        <a href="{{ route('kota') }}" class="text-white text-decoration-none">
                            <div class="card city-card">
                                <img src="https://cozzy.id/uploads/0000/630/2024/09/04/cozzyid-hotel-murah-hotel-terdekat-penginapan-murah-penginapan-terdekat-booking-hotel-monumen-nasional-monas-ikon-jakarta-yang-membanggakan-sumber-gambar-kompas.jpg" class="card-img" alt="{{ $kota }}">
                                <div class="card-img-overlay">
                                    <h4>{{ $kota }}</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                     @endforeach
                </div>
            </div>
        </section>








@endsection
