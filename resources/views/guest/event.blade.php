@extends('layouts.app')

@section('title', 'EventKita - Temukan Event Hiburan Terbaik!')

@section('content')
<section class="album-section bg-soft py-5">
    <div class="container">
        @php
            $kategoriAktif = isset($categories) && $categories->count() ? $categories->first()->name : 'Semua Kategori';
        @endphp

        @if (!request()->is('kota'))
            <h2 class="pb-2 border-bottom mb-3 fw-bold">Kategori: {{ $kategoriAktif }}</h2>
            <p class="mb-4 text-secondary">Temukan event sesuai kategori pilihanmu. Tambahkan kategori baru di panel admin untuk memperkaya pilihan pengguna.</p>

            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <label for="filter-kota" class="form-label small fw-bold">Filter Kota</label>
                    <select id="filter-kota" class="form-select">
                        <option value="semua" selected>Semua Kota</option>
                        <option value="jakarta">Jakarta</option>
                        <option value="yogyakarta">Yogyakarta</option>
                        <option value="bandung">Bandung</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-tanggal" class="form-label small fw-bold">Urutkan</label>
                    <select id="filter-tanggal" class="form-select">
                        <option value="populer" selected>Paling Populer</option>
                        <option value="terdekat">Tanggal Terdekat</option>
                        <option value="terjauh">Tanggal Terjauh</option>
                    </select>
                </div>
            </div>
        @endif

        @if (!request()->is('kategory'))
            <h2 class="pb-2 border-bottom mb-4 fw-bold">Kota: Jakarta</h2>
        @endif

        @if(isset($categories) && $categories->count())
            <div class="mb-4">
                <h5 class="fw-bold mb-2">Semua Kategori</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 d-flex align-items-center gap-2">
                            <img src="{{ $category->icon_path ? \Illuminate\Support\Facades\Storage::url($category->icon_path) : 'https://via.placeholder.com/24?text=Icon' }}" alt="{{ $category->name }}" width="24" height="24" class="rounded-circle border">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $events = [
                [
                    'judul' => 'Konser "Nada Senja"',
                    'tanggal' => '2025-11-15',
                    'waktu' => '15 Nov 2025 - Lapangan Banteng',
                    'kota' => 'jakarta',
                    'kategori' => 'Musik',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                ],
                [
                    'judul' => 'Festival Kuliner Nusantara',
                    'tanggal' => '2025-12-10',
                    'waktu' => '10 Des 2025 - Kota Kasablanka',
                    'kota' => 'jakarta',
                    'kategori' => 'Kuliner',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                ],
                [
                    'judul' => 'Pameran Seni Kontemporer',
                    'tanggal' => '2025-11-18',
                    'waktu' => '18 Nov 2025 - Galeri Nasional',
                    'kota' => 'bandung',
                    'kategori' => 'Seni',
                    'gambar' => 'https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp',
                ],
                [
                    'judul' => 'Marathon Kota Hujan',
                    'tanggal' => '2025-11-05',
                    'waktu' => '05 Nov 2025 - Kota Bandung',
                    'kota' => 'bandung',
                    'kategori' => 'Olahraga',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                ],
                [
                    'judul' => 'Jogja Jazz Fest',
                    'tanggal' => '2025-11-28',
                    'waktu' => '28 Nov 2025 - Taman Budaya',
                    'kota' => 'yogyakarta',
                    'kategori' => 'Musik',
                    'gambar' => 'https://gigsplay.com/wp-content/uploads/2025/10/Synchronize-Fest-2025.jpg.webp',
                ],
                [
                    'judul' => 'Pekan Startup Nusantara',
                    'tanggal' => '2025-12-01',
                    'waktu' => '01 Des 2025 - ICE BSD',
                    'kota' => 'jakarta',
                    'kategori' => 'Teknologi',
                    'gambar' => 'https://cdn.antaranews.com/cache/1200x800/2024/12/24/A64E4496-BFC8-4FC7-A56A-43D2DBCA8411.jpeg',
                ],
            ];
        @endphp

        <div id="event-card-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($events as $index => $event)
                <div class="col event-col-filterable"
                     data-kota="{{ $event['kota'] }}"
                     data-tanggal="{{ $event['tanggal'] }}"
                     data-original-order="{{ $index }}">
                    <div class="card event-card h-100">
                        <img src="{{ $event['gambar'] }}" class="card-img-top" alt="{{ $event['judul'] }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $event['judul'] }}</h5>
                            <p class="card-text small text-muted">{{ $event['waktu'] }}</p>
                            <p class="card-text">Nikmati pengalaman spesial yang dikurasi oleh EventKita.</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="{{ route('event.detail', ['slug' => \Illuminate\Support\Str::slug($event['judul'])]) }}"
                                        class="btn btn-sm btn-outline-custom">
                                        Lihat Detail
                                    </a>
                                    <a href="https://www.synchronizefestival.com/" target="_blank"
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
@endsection
