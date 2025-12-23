@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --success-color: #10b981;
        --border-radius-lg: 1rem;
    }

    /* 1. SUCCESS ANIMATION */
    .success-icon-wrapper {
        width: 100px;
        height: 100px;
        background-color: #d1fae5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .success-icon-wrapper i {
        font-size: 3.5rem;
        color: var(--success-color);
    }

    @keyframes popIn {
        0% { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* 2. ORDER CARD */
    .order-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        overflow: hidden;
        background: white;
    }
    .order-card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 20px 25px;
    }
    .info-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .info-value {
        font-weight: 600;
        color: #1e293b;
        font-size: 1rem;
    }

    /* 3. EVENT THUMBNAIL */
    .event-thumb-small {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 12px;
    }

    /* Action Buttons */
    .btn-action {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            
            {{-- SUCCESS HEADER --}}
            <div class="text-center mb-5">
                <div class="success-icon-wrapper shadow-sm">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h2 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h2>
                <p class="text-muted">Terima kasih, pesanan Anda telah terkonfirmasi.</p>
            </div>

            {{-- ORDER DETAILS CARD --}}
            <div class="card order-card mb-4">
                <div class="order-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Rincian Pesanan</h5>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">LUNAS</span>
                </div>
                
                <div class="card-body p-4">
                    {{-- Order Info Grid --}}
                    <div class="row g-4 mb-4">
                        <div class="col-6">
                            <div class="info-label">Nomor Pesanan</div>
                            <div class="info-value font-monospace">{{ $order->order_number }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="info-label">Metode Pembayaran</div>
                            <div class="info-value">{{ $order->payment_type ?? 'Otomatis' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="info-label">Tanggal Transaksi</div>
                            <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="info-label">Total Dibayar</div>
                            <div class="info-value text-primary fs-5">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    {{-- Event Info --}}
                    <div class="d-flex align-items-center gap-3">
                        @php
                            $imgUrl = $order->event->image_path 
                                ? (str_starts_with($order->event->image_path, 'http') ? $order->event->image_path : \App\Helpers\StorageHelper::url($order->event->image_path)) 
                                : 'https://via.placeholder.com/150';
                        @endphp
                        <img src="{{ $imgUrl }}" class="event-thumb-small shadow-sm" alt="Event">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $order->event->title }}</h5>
                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $order->event->location ?? 'Lokasi Online' }}</p>
                            <p class="text-muted small mb-0"><i class="bi bi-calendar-check me-1"></i> {{ $order->event->date->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- IMPORTANT INFO --}}
            <div class="alert alert-primary border-0 d-flex align-items-start rounded-4 p-4 mb-4 shadow-sm" role="alert">
                <i class="bi bi-envelope-check-fill fs-3 me-3 text-primary"></i>
                <div>
                    <h6 class="fw-bold mb-1">Tiket Telah Dikirim!</h6>
                    <p class="mb-0 small text-dark opacity-75">
                        Salinan E-Ticket telah dikirim ke email <strong>{{ Auth::user()->email }}</strong>. <br>
                        Tunjukkan QR Code pada tiket saat memasuki lokasi acara.
                    </p>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-primary btn-action shadow-sm text-white">
                    <i class="bi bi-printer me-2"></i> Cetak Tiket Sekarang
                </a>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-action">
                    Ke Dashboard Saya
                </a>
            </div>

        </div>
    </div>
</div>
@endsection