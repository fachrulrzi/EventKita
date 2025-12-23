@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Status Header -->
            <div class="text-center mb-4">
                @if($order->status === 'pending')
                    <div class="mb-4">
                        <i class="bi bi-clock-fill text-warning" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="fw-bold text-warning mb-3">Menunggu Pembayaran</h2>
                    <p class="text-muted">Segera selesaikan pembayaran untuk mendapatkan tiket Anda.</p>
                @elseif($order->status === 'paid')
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="fw-bold text-success mb-3">Pembayaran Berhasil</h2>
                    <p class="text-muted">Tiket Anda sudah siap!</p>
                @elseif($order->status === 'cancelled')
                    <div class="mb-4">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="fw-bold text-danger mb-3">Pesanan Dibatalkan</h2>
                    <p class="text-muted">Pesanan ini telah dibatalkan.</p>
                @elseif($order->status === 'expired')
                    <div class="mb-4">
                        <i class="bi bi-exclamation-circle-fill text-secondary" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="fw-bold text-secondary mb-3">Pesanan Kedaluwarsa</h2>
                    <p class="text-muted">Pesanan ini telah kedaluwarsa. Silakan buat pesanan baru.</p>
                @endif
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
                            @if($order->status === 'pending')
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="bi bi-clock me-1"></i>Menunggu Pembayaran
                                </span>
                            @elseif($order->status === 'paid')
                                <span class="badge bg-success fs-6">
                                    <i class="bi bi-check-circle me-1"></i>Dibayar
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge bg-danger fs-6">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary fs-6">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Tanggal Pemesanan</p>
                            <p class="mb-0">{{ $order->created_at->format('d M Y H:i') }} WIB</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($order->status === 'paid')
                                <p class="text-muted mb-1">Metode Pembayaran</p>
                                <p class="mb-0">{{ $order->payment_type ?? 'N/A' }}</p>
                            @else
                                <p class="text-muted mb-1">Batas Pembayaran</p>
                                <p class="mb-0 text-danger fw-bold">{{ $order->created_at->addHours(24)->format('d M Y H:i') }} WIB</p>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Event Information -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-calendar-event me-2"></i>Informasi Event</h6>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @php
                                    $eventImgUrl = $order->event->image_path
                                        ? (str_starts_with($order->event->image_path, 'http')
                                            ? $order->event->image_path
                                            : \App\Helpers\StorageHelper::url($order->event->image_path))
                                        : null;
                                @endphp
                                <img src="{{ $eventImgUrl ?? 'https://via.placeholder.com/150x100?text=Event' }}" 
                                     class="img-fluid rounded" alt="{{ $order->event->title }}">
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold mb-2">{{ $order->event->title }}</h5>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-calendar3 me-1"></i> 
                                    {{ $order->event->date->format('d M Y') }}
                                </p>
                                @if($order->event->time_start)
                                <p class="text-muted mb-1">
                                    <i class="bi bi-clock me-1"></i> 
                                    {{ date('H:i', strtotime($order->event->time_start)) }}
                                    @if($order->event->time_end)
                                        - {{ date('H:i', strtotime($order->event->time_end)) }}
                                    @endif
                                    WIB
                                </p>
                                @endif
                                <p class="text-muted mb-0">
                                    <i class="bi bi-geo-alt me-1"></i> 
                                    {{ $order->event->location ?? 'Lokasi akan diinformasikan' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Ticket Details -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3"><i class="bi bi-ticket-perforated me-2"></i>Detail Tiket</h6>
                        @php
                            $ticketsByCategory = $order->tickets->groupBy('event_ticket_category_id');
                        @endphp
                        @foreach($ticketsByCategory as $categoryId => $tickets)
                            @php
                                $category = $tickets->first()->ticketCategory;
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-2 p-3 bg-light rounded">
                                <div>
                                    <p class="fw-bold mb-1">{{ $category->category_name ?? 'Tiket' }}</p>
                                    <small class="text-muted">{{ $tickets->count() }}x @ Rp {{ number_format($category->price ?? 0, 0, ',', '.') }}</small>
                                </div>
                                <div class="text-end">
                                    <p class="fw-bold mb-0">Rp {{ number_format(($category->price ?? 0) * $tickets->count(), 0, ',', '.') }}</p>
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

            @if($order->status === 'pending')
                <!-- Payment Information -->
                <div class="alert alert-warning mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Segera Selesaikan Pembayaran</h6>
                    <ul class="mb-0">
                        <li>Batas waktu pembayaran: <strong>{{ $order->created_at->addHours(24)->format('d M Y H:i') }} WIB</strong></li>
                        <li>Pesanan akan otomatis dibatalkan jika pembayaran tidak diselesaikan</li>
                        <li>Tiket akan dikirim ke email setelah pembayaran berhasil</li>
                    </ul>
                </div>

                <!-- Continue Payment Button -->
                <div class="d-grid gap-2 mb-4">
                    <button id="continuePaymentBtn" class="btn btn-primary btn-lg py-3">
                        <i class="bi bi-credit-card me-2"></i>Lanjutkan Pembayaran
                    </button>
                </div>
            @endif

            @if($order->status === 'paid')
                <!-- Success Info -->
                <div class="alert alert-success mb-4">
                    <h6 class="fw-bold mb-2"><i class="bi bi-check-circle me-2"></i>Pembayaran Diterima</h6>
                    <ul class="mb-0">
                        <li>E-ticket telah dikirim ke email Anda ({{ Auth::user()->email }})</li>
                        <li>Simpan e-ticket untuk ditunjukkan saat memasuki venue event</li>
                        <li>Kode tiket bersifat unik dan hanya dapat digunakan sekali</li>
                    </ul>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="d-flex gap-2 justify-content-center flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-outline-primary">
                    <i class="bi bi-house-door me-2"></i>Kembali ke Home
                </a>
                <a href="{{ url('/events/' . $order->event->slug) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-eye me-2"></i>Lihat Event
                </a>
                @if($order->status === 'paid')
                    <a href="{{ route('order.print', $order->id) }}" class="btn btn-success" target="_blank">
                        <i class="bi bi-printer me-2"></i>Cetak Tiket
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

@if($order->status === 'pending')
<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="fw-bold">Memproses Pembayaran...</h5>
                <p class="text-muted mb-0">Mohon tunggu sebentar</p>
            </div>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
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
            // Development mode - redirect to mock success
            if (confirm('Development Mode: Simulasi pembayaran berhasil?')) {
                window.location.href = '{{ route("order.mock.success", $order->id) }}';
            }
        } else {
            // Real Midtrans payment
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    // Call finishPayment to update order status
                    fetch('{{ route("order.finish", $order->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(finishData => {
                        window.location.href = '{{ route("order.success", $order->id) }}';
                    })
                    .catch(error => {
                        // Even if finish fails, redirect to success page
                        window.location.href = '{{ route("order.success", $order->id) }}';
                    });
                },
                onPending: function(result) {
                    alert('Pembayaran pending. Silakan selesaikan pembayaran.');
                    window.location.reload();
                },
                onError: function(result) {
                    alert('Terjadi kesalahan saat pembayaran.');
                },
                onClose: function() {
                    // User closed the popup - check payment status
                    fetch('{{ route("order.finish", $order->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(finishData => {
                        if (finishData.payment_status === 'paid') {
                            window.location.href = '{{ route("order.success", $order->id) }}';
                        } else {
                            window.location.reload();
                        }
                    });
                }
            });
        }
    })
    .catch(error => {
        loadingModal.hide();
        alert('Terjadi kesalahan: ' + error.message);
    });
});
</script>
@endif
@endsection
