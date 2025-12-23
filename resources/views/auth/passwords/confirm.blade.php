@extends('layouts.app')

@section('title', 'Konfirmasi Password')

@section('content')
<style>
    .confirm-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .confirm-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        padding: 30px;
        text-align: center;
        border-bottom: none;
    }
    .lock-icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        backdrop-filter: blur(5px);
    }
    .form-control-lg {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 1rem;
        border: 2px solid #f1f3f5;
    }
    .form-control-lg:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }
    .btn-confirm {
        padding: 12px;
        font-weight: 600;
        border-radius: 10px;
        font-size: 1rem;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
    }
    .btn-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.3);
    }
</style>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 85vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            <div class="card confirm-card">
                {{-- Header Visual --}}
                <div class="confirm-header">
                    <div class="lock-icon-wrapper">
                        <i class="bi bi-shield-lock-fill text-white" style="font-size: 2.5rem;"></i>
                    </div>
                    <h4 class="text-white fw-bold mb-1">{{ __('Konfirmasi Akses') }}</h4>
                    <p class="text-white-50 small mb-0">{{ __('Area ini membutuhkan verifikasi keamanan ekstra.') }}</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <p class="text-muted text-center mb-4 small">
                        {{ __('Silakan masukkan password Anda untuk melanjutkan proses ini.') }}
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-dark small text-uppercase">{{ __('Password Saat Ini') }}</label>
                            
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted ps-3">
                                    <i class="bi bi-key-fill"></i>
                                </span>
                                <input id="password" type="password" 
                                       class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="current-password"
                                       placeholder="Ketik password Anda...">
                                
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-confirm">
                                {{ __('Konfirmasi Password') }}
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link text-decoration-none text-muted small" href="{{ route('password.request') }}">
                                    {{ __('Lupa Password Anda?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="text-decoration-none text-muted small fw-bold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Sebelumnya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection