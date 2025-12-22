<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\EventTicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration - only if keys are valid
        $serverKey = config('midtrans.server_key');
        if ($serverKey && !str_contains($serverKey, 'YOUR_') && !empty($serverKey)) {
            Config::$serverKey = $serverKey;
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = config('midtrans.is_sanitized', true);
            Config::$is3ds = config('midtrans.is_3ds', true);
        }
    }

    /**
     * Show checkout page
     */
    public function checkout($slug)
    {
        $event = Event::with('ticketCategories')->where('slug', $slug)->firstOrFail();
        
        // Check if event has ticket categories
        if ($event->ticketCategories->count() == 0) {
            return redirect()->route('event.detail', $slug)
                ->with('error', 'Event ini belum memiliki kategori tiket.');
        }

        return view('checkout', compact('event'));
    }

    /**
     * Create order and get Midtrans Snap token
     */
    public function createOrder(Request $request, $slug)
    {
        $request->validate([
            'tickets' => 'required|array|min:1',
            'tickets.*' => 'required|integer|min:1',
            'attendee_name' => 'required|string|max:255',
            'attendee_email' => 'required|email|max:255',
            'attendee_phone' => 'required|string|max:20',
        ]);

        $event = Event::with('ticketCategories')->where('slug', $slug)->firstOrFail();
        $ticketsData = $request->tickets; // { category_id: quantity }

        try {
            DB::beginTransaction();

            // Calculate total price and create item details
            $totalPrice = 0;
            $totalQuantity = 0;
            $itemDetails = [];

            foreach ($ticketsData as $categoryId => $quantity) {
                $category = $event->ticketCategories->find($categoryId);
                
                if (!$category) {
                    return response()->json(['error' => 'Kategori tiket tidak valid'], 400);
                }

                // Check stock availability
                if ($category->stock && ($category->sold + $quantity) > $category->stock) {
                    return response()->json([
                        'error' => "Stok tiket {$category->category_name} tidak mencukupi. Tersedia: {$category->remaining_stock}"
                    ], 400);
                }

                $subtotal = $category->price * $quantity;
                $totalPrice += $subtotal;
                $totalQuantity += $quantity;

                // Add to item details for Midtrans
                $itemDetails[] = [
                    'id' => "ticket_{$categoryId}",
                    'price' => (int) $category->price,
                    'quantity' => $quantity,
                    'name' => "{$event->title} - {$category->category_name}"
                ];
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'quantity' => $totalQuantity,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Create tickets for each category
            foreach ($ticketsData as $categoryId => $quantity) {
                $category = $event->ticketCategories->find($categoryId);
                
                for ($i = 0; $i < $quantity; $i++) {
                    Ticket::create([
                        'order_id' => $order->id,
                        'event_ticket_category_id' => $categoryId,
                        'ticket_code' => Ticket::generateTicketCode(),
                        'attendee_name' => $request->attendee_name,
                        'attendee_email' => $request->attendee_email,
                        'attendee_phone' => $request->attendee_phone,
                        'price' => $category->price,
                        'status' => 'active',
                    ]);
                }
            }

            // Check if using Mock Payment or Real Midtrans
            $useMockPayment = !config('midtrans.server_key') || str_contains(config('midtrans.server_key'), 'YOUR_');

            if ($useMockPayment) {
                // MOCK PAYMENT for development
                $snapToken = 'MOCK-' . uniqid();
                
                DB::commit();

                return response()->json([
                    'snap_token' => $snapToken,
                    'order_id' => $order->id,
                    'mock_mode' => true,
                    'message' => 'Development Mode: Using Mock Payment'
                ]);
            } else {
                // REAL MIDTRANS
                $params = [
                    'transaction_details' => [
                        'order_id' => $order->order_number,
                        'gross_amount' => (int) $totalPrice,
                    ],
                    'customer_details' => [
                        'first_name' => Auth::user()->name,
                        'email' => Auth::user()->email,
                    ],
                    'item_details' => $itemDetails,
                ];

                // Get Snap payment token
                $snapToken = Snap::getSnapToken($params);
                
                DB::commit();

                return response()->json([
                    'snap_token' => $snapToken,
                    'order_id' => $order->id,
                    'mock_mode' => false
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment finish (called from frontend after Midtrans popup closes)
     */
    public function finishPayment($orderId)
    {
        try {
            $order = Order::with(['event', 'tickets'])->findOrFail($orderId);
            
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            // Check if already paid
            if ($order->status === 'paid') {
                return response()->json(['status' => 'already_paid']);
            }

            // Get transaction status from Midtrans
            $serverKey = config('midtrans.server_key');
            if ($serverKey && !str_contains($serverKey, 'YOUR_')) {
                Config::$serverKey = $serverKey;
                Config::$isProduction = config('midtrans.is_production', false);
                
                $status = \Midtrans\Transaction::status($order->order_number);
                
                // Check if payment is successful
                if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                    // Update order status
                    $order->update([
                        'status' => 'paid',
                        'payment_type' => $status->payment_type ?? 'unknown',
                        'midtrans_transaction_id' => $status->transaction_id ?? null,
                        'paid_at' => now(),
                        'midtrans_response' => json_encode($status),
                    ]);
                    
                    // Update sold count for each ticket category
                    $ticketsByCategory = $order->tickets->groupBy('event_ticket_category_id');
                    foreach ($ticketsByCategory as $categoryId => $tickets) {
                        if ($categoryId) {
                            EventTicketCategory::where('id', $categoryId)
                                ->increment('sold', $tickets->count());
                        }
                    }
                    
                    // Generate QR codes for tickets
                    foreach ($order->tickets as $ticket) {
                        $ticket->update([
                            'qr_code' => $ticket->ticket_code,
                        ]);
                    }
                    
                    return response()->json(['status' => 'success', 'payment_status' => 'paid']);
                } elseif ($status->transaction_status === 'pending') {
                    return response()->json(['status' => 'success', 'payment_status' => 'pending']);
                } else {
                    return response()->json(['status' => 'success', 'payment_status' => 'failed']);
                }
            }
            
            return response()->json(['status' => 'error', 'message' => 'Midtrans not configured']);
            
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Midtrans payment callback/notification (webhook dari Midtrans server)
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $order = Order::where('order_number', $request->order_id)->first();
            
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                // Only update if not already paid
                if ($order->status !== 'paid') {
                    $order->update([
                        'status' => 'paid',
                        'payment_type' => $request->payment_type,
                        'midtrans_transaction_id' => $request->transaction_id,
                        'paid_at' => now(),
                        'midtrans_response' => json_encode($request->all()),
                    ]);
                    
                    // Update sold count for each ticket category
                    $ticketsByCategory = $order->tickets->groupBy('event_ticket_category_id');
                    foreach ($ticketsByCategory as $categoryId => $tickets) {
                        if ($categoryId) {
                            EventTicketCategory::where('id', $categoryId)
                                ->increment('sold', $tickets->count());
                        }
                    }
                    
                    // Generate QR codes for tickets
                    foreach ($order->tickets as $ticket) {
                        $ticket->update([
                            'qr_code' => $ticket->ticket_code,
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Mock payment success (for development)
     */
    public function mockPaymentSuccess($orderId)
    {
        $order = Order::with(['event', 'tickets'])->findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate payment success
        $order->update([
            'status' => 'paid',
            'payment_type' => 'mock_payment',
            'midtrans_transaction_id' => 'MOCK-' . time(),
            'paid_at' => now(),
        ]);

        // Update sold count for each ticket category
        $ticketsByCategory = $order->tickets->groupBy('event_ticket_category_id');
        foreach ($ticketsByCategory as $categoryId => $tickets) {
            if ($categoryId) {
                EventTicketCategory::where('id', $categoryId)
                    ->increment('sold', $tickets->count());
            }
        }

        // Generate QR codes for tickets
        foreach ($order->tickets as $ticket) {
            $ticket->update([
                'qr_code' => $ticket->ticket_code,
            ]);
        }

        return redirect()->route('order.success', $orderId)
            ->with('success', 'Pembayaran berhasil! (Development Mode)');
    }

    /**
     * Show order success page
     */
    public function success($orderId)
    {
        $order = Order::with(['event', 'tickets.ticketCategory'])->findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('order-success', compact('order'));
    }

    /**
     * Print ticket page
     */
    public function printTicket($orderId)
    {
        $order = Order::with(['event', 'tickets.ticketCategory'])->findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            return redirect()->back()->with('error', 'Tiket hanya dapat dicetak setelah pembayaran berhasil.');
        }

        return view('print-ticket', compact('order'));
    }

    /**
     * Show user's order history
     */
    public function myOrders()
    {
        $orders = Order::with(['event', 'tickets'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('my-orders', compact('orders'));
    }
}
