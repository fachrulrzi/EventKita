@extends('layouts.app')

@section('title', 'Riwayat Pesanan - EventKita')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Riwayat Pesanan</h2>
                    <p class="text-muted mb-0">Daftar semua pesanan tiket Anda</p>
                </div>
                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>

            @if($orders->count() > 0)
                <!-- Statistics Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 bg-success bg-opacity-10">
                            <div class="card-body text-center">
                                <h3 class="fw-bold text-success mb-0">{{ $orders->where('status', 'paid')->count() }}</h3>
                                <small class="text-muted">Pesanan Sukses</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-warning bg-opacity-10">
                            <div class="card-body text-center">
                                <h3 class="fw-bold text-warning mb-0">{{ $orders->where('status', 'pending')->count() }}</h3>
                                <small class="text-muted">Menunggu Bayar</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-danger bg-opacity-10">
                            <div class="card-body text-center">
                                <h3 class="fw-bold text-danger mb-0">{{ $orders->where('status', 'cancelled')->count() + $orders->where('status', 'expired')->count() }}</h3>
                                <small class="text-muted">Batal/Expired</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-primary bg-opacity-10">
                            <div class="card-body text-center">
                                <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($orders->where('status', 'paid')->sum('total_price'), 0, ',', '.') }}</h3>
                                <small class="text-muted">Total Belanja</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-receipt me-2 text-primary"></i>Semua Pesanan ({{ $orders->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">No. Pesanan</th>
                                        <th class="py-3">Event</th>
                                        <th class="py-3">Jumlah Tiket</th>
                                        <th class="py-3">Total</th>
                                        <th class="py-3">Status</th>
                                        <th class="py-3">Tanggal</th>
                                        <th class="pe-4 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $orderImgUrl = $order->event->image_path
                                                            ? (str_starts_with($order->event->image_path, 'http')
                                                                ? $order->event->image_path
                                                                : \App\Helpers\StorageHelper::url($order->event->image_path))
                                                            : null;
                                                    @endphp
                                                    <img src="{{ $orderImgUrl ?? 'https://via.placeholder.com/50x50?text=E' }}" 
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="{{ $order->event->title }}">
                                                    <div>
                                                        <h6 class="mb-0 fw-semibold">{{ Str::limit($order->event->title, 30) }}</h6>
                                                        <small class="text-muted">{{ $order->event->date->format('d M Y') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $order->tickets->count() }} tiket</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                            </td>
                                            <td>
                                                @if($order->status === 'paid')
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Dibayar
                                                    </span>
                                                @elseif($order->status === 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-clock me-1"></i>Menunggu
                                                    </span>
                                                @elseif($order->status === 'cancelled')
                                                    <span class="badge bg-danger">
                                                        <i class="bi bi-x-circle me-1"></i>Dibatalkan
                                                    </span>
                                                @elseif($order->status === 'expired')
                                                    <span class="badge bg-secondary">
                                                        <i class="bi bi-clock-history me-1"></i>Kedaluwarsa
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                                <br>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }} WIB</small>
                                            </td>
                                            <td class="pe-4 text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('order.show', $order->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    @if($order->status === 'paid')
                                                        <a href="{{ route('order.print', $order->id) }}" 
                                                           class="btn btn-sm btn-outline-success" 
                                                           target="_blank"
                                                           title="Cetak Tiket">
                                                            <i class="bi bi-printer"></i>
                                                        </a>
                                                    @elseif($order->status === 'pending')
                                                        <a href="{{ route('order.show', $order->id) }}" 
                                                           class="btn btn-sm btn-warning" 
                                                           title="Bayar Sekarang">
                                                            <i class="bi bi-credit-card"></i>
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
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-bag-x text-muted opacity-25" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-muted">Belum ada pesanan</h4>
                    <p class="text-muted mb-4">Kamu belum pernah memesan tiket event apapun.</p>
                    <a href="{{ url('/kategori') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-calendar-event me-2"></i>Jelajahi Event
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
