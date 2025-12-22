@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="fw-bold">Checkout Tiket</h2>
                <p class="text-muted">Lengkapi pembelian tiket Anda</p>
            </div>

            <!-- Event Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            @if($event->image_path)
                                <img src="{{ storage_url($event->image_path) }}" 
                                     class="img-fluid rounded" alt="{{ $event->title }}">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=Event" 
                                     class="img-fluid rounded" alt="{{ $event->title }}">
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-2">{{ $event->title }}</h4>
                            <p class="text-muted mb-2">
                                <i class="bi bi-calendar3 me-1"></i> {{ $event->date->format('d M Y') }}
                            </p>
                            <p class="text-muted mb-2">
                                <i class="bi bi-geo-alt me-1"></i> {{ $event->location }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="bi bi-clock me-1"></i> {{ date('H:i', strtotime($event->time_start)) }} - {{ date('H:i', strtotime($event->time_end)) }} WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Detail Pembelian</h5>
                    
                    <form id="checkoutForm">
                        @csrf
                        
                        @if($event->ticketCategories->count() > 0)
                            <div class="mb-4">
                                <label class="form-label fw-bold">Pilih Kategori Tiket</label>
                                @foreach($event->ticketCategories as $index => $ticket)
                                    <div class="card border mb-3 ticket-category-card" data-category-id="{{ $ticket->id }}" data-price="{{ $ticket->price }}" data-stock="{{ $ticket->stock }}">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-6">
                                                    <h6 class="fw-bold mb-1">{{ $ticket->category_name }}</h6>
                                                    @if($ticket->description)
                                                        <small class="text-muted d-block mb-2">{{ $ticket->description }}</small>
                                                    @endif
                                                    <p class="text-primary fw-bold mb-0">{{ $ticket->formatted_price }}</p>
                                                    @if($ticket->stock)
                                                        <small class="text-muted">
                                                            <i class="bi bi-ticket-perforated me-1"></i>
                                                            Tersedia: {{ $ticket->remaining_stock }} tiket
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="decreaseTicketQuantity({{ $ticket->id }})">
                                                            <i class="bi bi-dash"></i>
                                                        </button>
                                                        <input type="number" class="form-control form-control-sm text-center ticket-quantity" id="qty_{{ $ticket->id }}" style="max-width: 60px;" value="0" min="0" max="{{ $ticket->stock ?? 10 }}" readonly>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="increaseTicketQuantity({{ $ticket->id }}, {{ $ticket->stock ?? 10 }})">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Belum ada kategori tiket yang tersedia untuk event ini.
                            </div>
                        @endif

                        <hr>

                        <!-- Data Pemegang Tiket -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-badge me-2"></i>Data Pemegang Tiket</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="attendee_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="attendee_name" name="attendee_name" value="{{ Auth::user()->name }}" required>
                                    <small class="text-muted">Nama yang akan tertera di tiket</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="attendee_email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="attendee_email" name="attendee_email" value="{{ Auth::user()->email }}" required>
                                    <small class="text-muted">E-ticket akan dikirim ke email ini</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="attendee_phone" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="attendee_phone" name="attendee_phone" placeholder="08xxxxxxxxxx" required>
                                    <small class="text-muted">Untuk informasi event</small>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <small>Pastikan data yang Anda masukkan benar. Data ini akan tertera di tiket dan digunakan untuk informasi event.</small>
                            </div>
                        </div>

                        <hr>

                        <div id="orderSummary" class="mb-4">
                            <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                            <div id="selectedTickets" class="mb-3 text-muted small">
                                <i class="bi bi-info-circle me-1"></i> Pilih kategori tiket untuk melanjutkan
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fs-5 fw-bold">Total Bayar:</span>
                            <span id="totalPrice" class="fs-5 fw-bold text-primary">Rp0</span>
                        </div>

                        <button type="button" id="pay-button" class="btn btn-primary btn-lg w-100" disabled>
                            <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Security Info -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    Pembayaran aman menggunakan Midtrans
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
const eventSlug = '{{ $event->slug }}';
const ticketCategories = @json($event->ticketCategories);

// Object untuk menyimpan pesanan sementara
let selectedTickets = {};

function increaseTicketQuantity(categoryId, maxStock) {
    const input = document.getElementById(`qty_${categoryId}`);
    const currentQty = parseInt(input.value);
    
    if (currentQty < maxStock) {
        input.value = currentQty + 1;
        updateSelectedTickets(categoryId, currentQty + 1);
    }
}

function decreaseTicketQuantity(categoryId) {
    const input = document.getElementById(`qty_${categoryId}`);
    const currentQty = parseInt(input.value);
    
    if (currentQty > 0) {
        input.value = currentQty - 1;
        updateSelectedTickets(categoryId, currentQty - 1);
    }
}

function updateSelectedTickets(categoryId, quantity) {
    if (quantity > 0) {
        selectedTickets[categoryId] = quantity;
    } else {
        delete selectedTickets[categoryId];
    }
    
    updateOrderSummary();
}

function updateOrderSummary() {
    const summaryDiv = document.getElementById('selectedTickets');
    const totalPriceSpan = document.getElementById('totalPrice');
    const payButton = document.getElementById('pay-button');
    
    if (Object.keys(selectedTickets).length === 0) {
        summaryDiv.innerHTML = '<i class="bi bi-info-circle me-1"></i> Pilih kategori tiket untuk melanjutkan';
        totalPriceSpan.textContent = 'Rp0';
        payButton.disabled = true;
        return;
    }
    
    let summaryHTML = '';
    let grandTotal = 0;
    
    Object.keys(selectedTickets).forEach(categoryId => {
        const quantity = selectedTickets[categoryId];
        const ticket = ticketCategories.find(t => t.id == categoryId);
        
        if (ticket) {
            const subtotal = ticket.price * quantity;
            grandTotal += subtotal;
            
            summaryHTML += `
                <div class="d-flex justify-content-between mb-2">
                    <span>${ticket.category_name} (${quantity}x)</span>
                    <span class="fw-bold">Rp${subtotal.toLocaleString('id-ID')}</span>
                </div>
            `;
        }
    });
    
    summaryDiv.innerHTML = summaryHTML;
    totalPriceSpan.textContent = 'Rp' + grandTotal.toLocaleString('id-ID');
    payButton.disabled = false;
}

// Function to verify payment with server
function verifyPaymentAndRedirect(orderId) {
    fetch(`/order/finish/${orderId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success' && result.payment_status === 'paid') {
            window.location.href = `/order/success/${orderId}`;
        } else if (result.status === 'success' && result.payment_status === 'pending') {
            alert('Pembayaran sedang diproses. Silakan cek status di halaman Riwayat Pemesanan.');
            window.location.href = `/my-orders`;
        } else if (result.status === 'already_paid') {
            window.location.href = `/order/success/${orderId}`;
        } else {
            alert('Pembayaran belum berhasil. Silakan coba lagi.');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan. Silakan cek riwayat pemesanan Anda.');
        window.location.href = `/my-orders`;
    });
}

document.getElementById('pay-button').onclick = function() {
    // Validasi apakah ada tiket yang dipilih
    if (Object.keys(selectedTickets).length === 0) {
        alert('Pilih minimal 1 kategori tiket!');
        return;
    }
    
    // Validasi data pemegang tiket
    const attendeeName = document.getElementById('attendee_name').value.trim();
    const attendeeEmail = document.getElementById('attendee_email').value.trim();
    const attendeePhone = document.getElementById('attendee_phone').value.trim();
    
    if (!attendeeName || !attendeeEmail || !attendeePhone) {
        alert('Mohon lengkapi semua data pemegang tiket!');
        return;
    }
    
    // Disable button
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    
    // Create order dengan multiple ticket categories
    fetch(`/checkout/${eventSlug}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            tickets: selectedTickets,
            attendee_name: attendeeName,
            attendee_email: attendeeEmail,
            attendee_phone: attendeePhone
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.snap_token) {
            // Check if using Mock Payment or Real Midtrans
            if (data.mock_mode) {
                // MOCK PAYMENT MODE - Auto redirect to success
                alert('🎉 ' + data.message + '\n\nPembayaran akan otomatis berhasil!');
                window.location.href = `/order/mock-success/${data.order_id}`;
            } else {
                // REAL MIDTRANS - Open payment popup
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Verify payment status with server
                        verifyPaymentAndRedirect(data.order_id);
                    },
                    onPending: function(result) {
                        // Verify payment status with server
                        verifyPaymentAndRedirect(data.order_id);
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal!');
                        location.reload();
                    },
                    onClose: function() {
                        // Re-enable button
                        document.getElementById('pay-button').disabled = false;
                        document.getElementById('pay-button').innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                    }
                });
            }
        } else {
            alert(data.error || 'Terjadi kesalahan. Silakan coba lagi.');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memproses pembayaran');
        location.reload();
    });
};
</script>
@endpush
@endsection
