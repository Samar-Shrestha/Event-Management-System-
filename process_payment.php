<?php
// process_payment.php - WORKING VERSION
session_start();
require_once('paypal_config.php');

// Debug info
if (PAYPAL_DEBUG) {
    echo "<pre>";
    echo "Session Data:\n";
    print_r($_SESSION);
    echo "</pre>";
}

// Check if we have booking data
if (!isset($_SESSION['booking_ids']) || empty($_SESSION['booking_ids'])) {
    echo "<script>alert('Session expired. Please start over.'); window.location='booking.php';</script>";
    exit();
}

// Calculate amount - use a small amount for testing
$total_price = $_SESSION['booking_info']['total_price'] ?? 0;
$test_amount = 1.00; // Use $1 for testing
// $test_amount = number_format($total_price / 75, 2); // Real calculation

// Create invoice
$invoice = 'INV-' . time() . '-' . rand(100, 999);

// Store in session
$_SESSION['paypal_invoice'] = $invoice;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment...</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            max-width: 500px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Processing Payment</h2>
    
    <div class="info-box">
        <h3>Payment Details:</h3>
        <p><strong>Amount:</strong> $<?php echo $test_amount; ?> USD</p>
        <p><strong>Invoice:</strong> <?php echo $invoice; ?></p>
        <p><strong>Description:</strong> Theme Booking</p>
    </div>
    
    <!-- PayPal Form -->
    <form id="paypalForm" action="<?php echo PAYPAL_URL; ?>" method="post">
        <!-- Required Parameters -->
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php echo PAYPAL_BUSINESS_EMAIL; ?>">
        
        <!-- Item Details -->
        <input type="hidden" name="item_name" value="Theme Booking">
        <input type="hidden" name="item_number" value="<?php echo $invoice; ?>">
        <input type="hidden" name="amount" value="<?php echo $test_amount; ?>">
        <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
        
        <!-- Return URLs -->
        <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL . '?invoice=' . $invoice; ?>">
        <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL . '?invoice=' . $invoice; ?>">
        <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">
        
        <!-- Additional Settings -->
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="bn" value="PP-BuyNowBF">
        <input type="hidden" name="custom" value="<?php echo base64_encode(json_encode([
            'booking_ids' => $_SESSION['booking_ids'],
            'user_id' => $_SESSION['user_id'] ?? 0
        ])); ?>">
        
        <!-- Shipping (not needed for digital) -->
        <input type="hidden" name="shipping" value="0">
        <input type="hidden" name="shipping2" value="0">
        <input type="hidden" name="handling" value="0">
        <input type="hidden" name="tax" value="0">
        
        <!-- Button -->
        <p>
            <button type="submit" style="
                background: #0070ba;
                color: white;
                border: none;
                padding: 15px 40px;
                font-size: 18px;
                border-radius: 5px;
                cursor: pointer;
            ">
                Pay with PayPal - $<?php echo $test_amount; ?> USD
            </button>
        </p>
    </form>
    
    <p><em>You will be redirected to PayPal's secure payment page.</em></p>
    
    <script>
        // Auto-submit after 2 seconds
        setTimeout(function() {
            document.getElementById('paypalForm').submit();
        }, 2000);
    </script>
</body>
</html>