@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<style>
    /* Styling Khusus Halaman Auth (Email Reset) */
    .reset-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: #0d6efd;
    }
    .form-control-lg {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 1rem;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    .form-control-lg:focus {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .btn-reset {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        border: none;
        padding: 12px;
        font-weight: 600;
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            
            <div class="card reset-card">
                <div class="card-body p-4 p-md-5 text-center">
                    
                    {{-- Header Icon --}}
                    <div class="icon-wrapper">
                        <i class="bi bi-envelope-paper-heart-fill" style="font-size: 2.5rem;"></i>
                    </div>

                    <h4 class="fw-bold text-dark mb-2">{{ __('Lupa Password?') }}</h4>
                    <p class="text-muted small mb-4">
                        {{ __('Jangan khawatir. Masukkan email Anda di bawah ini dan kami akan mengirimkan tautan untuk mengatur ulang password.') }}
                    </p>

                    {{-- Success Alert --}}
                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm rounded-3 small py-2 mb-4 d-flex align-items-center justify-content-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    {{-- Reset Form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4 text-start">
                            <label for="email" class="form-label fw-bold small text-uppercase text-muted">{{ __('Alamat Email') }}</label>
                            <input id="email" type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="nama@email.com">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-reset w-100 text-white shadow-sm">
                                {{ __('Kirim Tautan Reset') }} <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('login') }}" class="text-decoration-none fw-bold text-muted small">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('Kembali ke Halaman Login') }}
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection