@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - EventKita')

@section('content')
<style>
    .terms-hero {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        padding: 80px 0;
        color: white;
    }
    
    .terms-hero h1 {
        font-size: 3rem;
        font-weight: 800;
    }
    
    .terms-section {
        padding: 60px 0;
    }
    
    .terms-content {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    .terms-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e1b4b;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    
    .terms-content h2:first-child {
        margin-top: 0;
    }
    
    .terms-content p, .terms-content li {
        color: #6c757d;
        line-height: 1.8;
    }
    
    .terms-content ul, .terms-content ol {
        padding-left: 20px;
    }
    
    .terms-content li {
        margin-bottom: 10px;
    }
    
    .last-updated {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px 20px;
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>

{{-- Hero Section --}}
<section class="terms-hero">
    <div class="container text-center">
        <h1 class="mb-4">Syarat & Ketentuan</h1>
        <p class="lead mb-0 opacity-75">Ketentuan penggunaan platform EventKita</p>
    </div>
</section>

{{-- Content Section --}}
<section class="terms-section bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="last-updated mb-4">
                    <i class="bi bi-clock me-2"></i>
                    Terakhir diperbarui: 23 Desember 2025
                </div>
                
                <div class="terms-content">
                    <h2>1. Ketentuan Umum</h2>
                    <p>
                        Dengan mengakses dan menggunakan platform EventKita, Anda menyetujui untuk terikat 
                        dengan syarat dan ketentuan ini. Jika Anda tidak menyetujui syarat dan ketentuan ini, 
                        mohon untuk tidak menggunakan layanan kami.
                    </p>
                    <p>
                        EventKita berhak untuk mengubah syarat dan ketentuan ini sewaktu-waktu tanpa 
                        pemberitahuan terlebih dahulu. Perubahan akan efektif segera setelah dipublikasikan 
                        di platform kami.
                    </p>
                    
                    <h2>2. Definisi</h2>
                    <ul>
                        <li><strong>Platform</strong>: Website dan aplikasi EventKita</li>
                        <li><strong>Pengguna</strong>: Individu yang mengakses atau menggunakan platform</li>
                        <li><strong>Event Organizer</strong>: Pihak yang membuat dan mengelola event di platform</li>
                        <li><strong>Tiket</strong>: Bukti pembelian hak untuk menghadiri event</li>
                    </ul>
                    
                    <h2>3. Pendaftaran Akun</h2>
                    <p>
                        Untuk menggunakan beberapa fitur platform, Anda harus mendaftar akun dengan:
                    </p>
                    <ul>
                        <li>Memberikan informasi yang akurat dan lengkap</li>
                        <li>Menjaga kerahasiaan kata sandi akun Anda</li>
                        <li>Bertanggung jawab atas semua aktivitas yang terjadi di akun Anda</li>
                        <li>Memberitahu kami segera jika ada penggunaan tidak sah atas akun Anda</li>
                    </ul>
                    
                    <h2>4. Pembelian Tiket</h2>
                    <p>
                        Ketentuan pembelian tiket di EventKita:
                    </p>
                    <ul>
                        <li>Harga tiket sudah termasuk biaya layanan kecuali dinyatakan lain</li>
                        <li>Pembayaran harus diselesaikan dalam waktu yang ditentukan</li>
                        <li>E-ticket akan dikirim ke email setelah pembayaran berhasil</li>
                        <li>Tiket tidak dapat dipindahtangankan kecuali dengan persetujuan organizer</li>
                        <li>EventKita tidak bertanggung jawab atas kehilangan atau kerusakan tiket</li>
                    </ul>
                    
                    <h2>5. Kebijakan Pembatalan & Refund</h2>
                    <p>
                        Kebijakan pembatalan dan refund mengikuti ketentuan berikut:
                    </p>
                    <ul>
                        <li>Pembatalan oleh pengguna tidak berhak atas refund kecuali event dibatalkan</li>
                        <li>Jika event dibatalkan oleh organizer, refund akan diproses sesuai kebijakan organizer</li>
                        <li>Proses refund membutuhkan waktu 7-14 hari kerja</li>
                        <li>EventKita tidak menjamin refund untuk tiket yang sudah dibeli</li>
                    </ul>
                    
                    <h2>6. Hak dan Kewajiban Pengguna</h2>
                    <p>Sebagai pengguna, Anda berhak:</p>
                    <ul>
                        <li>Mengakses dan menggunakan fitur platform sesuai ketentuan</li>
                        <li>Mendapatkan informasi event yang akurat</li>
                        <li>Mendapatkan e-ticket setelah pembayaran berhasil</li>
                    </ul>
                    <p>Sebagai pengguna, Anda berkewajiban:</p>
                    <ul>
                        <li>Tidak menyalahgunakan platform untuk tujuan ilegal</li>
                        <li>Tidak melakukan spam, phishing, atau aktivitas berbahaya lainnya</li>
                        <li>Menghormati hak kekayaan intelektual pihak lain</li>
                        <li>Mematuhi semua peraturan yang berlaku</li>
                    </ul>
                    
                    <h2>7. Batasan Tanggung Jawab</h2>
                    <p>
                        EventKita tidak bertanggung jawab atas:
                    </p>
                    <ul>
                        <li>Pembatalan atau perubahan event oleh organizer</li>
                        <li>Kualitas atau keamanan event yang diselenggarakan</li>
                        <li>Kerugian yang timbul dari penggunaan platform</li>
                        <li>Gangguan teknis atau downtime platform</li>
                    </ul>
                    
                    <h2>8. Hak Kekayaan Intelektual</h2>
                    <p>
                        Seluruh konten di platform EventKita termasuk logo, desain, teks, dan grafis 
                        adalah milik EventKita dan dilindungi oleh hak cipta. Pengguna tidak diperkenankan 
                        menyalin, memodifikasi, atau mendistribusikan konten tanpa izin tertulis.
                    </p>
                    
                    <h2>9. Hukum yang Berlaku</h2>
                    <p>
                        Syarat dan ketentuan ini diatur oleh hukum Republik Indonesia. Segala perselisihan 
                        yang timbul akan diselesaikan melalui musyawarah atau pengadilan yang berwenang 
                        di Indonesia.
                    </p>
                    
                    <h2>10. Kontak</h2>
                    <p>
                        Jika Anda memiliki pertanyaan mengenai syarat dan ketentuan ini, silakan hubungi kami:
                    </p>
                    <ul>
                        <li>Email: support@eventkita.com</li>
                        <li>WhatsApp: +62 858-8465-3526</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
