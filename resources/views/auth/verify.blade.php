@extends('layouts.app')

@section('title', 'Verifikasi Email | EventKita')

@section('content')
<style>
    /* Custom Styling untuk Halaman Verifikasi */
    .verify-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .icon-wrapper {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin: 0 auto 20px;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    .btn-resend {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .btn-resend:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
    .text-divider {
        font-size: 0.85rem;
        color: #6c757d;
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    .text-divider::before {
        content: '';
        position: absolute;
        top: 50%; left: 0; right: 0;
        border-top: 1px solid #e9ecef;
        z-index: -1;
    }
    .text-divider span {
        background: #fff;
        padding: 0 10px;
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            
            <div class="card verify-card">
                <div class="card-body p-4 p-md-5 text-center">
                    
                    {{-- Header Visual --}}
                    <div class="icon-wrapper">
                        <i class="bi bi-envelope-check-fill" style="font-size: 2.5rem;"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">{{ __('Verifikasi Email Kamu') }}</h4>
                    <p class="text-muted small mb-4">
                        {{ __('Terima kasih telah mendaftar! Sebelum memulai, mohon periksa email Anda untuk tautan verifikasi.') }}
                    </p>

                    {{-- Alert Success (Resent) --}}
                    @if (session('resent'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 small py-2 mb-4 d-flex align-items-center justify-content-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </div>
                    @endif

                    {{-- Instruction --}}
                    <div class="bg-light p-3 rounded-3 border border-light mb-4">
                        <p class="mb-0 text-muted small">
                            {{ __('Belum menerima email?') }} <br>
                            {{ __('Periksa folder spam atau klik tombol di bawah untuk kirim ulang.') }}
                        </p>
                    </div>

                    {{-- Resend Form --}}
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-resend w-100 text-white shadow-sm">
                                {{ __('Kirim Ulang Email Verifikasi') }} <i class="bi bi-send ms-1"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Divider --}}
                    <div class="text-divider">
                        <span>atau</span>
                    </div>

                    {{-- Logout Link --}}
                    <div>
                        <a class="text-decoration-none text-muted small" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Bukan akun Anda? <span class="fw-bold text-primary">{{ __('Keluar') }}</span>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection