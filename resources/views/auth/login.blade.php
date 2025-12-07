@extends('layouts.app')

@section('title', 'Masuk | EventKita')
@section('body-class', 'bg-brand-surface')

@section('content')
<section class="section-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-2 text-brand">Masuk ke EventKita</h3>
                        <p class="text-muted small">Nikmati pengalaman menemukan event favoritmu 🎉</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}" 
                                class="form-control rounded-3 p-3 @error('email') is-invalid @enderror" 
                                placeholder="nama@email.com" 
                                required autofocus>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                            <input type="password" 
                                name="password" 
                                id="password" 
                                class="form-control rounded-3 p-3 @error('password') is-invalid @enderror" 
                                placeholder="••••••••" 
                                required>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Remember + Lupa Password --}}
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat saya
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-decoration-none small fw-semibold text-brand">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>

                        {{-- Tombol Masuk --}}
                        <button type="submit" 
                            class="btn btn-brand-gradient w-100 py-3 fw-semibold rounded-3">
                            Masuk Sekarang
                        </button>

                        {{-- Garis Pemisah --}}
                        <div class="text-center my-4 position-relative">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                atau
                            </span>
                        </div>

                        {{-- Tombol Daftar --}}
                        <a href="{{ route('register') }}" 
                            class="btn btn-brand-outline w-100 py-3 fw-semibold rounded-3">
                            Daftar Akun Baru
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
