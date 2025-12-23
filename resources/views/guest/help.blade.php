@extends('layouts.app')

@section('title', 'Pusat Bantuan - EventKita')

@section('content')
<style>
    .help-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        padding: 80px 0;
        color: white;
    }
    
    .help-hero h1 {
        font-size: 3rem;
        font-weight: 800;
    }
    
    .help-section {
        padding: 60px 0;
    }
    
    .faq-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .faq-card .accordion-button {
        font-weight: 600;
        padding: 20px 25px;
        background: white;
    }
    
    .faq-card .accordion-button:not(.collapsed) {
        background: #f8f9fa;
        color: #0d6efd;
    }
    
    .faq-card .accordion-body {
        padding: 20px 25px;
        color: #6c757d;
        line-height: 1.8;
    }
    
    .category-card {
        background: white;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    
    .category-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: white;
        margin: 0 auto 20px;
    }
    
    .search-box {
        max-width: 600px;
        margin: 0 auto;
    }
    
    .search-box input {
        border-radius: 50px;
        padding: 15px 25px;
        font-size: 1.1rem;
        border: 2px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        color: white;
    }
    
    .search-box input::placeholder {
        color: rgba(255,255,255,0.7);
    }
    
    .search-box input:focus {
        background: white;
        color: #333;
        border-color: white;
    }
    
    .contact-card {
        background: linear-gradient(135deg, #0d6efd, #6610f2);
        border-radius: 16px;
        padding: 40px;
        color: white;
        text-align: center;
    }
</style>

{{-- Hero Section --}}
<section class="help-hero">
    <div class="container text-center">
        <h1 class="mb-4">Pusat Bantuan</h1>
        <p class="lead mb-4 opacity-75">Ada pertanyaan? Kami siap membantu Anda!</p>
        <div class="search-box">
            <input type="text" class="form-control form-control-lg" placeholder="Cari pertanyaan atau topik...">
        </div>
    </div>
</section>

{{-- Categories Section --}}
<section class="help-section bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Kategori Bantuan</h2>
            <p class="text-muted">Pilih kategori untuk menemukan jawaban yang Anda butuhkan</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="fw-bold">Akun & Profil</h5>
                    <p class="text-muted small mb-0">Registrasi, login, pengaturan profil</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                    <h5 class="fw-bold">Pembelian Tiket</h5>
                    <p class="text-muted small mb-0">Cara membeli, pembayaran, refund</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <h5 class="fw-bold">Event & Organizer</h5>
                    <p class="text-muted small mb-0">Info event, menjadi organizer</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="help-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Pertanyaan yang Sering Diajukan</h2>
            <p class="text-muted">Temukan jawaban untuk pertanyaan umum</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    {{-- FAQ 1 --}}
                    <div class="faq-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Bagaimana cara membeli tiket di EventKita?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Untuk membeli tiket, ikuti langkah berikut:
                                <ol class="mt-2">
                                    <li>Cari event yang Anda inginkan</li>
                                    <li>Klik tombol "Beli Tiket" pada halaman event</li>
                                    <li>Pilih jenis dan jumlah tiket</li>
                                    <li>Lakukan pembayaran melalui metode yang tersedia</li>
                                    <li>E-ticket akan dikirim ke email Anda</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    {{-- FAQ 2 --}}
                    <div class="faq-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Metode pembayaran apa saja yang tersedia?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                EventKita menyediakan berbagai metode pembayaran yang aman dan terpercaya melalui Midtrans, termasuk:
                                <ul class="mt-2">
                                    <li>Transfer Bank (BCA, BNI, BRI, Mandiri, dll)</li>
                                    <li>E-Wallet (GoPay, OVO, DANA, ShopeePay)</li>
                                    <li>Kartu Kredit/Debit</li>
                                    <li>Virtual Account</li>
                                    <li>Gerai Retail (Indomaret, Alfamart)</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    {{-- FAQ 3 --}}
                    <div class="faq-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                Bagaimana cara melihat tiket saya?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Anda dapat melihat tiket yang sudah dibeli melalui:
                                <ol class="mt-2">
                                    <li>Login ke akun EventKita Anda</li>
                                    <li>Klik menu "Tiket Saya" di navbar</li>
                                    <li>Semua tiket yang telah dibeli akan ditampilkan</li>
                                    <li>Klik "Lihat Detail" untuk melihat e-ticket</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    {{-- FAQ 4 --}}
                    <div class="faq-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                Apakah bisa refund tiket yang sudah dibeli?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Kebijakan refund tergantung pada ketentuan dari masing-masing Event Organizer. 
                                Secara umum, tiket tidak dapat di-refund kecuali:
                                <ul class="mt-2">
                                    <li>Event dibatalkan oleh organizer</li>
                                    <li>Event dijadwalkan ulang dan Anda tidak bisa hadir di tanggal baru</li>
                                </ul>
                                Untuk informasi lebih lanjut, silakan hubungi admin atau Event Organizer terkait.
                            </div>
                        </div>
                    </div>
                    
                    {{-- FAQ 5 --}}
                    <div class="faq-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Bagaimana cara menjadi Event Organizer di EventKita?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Untuk menjadi Event Organizer di EventKita:
                                <ol class="mt-2">
                                    <li>Klik tombol "Daftar Partner" di footer website</li>
                                    <li>Hubungi tim kami melalui WhatsApp</li>
                                    <li>Lengkapi data dan dokumen yang diperlukan</li>
                                    <li>Tim kami akan memverifikasi dan menghubungi Anda</li>
                                    <li>Setelah disetujui, Anda dapat mulai membuat event</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact Section --}}
<section class="help-section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="contact-card">
                    <h3 class="fw-bold mb-3">Masih Butuh Bantuan?</h3>
                    <p class="opacity-75 mb-4">Tim support kami siap membantu Anda 24/7</p>
                    <a href="https://wa.me/62811547999" target="_blank" class="btn btn-light btn-lg px-5 rounded-pill fw-bold">
                        <i class="bi bi-whatsapp me-2"></i>Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
