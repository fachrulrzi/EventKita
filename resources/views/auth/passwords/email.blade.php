@extends('layouts.app')

@section('title', 'Lupa Password | EventKita')
@section('body-class', 'bg-brand-panel')

@section('content')
<section class="section-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card">
                    <div class="text-center mb-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i class="bi bi-envelope-paper fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-brand">Lupa Password</h4>
                        <p class="text-muted small mb-0">
                            Masukkan alamat email kamu, kami akan kirim tautan untuk mengatur ulang password.
                        </p>
                    </div>

                    {{-- Notifikasi sukses --}}
                    @if (session('status'))
                        <div class="alert alert-success small text-center rounded-3 py-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <input id="email" type="email"
                                   class="form-control rounded-3 @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-grid">
                                <button type="submit"
                                    class="btn btn-brand-gradient fw-semibold py-2 rounded-3">
                                Kirim Tautan Reset Password
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
