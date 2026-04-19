<?php
session_start();
require_once('paypal_config.php');

if (!isset($_SESSION['booking_ids']) || empty($_SESSION['booking_ids'])) {
    header("Location: cart.php");
    exit();
}

$total_price_npr = $_SESSION['booking_info']['total_price'];
$exchange_rate = 130;
$amount_usd = number_format($total_price_npr / $exchange_rate, 2);

$invoice = 'INV-' . time() . '-' . rand(1000, 9999);
$_SESSION['paypal_invoice'] = $invoice;

$custom_data = base64_encode(json_encode([
    'booking_ids' => $_SESSION['booking_ids'],
    'user_email' => $_SESSION['booking_info']['customer_email'],
    'user_name' => $_SESSION['booking_info']['customer_name']
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
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-cancel {
            display: inline-block;
            margin-top: 20px;
            color: #666;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="payment-box">
        <div class="spinner"></div>
        <h2>Processing Secure Payment</h2>
        <p><strong>Amount:</strong> NPR <?php echo number_format($total_price_npr); ?> (≈ $<?php echo $amount_usd; ?> USD)</p>
        <p><strong>Invoice:</strong> <?php echo $invoice; ?></p>
        <p>Please wait, redirecting to PayPal...</p>
        <a href="cart.php" class="btn-cancel">← Go back to cart</a>

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
        setTimeout(function() {
            document.getElementById('paypalForm').submit();
        }, 2000);
    </script>
</body>
</html>