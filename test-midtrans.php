<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== MIDTRANS CONFIGURATION TEST ===\n\n";
echo "Server Key: " . config('midtrans.server_key') . "\n";
echo "Client Key: " . config('midtrans.client_key') . "\n";
echo "Is Production: " . (config('midtrans.is_production') ? 'true' : 'false') . "\n";
echo "Is Sanitized: " . (config('midtrans.is_sanitized') ? 'true' : 'false') . "\n";
echo "Is 3DS: " . (config('midtrans.is_3ds') ? 'true' : 'false') . "\n\n";

// Test Midtrans Connection
try {
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    
    echo "✅ Midtrans Config Set Successfully!\n";
    echo "✅ Server Key Valid: " . (!empty(config('midtrans.server_key')) ? 'YES' : 'NO') . "\n";
    
    // Test create dummy transaction
    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000,
        ],
        'customer_details' => [
            'first_name' => 'Test User',
            'email' => 'test@example.com',
        ],
    ];
    
    echo "\nAttempting to get Snap Token...\n";
    $snapToken = \Midtrans\Snap::getSnapToken($params);
    echo "✅ SUCCESS! Snap Token: $snapToken\n";
    echo "✅ Midtrans Integration Working!\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nPossible causes:\n";
    echo "1. Merchant belum aktif di dashboard Midtrans\n";
    echo "2. Server Key salah atau expired\n";
    echo "3. Perlu klik 'Mulai buat kredensial' di dashboard\n";
}
