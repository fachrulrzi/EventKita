@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. STATUS HEADER */
    .status-header {
        text-align: center;
        padding: 40px 20px;
        margin-bottom: 30px;
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .status-icon-large {
        font-size: 5rem;
        margin-bottom: 1rem;
        display: block;
        line-height: 1;
    }

    /* 2. ORDER DETAILS CARD */
    .order-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
        font-size: 1.05rem;
    }

    /* 3. EVENT & TICKET SECTION */
    .event-thumb-small {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
    }
    .ticket-item {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 10px;
        border: 1px solid #e2e8f0;
    }

    /* 4. TOTAL & ACTION */
    .total-section {
        background-color: #f1f5f9;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
    }
    
    /* Buttons */
    .btn-action {
        padding: 12px 24px;
        font-weight: 600;
        border-radius: 50px;
        transition: all 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- STATUS HEADER --}}
            <div class="status-header">
                @if($order->status === 'pending')
                    <i class="bi bi-hourglass-split status-icon-large text-warning"></i>
                    <h2 class="fw-bold text-dark mb-2">Menunggu Pembayaran</h2>
                    <p class="text-muted mb-0">Selesaikan pembayaran sebelum batas waktu berakhir.</p>
                @elseif($order->status === 'paid')
                    <i class="bi bi-check-circle-fill status-icon-large text-success"></i>
                    <h2 class="fw-bold text-dark mb-2">Pembayaran Berhasil!</h2>
                    <p class="text-muted mb-0">Terima kasih, tiket Anda sudah siap digunakan.</p>
                @elseif($order->status === 'cancelled')
                    <i class="bi bi-x-circle-fill status-icon-large text-danger"></i>
                    <h2 class="fw-bold text-dark mb-2">Pesanan Dibatalkan</h2>
                    <p class="text-muted mb-0">Pesanan ini telah dibatalkan oleh sistem atau Anda.</p>
                @elseif($order->status === 'expired')
                    <i class="bi bi-clock-history status-icon-large text-secondary"></i>
                    <h2 class="fw-bold text-dark mb-2">Pesanan Kedaluwarsa</h2>
                    <p class="text-muted mb-0">Waktu pembayaran telah habis. Silakan pesan ulang.</p>
                @endif
            </div>

            {{-- ORDER DETAILS CARD --}}
            <div class="card order-card mb-4">
                <div class="order-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-receipt me-2 text-primary"></i>Detail Pesanan</h5>
                    <span class="badge bg-light text-dark border">#{{ $order->order_number }}</span>
                </div>
                
                <div class="card-body p-4">
                    {{-- Info Grid --}}
                    <div class="row g-4 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="info-label">Tanggal Order</div>
                            <div class="info-value">{{ $order->created_at->format('d M Y') }}</div>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }} WIB</small>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Status</div>
                            @if($order->status === 'paid')
                                <span class="badge bg-success">PAID</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning text-dark">PENDING</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger">CANCELLED</span>
                            @else
                                <span class="badge bg-secondary">EXPIRED</span>
                            @endif
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Metode Bayar</div>
                            <div class="info-value">{{ $order->payment_type ?? '-' }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Batas Waktu</div>
                            @if($order->status === 'paid')
                                <div class="text-success fw-bold"><i class="bi bi-check-lg"></i> Selesai</div>
                            @else
                                <div class="text-danger fw-bold">{{ $order->created_at->addHours(24)->format('d M H:i') }}</div>
                            @endif
                        </div>
                    </div>

                    <hr class="border-light-subtle my-4">

                    {{-- Event Info --}}
                    <h6 class="fw-bold mb-3">Event yang Dipesan</h6>
                    <div class="d-flex align-items-start gap-3 mb-4">
                        @php
                            $imgUrl = $order->event->image_path 
                                ? (str_starts_with($order->event->image_path, 'http') ? $order->event->image_path : \App\Helpers\StorageHelper::url($order->event->image_path)) 
                                : 'https://via.placeholder.com/150';
                        @endphp
                        <img src="{{ $imgUrl }}" class="event-thumb-small shadow-sm" alt="Event">
                        <div>
                            <h5 class="fw-bold mb-1">{{ $order->event->title }}</h5>
                            <p class="text-muted small mb-1"><i class="bi bi-calendar3 me-1"></i> {{ $order->event->date->format('d F Y') }}</p>
                            <p class="text-muted small mb-0"><i class="bi bi-geo-alt me-1"></i> {{ $order->event->location ?? 'Lokasi Online' }}</p>
                        </div>
                    </div>

                    {{-- Ticket List --}}
                    <h6 class="fw-bold mb-3">Rincian Tiket</h6>
                    <div class="ticket-list">
                        @php $ticketsByCategory = $order->tickets->groupBy('event_ticket_category_id'); @endphp
                        
                        @foreach($ticketsByCategory as $categoryId => $tickets)
                            @php $category = $tickets->first()->ticketCategory; @endphp
                            <div class="ticket-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold">{{ $category->category_name ?? 'Tiket Masuk' }}</div>
                                    <small class="text-muted">{{ $tickets->count() }}x @ Rp {{ number_format($category->price ?? 0, 0, ',', '.') }}</small>
                                </div>
                                <div class="fw-bold">
                                    Rp {{ number_format(($category->price ?? 0) * $tickets->count(), 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total Section --}}
                    <div class="total-section d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-secondary">Total Pembayaran</span>
                        <span class="fw-bold text-primary fs-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>

                    {{-- Actions (Pending Only) --}}
                    @if($order->status === 'pending')
                        <div class="mt-4">
                            <div class="alert alert-warning border-0 d-flex align-items-center mb-3">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Perhatian:</strong> Mohon selesaikan pembayaran sebelum batas waktu berakhir agar pesanan tidak hangus.
                                </div>
                            </div>
                            <button id="continuePaymentBtn" class="btn btn-primary btn-action w-100 shadow-sm">
                                <i class="bi bi-credit-card-fill me-2"></i> Lanjutkan Pembayaran
                            </button>
                        </div>
                    @endif

                    {{-- Actions (Paid Only) --}}
                    @if($order->status === 'paid')
                        <div class="mt-4">
                            <div class="alert alert-success border-0 d-flex align-items-center mb-3">
                                <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Info:</strong> E-Ticket telah dikirim ke email <b>{{ Auth::user()->email }}</b>.
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- BOTTOM NAVIGATION --}}
            <div class="d-flex justify-content-center gap-3 pb-5">
                <a href="{{ url('/') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="bi bi-house-door me-2"></i>Home
                </a>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="bi bi-clock-history me-2"></i>Riwayat
                </a>
                @if($order->status === 'paid')
                    <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-success btn-action shadow-sm text-white">
                        <i class="bi bi-printer-fill me-2"></i>Cetak E-Ticket
                    </a>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- MODAL & SCRIPTS (Pending Only) --}}
