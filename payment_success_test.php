<?php
session_start();
include('Database/connect.php');

// Get payment details from URL
$payment_id = $_GET['payment_id'] ?? '';
$payer_email = $_GET['payer_email'] ?? '';
$payer_id = $_GET['payer_id'] ?? '';

if(empty($payment_id) || !isset($_SESSION['booking_ids'])) {
    die("Invalid payment confirmation");
}

// Update booking records
if(isset($_SESSION['booking_ids'])) {
    $total_price = $_SESSION['booking_info']['total_price'] ?? 0;
    
    foreach($_SESSION['booking_ids'] as $booking_id) {
        // Update booking
        mysqli_query($con, "UPDATE booking SET 
            payment_status = 'completed',
            payment_id = '$payment_id',
            transaction_id = '$payment_id',
            payment_date = NOW(),
            amount_paid = $total_price,
            payer_email = '$payer_email',
            payer_id = '$payer_id'
            WHERE id = $booking_id");
        
        // Add to payments table
        mysqli_query($con, "INSERT INTO payments (booking_id, payment_id, payer_id, payer_email, amount, currency, payment_status) 
            VALUES ($booking_id, '$payment_id', '$payer_id', '$payer_email', $total_price, 'USD', 'completed')");
    }
    
    // Clear temp table
    mysqli_query($con, "DELETE FROM temp");
    
    $booking_info = $_SESSION['booking_info'] ?? [];
    
    // Clear session
    unset($_SESSION['booking_ids']);
    unset($_SESSION['paypal_order_id']);
    unset($_SESSION['booking_info']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: Arial; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .success { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); max-width: 500px; text-align: center; }
        .success-icon { color: #28a745; font-size: 80px; margin-bottom: 20px; }
        .details { text-align: left; background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 50px; margin: 10px; }
        .btn-success { background: #28a745; }
    </style>
</head>
<body>
    <div class="success">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 style="color: #28a745;">🎉 Payment Successful! 🎉</h1>
        
        <div class="details">
            <h3>Booking Confirmed</h3>
            <?php if(isset($booking_info['customer_name'])): ?>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($booking_info['customer_name']); ?></p>
            <?php endif; ?>
            <?php if(isset($booking_info['customer_email'])): ?>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking_info['customer_email']); ?></p>
            <?php endif; ?>
            <?php if(isset($booking_info['booking_date'])): ?>
                <p><strong>Event Date:</strong> <?php echo htmlspecialchars($booking_info['booking_date']); ?></p>
            <?php endif; ?>
            <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_id); ?></p>
            <p><strong>Payment Email:</strong> <?php echo htmlspecialchars($payer_email); ?></p>
        </div>
        
        <p>A confirmation email has been sent to your registered email address.</p>
        
        <div>
            <a href="index.php" class="btn">Return to Home</a>
            <a href="mybookings.php" class="btn btn-success">View My Bookings</a>
        </div>
        
        <p style="margin-top: 20px; color: #666; font-size: 12px;">
            <i class="fas fa-info-circle"></i> This was a test payment using PayPal Sandbox.
        </p>
    </div>
</body>
</html>