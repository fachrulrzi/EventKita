@extends('layouts.app')

@section('title', 'Masuk | EventKita')

@section('content')
<style>
    /* 1. AUTH CARD STYLING */
    .auth-card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        background: white;
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-logo {
        width: 60px;
        height: 60px;
        background: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
    }

    /* 2. FORM ELEMENTS */
    .form-floating-custom > .form-control {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding-left: 1rem;
        height: 3.5rem;
    }
    .form-floating-custom > .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .form-floating-custom > label {
        padding-left: 1rem;
        color: #64748b;
    }

    /* 3. BUTTONS */
    .btn-primary-gradient {
        background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.8rem;
        border-radius: 12px;
        transition: transform 0.2s;
    }
    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        color: white;
    }
    
    .btn-outline-custom {
        border: 1px solid #e2e8f0;
        color: #1e293b;
        font-weight: 600;
        padding: 0.8rem;
        border-radius: 12px;
        background: white;
        transition: all 0.2s;
    }
    .btn-outline-custom:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    /* 4. UTILS */
    .divider-text {
        position: relative;
        text-align: center;
        margin: 1.5rem 0;
    }
    .divider-text::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 1px;
        background: #e2e8f0;
        z-index: 0;
    }
    .divider-text span {
        background: white;
        padding: 0 1rem;
        position: relative;
        z-index: 1;
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 500;
    }
</style>

<div class="container py-5 d-flex align-items-center" style="min-height: 85vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5 col-xl-4">
            
            <div class="card auth-card p-4 p-md-5">
                {{-- HEADER --}}
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Selamat Datang Kembali!</h3>
                    <p class="text-muted small">Masuk untuk mengelola event dan tiketmu.</p>
                </div>

                {{-- LOGIN FORM --}}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email Input --}}
                    <div class="form-floating form-floating-custom mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" 
                               placeholder="nama@contoh.com" required autofocus>
                        <label for="email">Alamat Email</label>
                        @error('email')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="form-floating form-floating-custom mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Password" required>
                        <label for="password">Kata Sandi</label>
                        @error('password')
                            <div class="invalid-feedback ps-2">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small text-muted cursor-pointer" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none small fw-bold text-primary">
                                Lupa Sandi?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary-gradient w-100 mb-3">
                        Masuk Sekarang <i class="bi bi-arrow-right-short"></i>
                    </button>

                    {{-- Divider --}}
                    <div class="divider-text">
                        <span>Belum punya akun?</span>
                    </div>

                    {{-- Register Button --}}
                    <a href="{{ route('register') }}" class="btn btn-outline-custom w-100">
                        Daftar Akun Baru
                    </a>
                </form>
            </div>

            {{-- Footer Text --}}
            <div class="text-center mt-4">
                <small class="text-muted">
                    &copy; {{ date('Y') }} <strong>EventKita</strong>. All rights reserved.
                </small>
            </div>

        </div>
    </div>
</div>
@endsection