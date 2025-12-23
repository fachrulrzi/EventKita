@extends('layouts.app')

@section('title', 'Riwayat Pesanan - EventKita')

@section('content')
{{-- INTERNAL CSS --}}
<style>
    :root {
        --primary-color: #0d6efd;
        --border-radius-lg: 1rem;
    }

    /* 1. STATS CARDS */
    .stats-card {
        border: none;
        border-radius: var(--border-radius-lg);
        padding: 20px;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 12px;
    }

    /* 2. ORDER TABLE */
    .order-card {
        border: none;
        border-radius: var(--border-radius-lg);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    .table thead th {
        background-color: #f8fafc;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        padding: 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    .table tbody td {
        padding: 20px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .order-number {
        font-family: 'Courier New', Courier, monospace;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
    }

    /* 3. BADGES */
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .bg-soft-success { background-color: #d1fae5; color: #065f46; }
    .bg-soft-warning { background-color: #fef3c7; color: #92400e; }
    .bg-soft-danger { background-color: #fee2e2; color: #991b1b; }
    .bg-soft-primary { background-color: #dbeafe; color: #1e40af; }

    /* Action Buttons */
    .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
    }
    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container py-5">
    
    {{-- HEADER --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Pesanan</h2>
            <p class="text-muted mb-0">Kelola dan pantau status pembelian tiket event Anda.</p>
        </div>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-grid-fill me-2"></i>Dashboard
        </a>
    </div>

    @if($orders->count() > 0)
        {{-- STATS OVERVIEW --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card bg-soft-success">
                    <div class="stats-icon bg-white text-success shadow-sm">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $orders->where('status', 'paid')->count() }}</h3>
                    <small class="text-muted fw-bold text-uppercase">Pesanan Sukses</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card bg-soft-warning">
                    <div class="stats-icon bg-white text-warning shadow-sm">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $orders->where('status', 'pending')->count() }}</h3>
                    <small class="text-muted fw-bold text-uppercase">Menunggu Bayar</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card bg-soft-danger">
                    <div class="stats-icon bg-white text-danger shadow-sm">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $orders->whereIn('status', ['cancelled', 'expired'])->count() }}</h3>
                    <small class="text-muted fw-bold text-uppercase">Batal / Expired</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card bg-soft-primary">
                    <div class="stats-icon bg-white text-primary shadow-sm">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($orders->where('status', 'paid')->sum('total_price'), 0, ',', '.') }}</h4>
                    <small class="text-muted fw-bold text-uppercase">Total Transaksi</small>
                </div>
            </div>
        </div>

        {{-- ORDERS TABLE --}}
        <div class="card order-card">
            <div class="card-header bg-white py-3 px-4 border-bottom">
                <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Daftar Pesanan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">ID Pesanan</th>
                                <th>Event</th>
                                <th>Tiket</th>
                                <th>Total Harga</th>
                                <th class="text-center">Status</th>
                                <th>Tanggal</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <span class="order-number">#{{ $order->order_number }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            @php
                                                $imgUrl = $order->event->image_path 
                                                    ? (str_starts_with($order->event->image_path, 'http') 
                                                        ? $order->event->image_path 
                                                        : \App\Helpers\StorageHelper::url($order->event->image_path)) 
                                                    : 'https://via.placeholder.com/50';
                                            @endphp
                                            <img src="{{ $imgUrl }}" class="rounded" width="48" height="48" style="object-fit: cover;" alt="Event">
                                            <div>
                                                <div class="fw-bold text-dark">{{ Str::limit($order->event->title, 30) }}</div>
                                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $order->event->cityRelation->name ?? 'Online' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $order->tickets->count() }} Item</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'paid')
                                            <span class="status-badge bg-soft-success">Berhasil</span>
                                        @elseif($order->status === 'pending')
                                            <span class="status-badge bg-soft-warning">Pending</span>
                                        @elseif($order->status === 'cancelled')
                                            <span class="status-badge bg-soft-danger">Batal</span>
                                        @else
                                            <span class="status-badge bg-light text-muted border">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <div>{{ $order->created_at->format('d M Y') }}</div>
                                            <div style="font-size: 0.75rem;">{{ $order->created_at->format('H:i') }} WIB</div>
                                        </div>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-action btn-light border text-primary" title="Detail Pesanan">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            
                                            @if($order->status === 'paid')
                                                <a href="{{ route('order.print', $order->id) }}" target="_blank" class="btn btn-action btn-light border text-success" title="Cetak E-Ticket">
                                                    <i class="bi bi-printer-fill"></i>
                                                </a>
                                            @elseif($order->status === 'pending')
                                                <a href="{{ route('order.show', $order->id) }}" class="btn btn-action btn-warning text-dark shadow-sm" title="Bayar Sekarang">
                                                    <i class="bi bi-credit-card-fill"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        {{-- EMPTY STATE --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-cart-x text-muted opacity-25" style="font-size: 6rem;"></i>
            </div>
            <h3 class="fw-bold text-dark">Belum Ada Pesanan</h3>
            <p class="text-muted mb-4">Sepertinya kamu belum membeli tiket event apapun. Yuk, cari event seru!</p>
            <a href="{{ url('/kategori') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                Jelajahi Event Sekarang
            </a>
        </div>
    @endif

</div>
@endsection