@extends('layouts.app')

@section('title', 'EventKita - Temukan Event Hiburan Terbaik!')

@section('content')
<div class="wave-divider bg-soft">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path
            d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
            class="shape-fill-white"></path>
    </svg>
</div>

<div class="album-section bg-soft pt-4 pb-5">
    <div class="container">

        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-custom mb-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>

        @php
            $searchResults = [
                [
                    'judul' => 'Konser "Nada Senja"',
                    'waktu' => '15 Nov 2025 - Lapangan Banteng',
                    'kategori' => 'Musik',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                    'slug' => 'konser-nada-senja',
                    'link' => 'https://www.synchronizefestival.com/',
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
                    'judul' => 'Marathon Kota Hujan',
                    'waktu' => '05 Nov 2025 - Kota Bandung',
                    'kategori' => 'Olahraga',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                    'slug' => 'marathon-kota-hujan',
                    'link' => 'https://www.bandungrunnersclub.com/',
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
                    'judul' => 'Pekan Startup Nusantara',
                    'waktu' => '01 Des 2025 - ICE BSD',
                    'kategori' => 'Teknologi',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                    'slug' => 'pekan-startup-nusantara',
                    'link' => 'https://www.futurefest.id/',
                ],
            ];
        @endphp

        <div id="event-card-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($searchResults as $index => $event)
                <div class="col event-col-filterable" data-kota="jakarta" data-tanggal="2025-11-15" data-original-order="{{ $index }}">
                    <div class="card event-card h-100">
                        <img src="{{ $event['gambar'] }}"
                            class="card-img-top" alt="{{ $event['judul'] }}">
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
</div>
@endsection
