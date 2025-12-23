@extends('layouts.app')

@section('title', 'Tentang Kami - EventKita')

@section('content')
<style>
    .about-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        padding: 80px 0;
        color: white;
    }
    
    .about-hero h1 {
        font-size: 3rem;
        font-weight: 800;
    }
    
    .about-section {
        padding: 60px 0;
    }
    
    .feature-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        height: 100%;
        transition: transform 0.3s ease;
    }
    
    .feature-card:hover {
        transform: translateY(-5px);
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        margin-bottom: 20px;
    }
    
    .team-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-5px);
    }
    
    .team-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        margin: 30px auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
    }
    
    .stat-card {
        text-align: center;
        padding: 30px;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>

{{-- Hero Section --}}
<section class="about-hero">
    <div class="container text-center">
        <h1 class="mb-4">Tentang <span class="text-primary">EventKita</span></h1>
        <p class="lead mb-0 opacity-75" style="max-width: 600px; margin: 0 auto;">
            Platform nomor #1 untuk menemukan event seru dan membangun komunitas yang solid di Indonesia.
        </p>
    </div>
</section>

{{-- Misi Section --}}
<section class="about-section bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="fw-bold mb-4">Misi Kami</h2>
                <p class="text-muted mb-4">
                    EventKita hadir untuk menghubungkan pecinta event dengan berbagai acara menarik di seluruh Indonesia. 
                    Kami percaya bahwa setiap orang berhak menemukan pengalaman yang tak terlupakan.
                </p>
                <p class="text-muted mb-0">
                    Dengan teknologi modern dan komunitas yang kuat, kami memudahkan Anda untuk menemukan, 
                    membeli tiket, dan berbagi pengalaman event favorit Anda.
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stat-card bg-white rounded-4 shadow-sm">
                            <div class="stat-number">1000+</div>
                            <div class="text-muted">Event Terdaftar</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white rounded-4 shadow-sm">
                            <div class="stat-number">50K+</div>
                            <div class="text-muted">Pengguna Aktif</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white rounded-4 shadow-sm">
                            <div class="stat-number">100+</div>
                            <div class="text-muted">Kota</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card bg-white rounded-4 shadow-sm">
                            <div class="stat-number">500+</div>
                            <div class="text-muted">Event Organizer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features Section --}}
<section class="about-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengapa Memilih EventKita?</h2>
            <p class="text-muted">Fitur unggulan yang membuat pengalaman Anda lebih baik</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="fw-bold">Pencarian Mudah</h5>
                    <p class="text-muted mb-0">
                        Temukan event berdasarkan kategori, lokasi, atau tanggal dengan mudah dan cepat.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <h5 class="fw-bold">Pembayaran Aman</h5>
                    <p class="text-muted mb-0">
                        Transaksi aman dengan berbagai metode pembayaran yang terpercaya.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="fw-bold">Forum Diskusi</h5>
                    <p class="text-muted mb-0">
                        Berbagi pengalaman dan berdiskusi dengan sesama pecinta event.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h5 class="fw-bold">E-Ticket Instan</h5>
                    <p class="text-muted mb-0">
                        Dapatkan tiket digital langsung setelah pembayaran berhasil.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h5 class="fw-bold">Favorit & Notifikasi</h5>
                    <p class="text-muted mb-0">
                        Simpan event favorit dan dapatkan notifikasi terbaru.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold">Terpercaya</h5>
                    <p class="text-muted mb-0">
                        Event terverifikasi dan organizer terpercaya untuk keamanan Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="about-section bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Siap Menemukan Event Menarik?</h2>
        <p class="mb-4 opacity-75">Jelajahi ribuan event menarik di kotamu sekarang juga!</p>
        <a href="{{ route('kategori') }}" class="btn btn-light btn-lg px-5 rounded-pill fw-bold">
            <i class="bi bi-search me-2"></i>Cari Event
        </a>
    </div>
</section>
@endsection
