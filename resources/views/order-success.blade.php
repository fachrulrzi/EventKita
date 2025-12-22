@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="text-center mb-4">
                <div class="mb-4">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="fw-bold text-success mb-3">Pembayaran Berhasil!</h2>
                <p class="text-muted">Terima kasih atas pembelian Anda. Tiket Anda telah dikirim ke email.</p>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Detail Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Nomor Pesanan</p>
                            <h6 class="fw-bold">{{ $order->order_number }}</h6>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-muted mb-1">Status</p>
                            <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Tanggal Pembelian</p>
                            <p class="mb-0">{{ $order->created_at->format('d M Y H:i') }} WIB</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-muted mb-1">Metode Pembayaran</p>
                            <p class="mb-0">{{ $order->payment_type ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr>

                    <!-- Event Information -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-calendar-event me-2"></i>Informasi Event</h6>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($order->event->image_path)
                                    <img src="{{ Storage::url($order->event->image_path) }}" 
                                         class="img-fluid rounded" alt="{{ $order->event->title }}">
                                @else
                                    <img src="https://via.placeholder.com/150x100?text=Event" 
                                         class="img-fluid rounded" alt="{{ $order->event->title }}">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold mb-2">{{ $order->event->title }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    {{ $order->event->date->format('d M Y') }}
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-clock me-1"></i> 
                                    {{ date('H:i', strtotime($order->event->time_start)) }} - 
                                    {{ date('H:i', strtotime($order->event->time_end)) }} WIB
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt me-1"></i> 
                                    {{ $order->event->location }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Ticket Details -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-ticket-perforated me-2"></i>Detail Tiket</h6>
                        @foreach($order->tickets as $ticket)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-light rounded">
                            <div>
                                <p class="fw-bold mb-1">{{ $ticket->ticketCategory->name }}</p>
                                <small class="text-muted">Kode: {{ $ticket->ticket_code }}</small>
                            </div>
                            <div class="text-end">
                                <p class="fw-bold mb-0">{{ number_format($ticket->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Total -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Total Pembayaran</h5>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- Important Information -->
            <div class="alert alert-info mb-4">
                <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Informasi Penting</h6>
                <ul class="mb-0">
                    <li>E-ticket telah dikirim ke email Anda ({{ Auth::user()->email }})</li>
                    <li>Simpan e-ticket Anda untuk ditunjukkan saat memasuki venue event</li>
                    <li>Kode tiket bersifat unik dan hanya dapat digunakan sekali</li>
                    <li>Jika ada kendala, hubungi customer service kami</li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-center">
                <a href="{{ route('/') }}" class="btn btn-outline-primary">
                    <i class="bi bi-house-door me-2"></i>Kembali ke Home
                </a>
                <a href="{{ url('/events/' . $order->event->slug) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-eye me-2"></i>Lihat Event
                </a>
                <a href="{{ route('order.print', $order->id) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-printer me-2"></i>Cetak Tiket
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        .navbar, .footer, .btn, .alert {
            display: none !important;
        }
        .container {
            max-width: 100% !important;
        }
    }
</style>
@endsection
