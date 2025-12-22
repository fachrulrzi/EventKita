<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $order->event->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            padding: 10px;
            margin: 0;
        }

        .ticket-wrapper {
            max-width: 420px;
            margin: 0 auto 15px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* Header */
        .ticket-header {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            padding: 14px 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-brand {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .header-badge {
            background: rgba(255,255,255,0.25);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        /* Ticket Body */
        .ticket-body {
            padding: 0;
        }

        /* Event Image */
        .event-banner {
            width: 100%;
            height: 140px;
            object-fit: cover;
            display: block;
        }

        /* Event Info */
        .event-info {
            padding: 16px 20px;
        }

        .event-title-section {
            text-align: center;
            margin-bottom: 14px;
        }

        .event-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #111827;
            margin: 0 0 8px 0;
        }

        .badge-valid {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #10b981;
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Info Items */
        .info-list {
            margin-bottom: 14px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: #7c3aed;
            font-size: 1rem;
        }

        .info-details {
            flex: 1;
        }

        .info-label {
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 0.9rem;
            color: #111827;
            font-weight: 600;
            margin-top: 2px;
        }

        /* Attendee Box */
        .attendee-box {
            background: #fef3c7;
            padding: 14px 16px;
            margin: 0 20px 14px;
            border-radius: 10px;
            border-left: 4px solid #f59e0b;
        }

        .attendee-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: #92400e;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .attendee-item {
            margin-bottom: 6px;
            font-size: 0.85rem;
            color: #78350f;
        }

        .attendee-item:last-child {
            margin-bottom: 0;
        }

        .attendee-label {
            font-weight: 600;
            display: inline-block;
            min-width: 90px;
        }

        /* Ticket Code Box */
        .ticket-code-box {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
            padding: 20px;
            margin: 0 20px 16px;
            border-radius: 12px;
            text-align: center;
            color: white;
        }

        .qr-icon-box {
            width: 90px;
            height: 90px;
            background: white;
            margin: 0 auto 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-icon-box i {
            font-size: 3rem;
            color: #7c3aed;
        }

        .code-title {
            font-size: 0.75rem;
            opacity: 0.95;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .ticket-code-text {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        /* Footer */
        .ticket-footer {
            padding: 14px 20px;
            text-align: center;
            font-size: 0.7rem;
            color: #6b7280;
            line-height: 1.5;
        }

        .footer-brand {
            font-weight: 700;
            color: #7c3aed;
            margin-top: 8px;
            font-size: 0.85rem;
        }

        .order-info {
            font-size: 0.65rem;
            color: #9ca3af;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            @page {
                size: A4 portrait;
                margin: 12mm;
            }

            .ticket-wrapper {
                margin: 0 auto;
                box-shadow: none;
                page-break-inside: avoid;
                break-inside: avoid;
                max-width: 100%;
            }

            .ticket-header,
            .ticket-body,
            .event-banner,
            .event-info,
            .attendee-box,
            .ticket-code-box,
            .ticket-footer {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="text-center mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
            <i class="bi bi-printer-fill me-2"></i>Cetak Semua Tiket
        </button>
        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-lg ms-2">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    @foreach ($order->tickets as $ticket)
    <div class="ticket-wrapper">
        <!-- Header -->
        <div class="ticket-header">
            <div class="header-brand">EventKita</div>
            <div class="header-badge">E-TICKET</div>
        </div>

        <!-- Ticket Body -->
        <div class="ticket-body">
            <!-- Event Banner -->
            @if($order->event->image_path)
                @php
                    $ticketImgUrl = $order->event->image_path
                        ? (str_starts_with($order->event->image_path, 'http')
                            ? $order->event->image_path
                            : \App\Helpers\StorageHelper::url($order->event->image_path))
                        : null;
                @endphp
                <img src="{{ $ticketImgUrl ?? 'https://via.placeholder.com/1200x500?text=' . urlencode($order->event->title) }}" 
                     alt="{{ $order->event->title }}" 
                     class="event-banner">
            @else
                <div class="event-banner" style="background: linear-gradient(135deg, #e5e7eb 0%, #d1d5db 100%); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-image" style="font-size: 3rem; color: #9ca3af;"></i>
                </div>
            @endif

            <!-- Event Info -->
            <div class="event-info">
                <!-- Event Title -->
                <div class="event-title-section">
                    <h1 class="event-title">{{ $order->event->title }}</h1>
                    @if($order->status === 'paid')
                        <span class="badge-valid">
                            <i class="bi bi-check-circle-fill"></i> VALID
                        </span>
                    @endif
                </div>

                <!-- Info List -->
                <div class="info-list">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Tanggal</div>
                            <div class="info-value">{{ $order->event->date->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Waktu</div>
                            <div class="info-value">{{ $order->event->time }} WIB</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Lokasi</div>
                            <div class="info-value">{{ $order->event->location }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                        <div class="info-details">
                            <div class="info-label">Harga Tiket</div>
                            <div class="info-value">Rp {{ number_format($ticket->price ?? 0, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendee Info -->
            <div class="attendee-box">
                <div class="attendee-title">
                    <i class="bi bi-person-fill"></i>
                    DATA PEMEGANG TIKET
                </div>
                <div class="attendee-item">
                    <span class="attendee-label">Nama Lengkap</span>
                    <span>: {{ $ticket->attendee_name }}</span>
                </div>
                <div class="attendee-item">
                    <span class="attendee-label">Email</span>
                    <span>: {{ $ticket->attendee_email }}</span>
                </div>
                <div class="attendee-item">
                    <span class="attendee-label">No. Telepon</span>
                    <span>: {{ $ticket->attendee_phone }}</span>
                </div>
            </div>

            <!-- Ticket Code -->
            <div class="ticket-code-box">
                <div class="qr-icon-box">
                    <i class="bi bi-qr-code"></i>
                </div>
                <div class="code-title">Kode Tiket</div>
                <div class="ticket-code-text">{{ $ticket->ticket_code }}</div>
            </div>

            <!-- Footer -->
            <div class="ticket-footer">
                Simpan tiket ini baik-baik! Tunjukkan kode QR atau kode tiket saat memasuki venue event dan nikmati event favoritmu!
                <div class="footer-brand">EventKita</div>
                <div class="order-info">
                    Order ID: {{ $order->order_code }} | Purchased: {{ $order->created_at->format('d/m/Y, H:i') }}
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        // Auto print jika dari halaman success
        if (window.location.search.includes('auto_print=1')) {
            setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>
