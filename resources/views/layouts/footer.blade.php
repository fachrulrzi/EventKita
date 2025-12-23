<footer class="site-footer mt-auto">
    {{-- BAGIAN ATAS: Widget & Link --}}
    <div class="footer-top py-5">
        <div class="container">
            <div class="row gy-5 align-items-start"> {{-- align-items-start agar isinya rata atas --}}
                
                {{-- KOLOM 1: Brand & Deskripsi --}}
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <a class="d-inline-flex align-items-center mb-3 text-decoration-none" href="{{ url('/') }}">
                        <span class="fs-2 me-2">🎉</span>
                        <span class="fs-3 fw-bold text-white tracking-wide">Event<span class="text-primary">Kita</span></span>
                    </a>
                    <p class="text-white-50 lh-lg mb-4 pe-lg-5">
                        Platform nomor #1 untuk menemukan event seru dan membangun komunitas yang solid di sekitarmu.
                    </p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-2">
                        <a href="#" class="social-box"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-box"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="social-box"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-box"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                {{-- KOLOM 2: Navigasi (Lurus Vertikal) --}}
                <div class="col-lg-2 col-md-4 col-6 ps-lg-5">
                    <h6 class="footer-title mb-4">Jelajahi</h6>
                    <ul class="list-unstyled footer-menu">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ route('kategori') }}">Cari Event</a></li>
                        <li><a href="{{ route('forum.index') }}">Forum Diskusi</a></li>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                    </ul>
                </div>

                {{-- KOLOM 3: Bantuan (Lurus Vertikal) --}}
                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="footer-title mb-4">Dukungan</h6>
                    <ul class="list-unstyled footer-menu">
                        <li><a href="{{ route('help') }}">Pusat Bantuan</a></li>
                        <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('contact') }}">Hubungi Admin</a></li>
                    </ul>
                </div>

                {{-- KOLOM 4: CTA Box (Rata Kanan/Tengah) --}}
                <div class="col-lg-4 col-md-4 col-12">
                    <div class="cta-card p-4 rounded-3 border border-white-10">
                        <h6 class="text-white fw-bold mb-2">
                            <i class="bi bi-calendar-plus text-primary me-2"></i>Event Organizer?
                        </h6>
                        <p class="small text-white-50 mb-3">
                            Promosikan acaramu sekarang dan jangkau audiens lebih luas.
                        </p>
                        <a href="https://wa.me/62811547999" target="_blank" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">
                            Daftar Partner
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- BAGIAN BAWAH: Copyright (Garis Lurus Pemisah) --}}
    <div class="footer-bottom py-3 border-top border-white-10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="small text-white-50">© 2025 <strong>EventKita</strong>. All Rights Reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="small text-white-50">
                        Made in Indonesia
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* KONFIGURASI UTAMA */
    .site-footer {
        background-color: #1e1b4b; /* Dark Indigo - Warna solid */
        color: #e2e8f0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin-top: auto; /* Wajib untuk Sticky Footer */
        border-top: 1px solid rgba(0,0,0,0.05); /* Garis halus di atas footer */
    }

    /* TYPOGRAPHY JUDUL KOLOM */
    .footer-title {
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1.2px;
        opacity: 0.8;
    }

    /* MENU LINKS */
    .footer-menu li {
        margin-bottom: 12px;
    }
    .footer-menu a {
        text-decoration: none;
        color: #94a3b8; /* Abu-abu soft */
        font-size: 0.95rem;
        transition: all 0.2s ease;
        display: block; /* Agar lurus */
    }
    .footer-menu a:hover {
        color: #ffffff;
        transform: translateX(5px); /* Efek geser dikit saat hover */
    }

    /* SOCIAL ICONS (KOTAK RAPI) */
    .social-box {
        width: 38px;
        height: 38px;
        background: rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px; /* Sudut sedikit membulat tapi tetap kotak */
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .social-box:hover {
        background: #0d6efd;
        border-color: #0d6efd;
        transform: translateY(-3px);
    }

    /* UTILITIES */
    .border-white-10 {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    /* CTA CARD BACKGROUND */
    .cta-card {
        background: rgba(0, 0, 0, 0.2); /* Gelap transparan agar kontras */
    }

    /* RESPONSIVE */
    @media (max-width: 991px) {
        .ps-lg-5 { padding-left: 0.75rem !important; } /* Reset padding di mobile */
    }
</style>