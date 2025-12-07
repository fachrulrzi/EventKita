@extends('layouts.app')

@section('title', 'Daftar Akun | EventKita')
@section('body-class', 'bg-brand-surface')

@section('content')
<section class="section-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold mb-2 text-brand">Buat Akun EventKita</h3>
                        <p class="text-muted small">Gabung dan temukan event seru di sekitarmu 🎉</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <input id="name" 
                                   type="text" 
                                   class="form-control rounded-3 p-3 @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   placeholder="Contoh: Fachrul Rozi"
                                   required 
                                   autocomplete="name" 
                                   autofocus>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control rounded-3 p-3 @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="nama@email.com"
                                   required 
                                   autocomplete="email">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                            <input id="password" 
                                   type="password" 
                                   class="form-control rounded-3 p-3 @error('password') is-invalid @enderror" 
                                   name="password" 
                                   placeholder="Minimal 8 karakter"
                                   required 
                                   autocomplete="new-password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Kata Sandi</label>
                            <input id="password-confirm" 
                                   type="password" 
                                   class="form-control rounded-3 p-3" 
                                   name="password_confirmation" 
                                   placeholder="Ulangi kata sandi"
                                   required 
                                   autocomplete="new-password">
                        </div>

                        {{-- Tombol Daftar --}}
                        <button type="submit" 
                            class="btn btn-brand-gradient w-100 py-3 fw-semibold rounded-3">
                            Daftar Sekarang
                        </button>

                        {{-- Garis Pemisah --}}
                        <div class="text-center my-4 position-relative">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                sudah punya akun?
                            </span>
                        </div>

                        {{-- Tombol ke Login --}}
                                <a href="{{ route('login') }}" 
                                    class="btn btn-brand-outline w-100 py-3 fw-semibold rounded-3">
                            Masuk Sekarang
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
