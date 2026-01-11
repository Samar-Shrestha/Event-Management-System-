<?php
session_start();
require_once('paypal_config.php');

// Validate booking session
if (
    !isset($_SESSION['booking_ids']) ||
    empty($_SESSION['booking_ids']) ||
    !isset($_SESSION['booking_info'])
) {
    header("Location: booking.php");
    exit();
}

// Get total price and convert to USD (example rate)
$total_price_npr = $_SESSION['booking_info']['total_price'];
$exchange_rate   = 75; // NPR → USD (adjust if needed)
$amount_usd      = number_format($total_price_npr / $exchange_rate, 2);

// Generate unique invoice
$invoice = 'INV-' . time() . '-' . rand(1000, 9999);
$_SESSION['paypal_invoice'] = $invoice;

// Custom data for IPN
$custom_data = base64_encode(json_encode([
    'booking_ids' => $_SESSION['booking_ids'],
    'user_id'     => $_SESSION['user_id'] ?? 0
]));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to PayPal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            text-align: center;
            padding-top: 80px;
        }
        .payment-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            max-width: 450px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="payment-box">
    <h2>Processing Secure Payment</h2>
    <p><strong>Amount:</strong> $<?php echo $amount_usd; ?> USD</p>
    <p><strong>Invoice:</strong> <?php echo $invoice; ?></p>
    <p>Please wait, you are being redirected to PayPal.</p>

    <form id="paypalForm" action="<?php echo PAYPAL_URL; ?>" method="post">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="<?php echo PAYPAL_BUSINESS_EMAIL; ?>">
        <input type="hidden" name="item_name" value="Event Theme Booking">
        <input type="hidden" name="item_number" value="<?php echo $invoice; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount_usd; ?>">
        <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
        <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
        <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
        <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">
        <input type="hidden" name="custom" value="<?php echo $custom_data; ?>">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="no_note" value="1">
    </form>
</div>

<script>
    // Auto-submit PayPal form
    document.getElementById('paypalForm').submit();
</script>

</body>
</html>
