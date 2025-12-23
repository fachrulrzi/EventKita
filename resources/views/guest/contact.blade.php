@extends('layouts.app')

@section('title', 'Hubungi Admin - EventKita')

@section('content')
<style>
    .contact-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        padding: 80px 0;
        color: white;
    }
    
    .contact-hero h1 {
        font-size: 3rem;
        font-weight: 800;
    }
    
    .contact-section {
        padding: 60px 0;
    }
    
    .contact-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .contact-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin: 0 auto 20px;
    }
    
    .contact-card h5 {
        font-weight: 700;
        color: #1e1b4b;
    }
    
    .contact-card p {
        color: #6c757d;
    }
    
    .contact-card a.btn {
        margin-top: 15px;
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }
    
    .form-label {
        font-weight: 600;
        color: #1e1b4b;
    }
    
    .info-card {
        background: linear-gradient(135deg, #1e1b4b, #312e81);
        border-radius: 16px;
        padding: 40px;
        color: white;
        height: 100%;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
    }
    
    .info-item:last-child {
        margin-bottom: 0;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .info-content h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .info-content p {
        opacity: 0.8;
        margin-bottom: 0;
        font-size: 0.95rem;
    }
    
    .social-links {
        display: flex;
        gap: 12px;
        margin-top: 30px;
    }
    
    .social-link {
        width: 45px;
        height: 45px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .social-link:hover {
        background: #0d6efd;
        color: white;
        transform: translateY(-3px);
    }
</style>

{{-- Hero Section --}}
<section class="contact-hero">
    <div class="container text-center">
        <h1 class="mb-4">Hubungi Kami</h1>
        <p class="lead mb-0 opacity-75">Ada pertanyaan atau saran? Kami siap mendengarkan!</p>
    </div>
</section>

{{-- Quick Contact Section --}}
<section class="contact-section bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <h5>WhatsApp</h5>
                    <p class="mb-0">Respon cepat untuk pertanyaan Anda</p>
                    <a href="https://wa.me/6285884653526" target="_blank" class="btn btn-success rounded-pill px-4">
                        <i class="bi bi-whatsapp me-2"></i>Chat Sekarang
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <h5>Email</h5>
                    <p class="mb-0">Untuk pertanyaan formal atau kerjasama</p>
                    <a href="mailto:support@eventkita.com" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-envelope me-2"></i>Kirim Email
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5>Forum Diskusi</h5>
                    <p class="mb-0">Diskusi dengan komunitas EventKita</p>
                    <a href="{{ route('forum.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="bi bi-chat-dots me-2"></i>Buka Forum
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact Form & Info Section --}}
<section class="contact-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="form-card">
                    <h4 class="fw-bold mb-4">Kirim Pesan</h4>
                    <form action="#" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" placeholder="Masukkan nama Anda" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="email@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Subjek</label>
                                <select class="form-select" name="subject" required>
                                    <option value="">Pilih subjek...</option>
                                    <option value="general">Pertanyaan Umum</option>
                                    <option value="order">Masalah Pemesanan</option>
                                    <option value="refund">Refund/Pembatalan</option>
                                    <option value="partnership">Kerjasama/Partnership</option>
                                    <option value="bug">Laporan Bug/Masalah Teknis</option>
                                    <option value="suggestion">Saran/Masukan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Pesan</label>
                                <textarea class="form-control" name="message" rows="5" placeholder="Tuliskan pesan Anda di sini..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                    <i class="bi bi-send me-2"></i>Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="info-card">
                    <h4 class="fw-bold mb-4">Informasi Kontak</h4>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="info-content">
                            <h6>Alamat</h6>
                            <p>Bandung, Jawa Barat, Indonesia</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="info-content">
                            <h6>Telepon</h6>
                            <p>+62 858-8465-3526</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h6>Email</h6>
                            <p>support@eventkita.com</p>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="info-content">
                            <h6>Jam Operasional</h6>
                            <p>Senin - Jumat: 09:00 - 17:00 WIB<br>Sabtu: 09:00 - 14:00 WIB</p>
                        </div>
                    </div>
                    
                    <hr class="border-light opacity-25 my-4">
                    
                    <h6 class="mb-3">Ikuti Kami</h6>
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