@if($order->status === 'pending')
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                <h5 class="fw-bold">Memproses...</h5>
                <p class="text-muted mb-0">Mohon tunggu sebentar, sedang menghubungkan ke gateway pembayaran.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
    document.getElementById('continuePaymentBtn').addEventListener('click', function() {
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        
        fetch('{{ route("order.continue", $order->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingModal.hide();
            
            if (data.error) {
                alert(data.error);
                return;
            }
            
            if (data.mock_mode) {
                // Development Mode Mock
                if (confirm('DEV MODE: Simulasi pembayaran berhasil?')) {
                    window.location.href = '{{ route("order.mock.success", $order->id) }}';
                }
            } else {
                // Real Midtrans Snap
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        fetch('{{ route("order.finish", $order->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(() => { window.location.href = '{{ route("order.success", $order->id) }}'; });
                    },
                    onPending: function(result) {
                        alert('Pembayaran tertunda. Silakan selesaikan pembayaran.');
                        window.location.reload();
                    },
                    onError: function(result) {
                        alert('Terjadi kesalahan saat pembayaran.');
                    },
                    onClose: function() {
                        // Check status when closed
                        fetch('{{ route("order.finish", $order->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        }).then(r => r.json()).then(d => {
                            if (d.payment_status === 'paid') window.location.href = '{{ route("order.success", $order->id) }}';
                            else window.location.reload();
                        });
                    }
                });
            }
        })
        .catch(error => {
            loadingModal.hide();
            alert('Terjadi kesalahan koneksi: ' + error.message);
        });
    });
</script>
@endif

@endsection