@extends('layouts.app')

@section('title', 'Reset Password | EventKita')
@section('body-class', 'bg-brand-panel')

@section('content')
<section class="section-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="text-center mb-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i class="bi bi-shield-lock fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-brand">Atur Ulang Password</h4>
                        <p class="text-muted small mb-0">Masukkan password baru untuk melanjutkan akses akunmu.</p>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input id="email" type="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password Baru</label>
                            <input id="password" type="password"
                                   class="form-control rounded-3 @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback small">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Password</label>
                            <input id="password-confirm" type="password"
                                   class="form-control rounded-3"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                                <button type="submit"
                                    class="btn btn-brand-gradient fw-semibold py-2 rounded-3">
                                Reset Password Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold small text-brand">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
