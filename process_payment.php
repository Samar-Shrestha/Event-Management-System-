<?php
session_start();
include("paypal_config.php");

// Security check
if (!isset($_SESSION['booking_info']) || !isset($_SESSION['booking_ids'])) {
    header("Location: cart.php");
    exit();
}

$total_amount = $_SESSION['booking_info']['total_price'];
$booking_ids = implode(",", $_SESSION['booking_ids']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to PayPal...</title>
</head>
<body>

<p style="text-align:center; margin-top:50px;">
    Redirecting to PayPal, please wait...
</p>

<form action="<?php echo PAYPAL_URL; ?>" method="post" id="paypal_form">

    <!-- PayPal Required -->
    <input type="hidden" name="business" value="<?php echo PAYPAL_BUSINESS_EMAIL; ?>">
    <input type="hidden" name="cmd" value="_xclick">

    <!-- Payment Details -->
    <input type="hidden" name="item_name" value="Event Theme Booking">
    <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
    <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">

    <!-- URLs -->
    <input type="hidden" name="return" value="<?php echo PAYPAL_RETURN_URL; ?>">
    <input type="hidden" name="cancel_return" value="<?php echo PAYPAL_CANCEL_URL; ?>">
    <input type="hidden" name="notify_url" value="<?php echo PAYPAL_NOTIFY_URL; ?>">

    <!-- Custom Data -->
    <input type="hidden" name="custom" value="<?php echo $booking_ids; ?>">

</form>

<script>
    document.getElementById("paypal_form").submit();
</script>

</body>
</html>
