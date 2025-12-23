@extends('layouts.app')

@section('title', 'Checkout - ' . $event->title)

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. EVENT INFO CARD */
    .event-info-card {
        border: none;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        background: white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .event-thumb {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: var(--border-radius-lg);
    }

    /* 2. TICKET SELECTION CARD */
    .ticket-category-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s ease;
        background: white;
    }
    .ticket-category-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.1);
    }
    .ticket-price {
        font-size: 1.1rem;
        color: var(--primary-color);
        font-weight: 700;
    }
    
    /* Quantity Control */
    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #ced4da;
        background: white;
        transition: 0.2s;
    }
    .qty-btn:hover:not(:disabled) {
        background: #f8f9fa;
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    .qty-input {
        width: 50px;
        text-align: center;
        border: none;
        background: transparent;
        font-weight: 600;
        font-size: 1rem;
    }

    /* 3. ORDER SUMMARY & FORM */
    .summary-card {
        background: #f8fafc;
        border-radius: var(--border-radius-lg);
        border: 1px solid #e2e8f0;
    }
    .form-control-custom {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        padding: 12px;
        border-radius: 10px;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
    }

    /* 4. FLOATING BOTTOM BAR (Mobile) */
    @media (max-width: 991px) {
        .checkout-footer {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: white;
            padding: 15px;
            border-top: 1px solid #e2e8f0;
            z-index: 1000;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.05);
        }
        .main-content-pb { padding-bottom: 100px; }
    }
</style>

