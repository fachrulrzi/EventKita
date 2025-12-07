@extends('layouts.app')

@section('title', 'Verifikasi Email | EventKita')
@section('body-class', 'bg-brand-surface')

@section('content')
<section class="section-auth">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card auth-card text-center">
                    <div class="mb-4">
                        <div class="icon-circle mx-auto mb-3">
                            <i class="bi bi-envelope-paper-heart fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-brand">Verifikasi Email Kamu</h4>
                        <p class="text-muted small mb-0">Kami telah mengirimkan link verifikasi ke alamat email kamu.</p>
                    </div>

                    @if (session('resent'))
                        <div class="alert alert-success small" role="alert">
                            Link verifikasi baru telah dikirim ke email kamu.
                        </div>
                    @endif

                    <p class="text-muted small mt-3">
                        Sebelum melanjutkan, silakan periksa email kamu dan klik tautan verifikasi.
                    </p>
                    <p class="text-muted small mb-4">
                        Jika belum menerima email, kamu bisa meminta ulang di bawah ini:
                    </p>

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" 
                            class="btn btn-brand-gradient w-100 py-3 fw-semibold rounded-3">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>

                    <hr class="my-4">

                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-decoration-none small text-muted">
                        Bukan akun kamu? <span class="fw-semibold text-brand">Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
