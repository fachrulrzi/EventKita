<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $order->event->title }}</title>
    
    {{-- CDN Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        /* --- PRINT CONFIGURATION --- */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            body { 
                background: white; 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print { display: none !important; }
            .ticket-wrapper { 
                box-shadow: none !important; 
                border: 1px solid #ddd !important;
                page-break-inside: avoid;
                margin-bottom: 20px;
            }
        }

        /* --- GENERAL STYLES --- */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f4f6;
            padding: 20px;
            margin: 0;
            color: #1f2937;
        }

        .ticket-wrapper {
            max-width: 450px; /* Lebar ideal tiket mirip boarding pass mobile */
            margin: 0 auto 25px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* --- HEADER --- */
        .ticket-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 16px 24px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-brand {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .header-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* --- BODY CONTENT --- */
        .ticket-body {
            padding: 0;
        }

        /* EVENT BANNER */
        .event-banner {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }
        .event-banner-placeholder {
            width: 100%;
            height: 160px;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 3rem;
        }

        /* EVENT INFO */
        .event-info {
            padding: 24px;
        }

        .event-title-section {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #e5e7eb;
        }
        .event-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
            margin: 0 0 5px 0;
            line-height: 1.2;
        }
        .badge-valid {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #d1fae5;
            color: #059669;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* INFO LIST GRID */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* 2 Kolom */
            gap: 15px;
            margin-bottom: 20px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 0.7rem;
            color: #6b7280;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-value i {
            color: #6366f1;
            font-size: 0.9rem;
        }

        /* FULL WIDTH ITEM */
        .info-item.full-width {
            grid-column: span 2;
        }

        /* ATTENDEE BOX */
        .attendee-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        .attendee-title {
            font-size: 0.7rem;
            font-weight: 800;
            color: #92400e;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .attendee-detail {
            font-size: 0.85rem;
            color: #78350f;
            margin-bottom: 2px;
            display: flex;
        }
        .attendee-detail strong {
            width: 80px;
            font-weight: 600;
        }

        /* QR CODE SECTION */
        .ticket-code-box {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            border: 2px dashed #d1d5db;
        }
        .qr-wrapper {
            background: white;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .qr-wrapper img {
            width: 120px;
            height: 120px;
            display: block;
        }
        .ticket-code-text {
            font-family: 'Courier New', Courier, monospace;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #374151;
            margin-top: 5px;
        }
        .code-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            color: #9ca3af;
            font-weight: 600;
            margin-bottom: 5px;
        }

        /* FOOTER */
        .ticket-footer {
            background: #f3f4f6;
            padding: 15px 24px;
            text-align: center;
            font-size: 0.7rem;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .footer-logo {
            font-weight: 800;
            color: #4f46e5;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* BUTTONS (NO PRINT) */
        .action-buttons {
            max-width: 420px;
            margin: 0 auto 30px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="action-buttons no-print">
        <a href="{{ route('user.dashboard') }}" class="btn btn-light border fw-bold">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-printer-fill me-2"></i>Cetak Tiket
        </button>
    </div>

    {{-- LOOPING TIKET --}}
    @foreach ($order->tickets as $ticket)
    <div class="ticket-wrapper">
        
        <div class="ticket-header">
            <div class="header-brand">EventKita</div>
            <div class="header-badge">OFFICIAL TICKET</div>
        </div>

        <div class="ticket-body">
            
            @if($order->event->image_path)
                @php
                    $ticketImgUrl = str_starts_with($order->event->image_path, 'http')
                        ? $order->event->image_path
                        : \App\Helpers\StorageHelper::url($order->event->image_path);
                @endphp
                <img src="{{ $ticketImgUrl }}" alt="Event Banner" class="event-banner">
            @else
                <div class="event-banner-placeholder">
                    <i class="bi bi-card-image"></i>
                </div>
            @endif

            <div class="event-info">
                
                <div class="event-title-section">
                    <h1 class="event-title">{{ $order->event->title }}</h1>
                    @if($order->status === 'paid')
                        <div class="badge-valid">
                            <i class="bi bi-check-circle-fill"></i> Tiket Valid
                        </div>
                    @else
                        <div class="badge-valid" style="background: #fee2e2; color: #b91c1c;">
                            <i class="bi bi-exclamation-circle-fill"></i> Belum Lunas
                        </div>
                    @endif
                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Tanggal</span>
                        <div class="info-value">
                            <i class="bi bi-calendar3"></i> 
                            {{ $order->event->date->format('d M Y') }}
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu</span>
                        <div class="info-value">
                            <i class="bi bi-clock"></i> 
                            {{ $order->event->time }} WIB
                        </div>
                    </div>
                    <div class="info-item full-width">
                        <span class="info-label">Lokasi Event</span>
                        <div class="info-value">
                            <i class="bi bi-geo-alt-fill"></i> 
                            {{ $order->event->location }}
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kategori Tiket</span>
                        <div class="info-value" style="color: #4f46e5;">
                            {{ $ticket->ticketCategory->category_name }}
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Harga</span>
                        <div class="info-value">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="attendee-box">
                    <div class="attendee-title">
                        <i class="bi bi-person-badge-fill"></i> Data Pemegang Tiket
                    </div>
                    <div class="attendee-detail">
                        <strong>Nama</strong> : {{ $ticket->attendee_name }}
                    </div>
                    <div class="attendee-detail">
                        <strong>Email</strong> : {{ $ticket->attendee_email }}
                    </div>
                    <div class="attendee-detail">
                        <strong>No. HP</strong> : {{ $ticket->attendee_phone }}
                    </div>
                </div>

                <div class="ticket-code-box">
                    <div class="qr-wrapper">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $ticket->ticket_code }}" alt="QR Code">
                    </div>
                    <div class="code-label">KODE TIKET (SCAN DI PINTU MASUK)</div>
                    <div class="ticket-code-text">{{ $ticket->ticket_code }}</div>
                </div>

            </div>
        </div>

        <div class="ticket-footer">
            Harap simpan tiket ini. Jangan bagikan kode tiket kepada orang lain.
            <div class="footer-logo">EventKita Platform © {{ date('Y') }}</div>
            <div style="margin-top: 5px; font-size: 0.6rem; color: #9ca3af;">
                Order ID: {{ $order->order_number }} | Dicetak: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

    </div>
    @endforeach

    <script>
        // Auto print jika ada parameter url ?auto_print=1
        if (window.location.search.includes('auto_print=1')) {
            setTimeout(() => window.print(), 800); // Delay dikit biar gambar load
        }
    </script>
</body>
</html>