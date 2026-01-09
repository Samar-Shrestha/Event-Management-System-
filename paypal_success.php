<?php
session_start();
include("Database/connect.php");

// Update booking status
if (isset($_SESSION['booking_ids'])) {
    foreach ($_SESSION['booking_ids'] as $id) {
        mysqli_query(
            $con,
            "UPDATE booking 
             SET payment_status='completed' 
             WHERE id='$id'"
        );
    }
}



// Clear cart
mysqli_query($con, "DELETE FROM temp");

// Clear session
unset($_SESSION['booking_ids']);
unset($_SESSION['booking_info']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>

<h2 style="color:green;">✅ Payment Successful</h2>
<p>Your booking has been confirmed.</p>

<a href="index.php">Go to Home</a>

</body>
</html>
