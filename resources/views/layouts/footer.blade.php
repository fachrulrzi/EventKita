<footer class="site-footer mt-5">
    <div class="footer-top pt-5 pb-4">
        <div class="container">
            <div class="row gy-5">
                <div class="col-lg-4 col-md-12 text-center text-lg-start">
                    <a class="footer-brand d-inline-block mb-3" href="{{ url('/') }}">
                        <span class="fs-2 fw-bold text-white">🎉 Event<span class="text-primary">Kita</span></span>
                    </a>
                    <p class="footer-bio mb-4 opacity-75">
                        Solusi terlengkap untuk menemukan pengalaman tak terlupakan. Dari konser megah hingga komunitas hangat, semua ada di genggamanmu.
                    </p>
                    <div class="social-links d-flex justify-content-center justify-content-lg-start gap-3">
                        <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-4 col-6 ps-lg-5">
                    <h6 class="text-white fw-bold mb-4 text-uppercase small" style="letter-spacing: 2px;">Jelajahi</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ route('kategori') }}">Kategori Event</a></li>
                        <li><a href="{{ route('forum.index') }}">Forum Komunitas</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-4 col-6">
                    <h6 class="text-white fw-bold mb-4 text-uppercase small" style="letter-spacing: 2px;">Bantuan</h6>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Kemitraan</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-4 col-12 text-center text-md-start">
                    <div class="cta-box p-4 rounded-4">
                        <h6 class="text-white fw-bold mb-2">Punya Event Sendiri?</h6>
                        <p class="small text-white-50 mb-3">Promosikan eventmu secara gratis dan jangkau ribuan audiens setiap harinya.</p>
                        <a href="https://wa.me/6285884653526?text=Halo%20Admin%2C%20saya%20ingin%20mendaftarkan%20event%20baru%20di%20EventKita." 
                           target="_blank" 
                           class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                            <i class="bi bi-whatsapp me-2"></i>Daftarkan Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span class="small opacity-50 text-white">© 2025 EventKita. All Rights Reserved.</span>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="small opacity-50 text-white">Dibuat dengan <i class="bi bi-heart-fill text-danger mx-1"></i> untuk Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Reset & Background */
    .site-footer {
        background-color: #0b0f19; /* Deep Slate Black */
        color: #e9ecef;
        font-family: 'Inter', sans-serif;
    }

    .footer-top {
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Typography */
    .footer-brand {
        text-decoration: none;
    }
    
    .footer-bio {
        font-size: 0.95rem;
        line-height: 1.7;
    }

    /* Links Animation */
    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        text-decoration: none;
        color: rgba(255, 255, 255, 0.6);
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }

    .footer-links a:hover {
        color: #0d6efd;
        padding-left: 8px;
    }

    /* Social Icons */
    .social-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.03);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .social-icon:hover {
        background: #0d6efd;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
    }

    /* CTA Box */
    .cta-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-bottom {
        background-color: #080b12;
    }

    /* Responsive adjustment */
    @media (max-width: 768px) {
        .ps-lg-5 {
            padding-left: 0.75rem !important;
        }
    }
</style>