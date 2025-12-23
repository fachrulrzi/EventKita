@extends('layouts.app')

@section('title', 'Reset Password | EventKita')

@section('content')
<style>
    /* Custom Styles untuk Reset Password Page */
    .reset-pass-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .icon-wrapper {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin: 0 auto 20px;
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
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
            
            <div class="card reset-pass-card">
                <div class="card-body p-4 p-md-5">
                    
                    {{-- Header Visual --}}
                    <div class="text-center mb-4">
                        <div class="icon-wrapper">
                            <i class="bi bi-shield-lock-fill fs-2"></i>
                        </div>
                        <h4 class="fw-bold text-dark">Atur Ulang Password</h4>
                        <p class="text-muted small">Silakan buat password baru yang kuat untuk akun Anda.</p>
                    </div>

                    {{-- Form Reset Password --}}
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email Input --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold small text-uppercase text-muted">{{ __('Alamat Email') }}</label>
                            <input id="email" type="email" 
                                   class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                   placeholder="nama@email.com">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold small text-uppercase text-muted">{{ __('Password Baru') }}</label>
                            <input id="password" type="password" 
                                   class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="new-password"
                                   placeholder="Minimal 8 karakter">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-bold small text-uppercase text-muted">{{ __('Konfirmasi Password') }}</label>
                            <input id="password-confirm" type="password" 
                                   class="form-control form-control-lg" 
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Ulangi password baru">
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-reset w-100 text-white shadow-sm">
                                {{ __('Reset Password Sekarang') }} <i class="bi bi-arrow-right-circle ms-2"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection