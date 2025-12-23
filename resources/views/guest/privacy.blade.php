@extends('layouts.app')

@section('title', 'Kebijakan Privasi - EventKita')

@section('content')
<style>
    .privacy-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        padding: 80px 0;
        color: white;
    }
    
    .privacy-hero h1 {
        font-size: 3rem;
        font-weight: 800;
    }
    
    .privacy-section {
        padding: 60px 0;
    }
    
    .privacy-content {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .privacy-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e1b4b;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    
    .privacy-content h2:first-child {
        margin-top: 0;
    }
    
    .privacy-content p, .privacy-content li {
        color: #6c757d;
        line-height: 1.8;
    }
    
    .privacy-content ul, .privacy-content ol {
        padding-left: 20px;
    }
    
    .privacy-content li {
        margin-bottom: 10px;
    }
    
    .last-updated {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .highlight-box {
        background: linear-gradient(135deg, #e0f2fe, #dbeafe);
        border-left: 4px solid #0d6efd;
        padding: 20px;
        border-radius: 0 8px 8px 0;
        margin: 20px 0;
    }
    
    .highlight-box p {
        color: #1e40af;
        margin-bottom: 0;
    }
</style>

{{-- Hero Section --}}
<section class="privacy-hero">
    <div class="container text-center">
        <h1 class="mb-4">Kebijakan Privasi</h1>
        <p class="lead mb-0 opacity-75">Komitmen kami dalam melindungi data pribadi Anda</p>
    </div>
</section>

{{-- Content Section --}}
<section class="privacy-section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="last-updated mb-4">
                    <i class="bi bi-clock me-2"></i>
                    Terakhir diperbarui: 23 Desember 2025
                </div>
                
                <div class="privacy-content">
                    <div class="highlight-box">
                        <p>
                            <i class="bi bi-shield-check me-2"></i>
                            <strong>EventKita berkomitmen</strong> untuk melindungi privasi dan data pribadi Anda. 
                            Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi 
                            informasi Anda.
                        </p>
                    </div>
                    
                    <h2>1. Informasi yang Kami Kumpulkan</h2>
                    <p>
                        Kami mengumpulkan beberapa jenis informasi untuk memberikan layanan terbaik:
                    </p>
                    <ul>
                        <li>
                            <strong>Informasi Akun:</strong> Nama, alamat email, nomor telepon, dan kata sandi 
                            yang Anda berikan saat mendaftar
                        </li>
                        <li>
                            <strong>Informasi Transaksi:</strong> Detail pembelian tiket, riwayat pembayaran, 
                            dan informasi billing
                        </li>
                        <li>
                            <strong>Informasi Penggunaan:</strong> Cara Anda berinteraksi dengan platform, 
                            halaman yang dikunjungi, dan fitur yang digunakan
                        </li>
                        <li>
                            <strong>Informasi Perangkat:</strong> Alamat IP, jenis browser, sistem operasi, 
                            dan informasi teknis lainnya
                        </li>
                    </ul>
                    
                    <h2>2. Penggunaan Informasi</h2>
                    <p>
                        Informasi yang kami kumpulkan digunakan untuk:
                    </p>
                    <ul>
                        <li>Memproses dan mengelola akun Anda</li>
                        <li>Memproses transaksi pembelian tiket</li>
                        <li>Mengirimkan e-ticket dan konfirmasi pesanan</li>
                        <li>Memberikan notifikasi tentang event dan promosi</li>
                        <li>Meningkatkan kualitas layanan dan pengalaman pengguna</li>
                        <li>Mencegah penipuan dan menjaga keamanan platform</li>
                        <li>Mematuhi kewajiban hukum yang berlaku</li>
                    </ul>
                    
                    <h2>3. Berbagi Informasi</h2>
                    <p>
                        Kami tidak menjual data pribadi Anda. Informasi Anda hanya dibagikan kepada:
                    </p>
                    <ul>
                        <li>
                            <strong>Event Organizer:</strong> Informasi yang diperlukan untuk memproses 
                            kehadiran Anda di event
                        </li>
                        <li>
                            <strong>Payment Gateway:</strong> Data yang diperlukan untuk memproses pembayaran 
                            (Midtrans)
                        </li>
                        <li>
                            <strong>Penyedia Layanan:</strong> Pihak ketiga yang membantu operasional platform 
                            (hosting, email service)
                        </li>
                        <li>
                            <strong>Otoritas Hukum:</strong> Jika diwajibkan oleh hukum atau proses hukum
                        </li>
                    </ul>
                    
                    <h2>4. Keamanan Data</h2>
                    <p>
                        Kami menerapkan langkah-langkah keamanan untuk melindungi data Anda:
                    </p>
                    <ul>
                        <li>Enkripsi SSL untuk semua transmisi data</li>
                        <li>Penyimpanan kata sandi dengan hashing yang aman</li>
                        <li>Pembatasan akses ke data pribadi</li>
                        <li>Pemantauan keamanan secara berkala</li>
                        <li>Backup data secara teratur</li>
                    </ul>
                    
                    <h2>5. Cookies</h2>
                    <p>
                        Platform kami menggunakan cookies untuk:
                    </p>
                    <ul>
                        <li>Menjaga sesi login Anda tetap aktif</li>
                        <li>Mengingat preferensi dan pengaturan Anda</li>
                        <li>Menganalisis penggunaan platform untuk peningkatan layanan</li>
                    </ul>
                    <p>
                        Anda dapat mengatur browser untuk menolak cookies, namun beberapa fitur 
                        mungkin tidak berfungsi dengan baik.
                    </p>
                    
                    <h2>6. Hak Anda</h2>
                    <p>
                        Anda memiliki hak untuk:
                    </p>
                    <ul>
                        <li>Mengakses data pribadi yang kami simpan tentang Anda</li>
                        <li>Memperbarui atau memperbaiki data pribadi Anda</li>
                        <li>Meminta penghapusan data pribadi Anda</li>
                        <li>Menarik persetujuan penggunaan data untuk pemasaran</li>
                        <li>Mendapatkan salinan data pribadi Anda</li>
                    </ul>
                    
                    <h2>7. Retensi Data</h2>
                    <p>
                        Kami menyimpan data pribadi Anda selama:
                    </p>
                    <ul>
                        <li>Akun Anda masih aktif</li>
                        <li>Diperlukan untuk memberikan layanan</li>
                        <li>Diwajibkan untuk keperluan hukum, akuntansi, atau pelaporan</li>
                    </ul>
                    <p>
                        Setelah tidak diperlukan lagi, data akan dihapus atau dianonimkan secara aman.
                    </p>
                    
                    <h2>8. Perubahan Kebijakan</h2>
                    <p>
                        Kami dapat memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan signifikan 
                        akan diinformasikan melalui email atau notifikasi di platform. Kami menyarankan 
                        Anda untuk meninjau kebijakan ini secara berkala.
                    </p>
                    
                    <h2>9. Kontak</h2>
                    <p>
                        Jika Anda memiliki pertanyaan tentang kebijakan privasi atau ingin menggunakan 
                        hak Anda, silakan hubungi kami:
                    </p>
                    <ul>
                        <li>Email: privacy@eventkita.com</li>
                        <li>WhatsApp: +62 858-8465-3526</li>
                    </ul>
                    
                    <div class="highlight-box mt-4">
                        <p>
                            <i class="bi bi-info-circle me-2"></i>
                            Dengan menggunakan platform EventKita, Anda menyetujui praktik yang dijelaskan 
                            dalam kebijakan privasi ini.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