<div class="container py-5 main-content-pb">
    
    {{-- HEADER --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Checkout Tiket</h2>
        <p class="text-muted">Lengkapi data pemesanan untuk event <strong>{{ $event->title }}</strong></p>
    </div>

    <div class="row g-4">
        
        {{-- KOLOM KIRI: PILIH TIKET --}}
        <div class="col-lg-7">
            
            {{-- Event Info --}}
            <div class="card event-info-card mb-4">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            @if($event->image_path)
                                @php
                                    $checkoutImgUrl = str_starts_with($event->image_path, 'http') 
                                        ? $event->image_path 
                                        : \App\Helpers\StorageHelper::url($event->image_path);
                                @endphp
                                <img src="{{ $checkoutImgUrl }}" class="event-thumb shadow-sm" alt="{{ $event->title }}">
                            @else
                                <div class="event-thumb bg-light d-flex align-items-center justify-content-center text-muted">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-sm-8">
                            <h4 class="fw-bold mb-2">{{ $event->title }}</h4>
                            <div class="text-secondary mb-2 small">
                                <i class="bi bi-calendar3 me-2 text-primary"></i> {{ $event->date->format('d M Y') }}
                            </div>
                            <div class="text-secondary mb-2 small">
                                <i class="bi bi-clock me-2 text-primary"></i> {{ date('H:i', strtotime($event->time_start)) }} WIB
                            </div>
                            <div class="text-secondary small">
                                <i class="bi bi-geo-alt me-2 text-primary"></i> {{ $event->location }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ticket Selection --}}
            <h5 class="fw-bold mb-3">Pilih Kategori Tiket</h5>
            @if($event->ticketCategories->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($event->ticketCategories as $ticket)
                        <div class="ticket-category-card p-3 shadow-sm" data-id="{{ $ticket->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $ticket->category_name }}</h6>
                                    <div class="ticket-price mb-1">{{ $ticket->formatted_price }}</div>
                                    @if($ticket->description)
                                        <small class="text-muted d-block mb-1">{{ $ticket->description }}</small>
                                    @endif
                                    <small class="text-success fst-italic">
                                        <i class="bi bi-check-circle me-1"></i> Tersedia: {{ $ticket->remaining_stock ?? 'Unlimited' }}
                                    </small>
                                </div>
                                
                                {{-- Quantity Control --}}
                                <div class="d-flex align-items-center bg-light rounded-3 p-1">
                                    <button type="button" class="qty-btn" onclick="decreaseTicketQuantity({{ $ticket->id }})">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="qty-input" id="qty_{{ $ticket->id }}" value="0" min="0" max="{{ $ticket->stock ?? 10 }}" readonly>
                                    <button type="button" class="qty-btn" onclick="increaseTicketQuantity({{ $ticket->id }}, {{ $ticket->stock ?? 10 }})">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning rounded-3 border-0 shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Tiket belum tersedia untuk event ini.
                </div>
            @endif

        </div>

        {{-- KOLOM KANAN: FORM DATA & PEMBAYARAN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg sticky-top" style="top: 100px; border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="bi bi-person-badge-fill me-2 text-primary"></i>Data Pemesan</h5>
                    
                    <form id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-custom" id="attendee_name" name="attendee_name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control form-control-custom" id="attendee_email" name="attendee_email" value="{{ Auth::user()->email }}" required>
                            <div class="form-text small">Tiket akan dikirim ke email ini.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">No. WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control form-control-custom" id="attendee_phone" name="attendee_phone" placeholder="Contoh: 08123456789" required>
                        </div>

                        {{-- Ringkasan Pesanan --}}
                        <div class="summary-card p-3 mb-4">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Pesanan</h6>
                            <div id="selectedTickets" class="mb-3 text-muted small">
                                <em>Belum ada tiket yang dipilih.</em>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-white">
                                <span class="fw-bold text-dark">Total Bayar</span>
                                <span id="totalPrice" class="fs-4 fw-bold text-primary">Rp0</span>
                            </div>
                        </div>

                        {{-- Tombol Bayar --}}
                        <button type="button" id="pay-button" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" disabled>
                            <i class="bi bi-shield-lock-fill me-2"></i> Bayar Sekarang
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-lock-fill text-success"></i> Pembayaran Aman by Midtrans
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    // --- LOGIKA PEMROGRAMAN ASLI (TIDAK DIKURANGI) ---
    
    const eventSlug = '{{ $event->slug }}';
    const ticketCategories = @json($event->ticketCategories);
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
            summaryDiv.innerHTML = '<em>Belum ada tiket yang dipilih.</em>';
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
                        <span>${ticket.category_name} <span class="badge bg-light text-dark border ms-1">x${quantity}</span></span>
                        <span class="fw-semibold">Rp${subtotal.toLocaleString('id-ID')}</span>
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

    // CLICK HANDLER TOMBOL BAYAR
    document.getElementById('pay-button').onclick = function() {
        // Validasi tiket
        if (Object.keys(selectedTickets).length === 0) {
            alert('Pilih minimal 1 kategori tiket!');
            return;
        }
        
        // Validasi form data diri
        const attendeeName = document.getElementById('attendee_name').value.trim();
        const attendeeEmail = document.getElementById('attendee_email').value.trim();
        const attendeePhone = document.getElementById('attendee_phone').value.trim();
        
        if (!attendeeName || !attendeeEmail || !attendeePhone) {
            alert('Mohon lengkapi semua data pemegang tiket!');
            return;
        }
        
        // Disable & Loading State
        this.disabled = true;
        const originalText = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        
        // Create Order Request
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
                if (data.mock_mode) {
                    // MOCK MODE
                    alert('🎉 ' + data.message + '\n\nPembayaran akan otomatis berhasil (Mode Demo)!');
                    window.location.href = `/order/mock-success/${data.order_id}`;
                } else {
                    // MIDTRANS SNAP
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) { verifyPaymentAndRedirect(data.order_id); },
                        onPending: function(result) { verifyPaymentAndRedirect(data.order_id); },
                        onError: function(result) { 
                            alert('Pembayaran gagal!'); 
                            location.reload(); 
                        },
                        onClose: function() {
                            // Reset Button State
                            document.getElementById('pay-button').disabled = false;
                            document.getElementById('pay-button').innerHTML = originalText;
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