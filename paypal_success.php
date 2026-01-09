<?php
session_start();
include("Database/connect.php");

// Ensure booking session exists
if (!isset($_SESSION['booking_ids']) || empty($_SESSION['booking_ids'])) {
    header("Location: index.php");
    exit();
}

// Update booking payment status
foreach ($_SESSION['booking_ids'] as $booking_id) {
    mysqli_query(
        $con,
        "UPDATE booking 
         SET payment_status = 'completed' 
         WHERE id = '$booking_id'"
    );
}

// Clear temporary cart
mysqli_query($con, "DELETE FROM temp");

// Clear session data
unset($_SESSION['booking_ids']);
unset($_SESSION['booking_info']);
unset($_SESSION['paypal_invoice']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            text-align: center;
            padding-top: 80px;
        }
        .success-box {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            max-width: 450px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success-box h2 {
            color: #28a745;
        }
        .success-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 25px;
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
        }
        .success-box a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="success-box">
    <h2>✅ Payment Successful</h2>
    <p>Your booking has been confirmed successfully.</p>
    <p>Thank you for choosing our service.</p>

    <a href="index.php">Go to Home</a>
</div>

</body>
</html>
