<?php
session_start();
include("header.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Cancelled - Classic Event</title>
    <style>
        .cancel-container {
            text-align: center;
            padding: 50px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 50px auto;
            max-width: 600px;
        }
        .cancel-icon {
            color: #dc3545;
            font-size: 80px;
            margin-bottom: 20px;
        }
        .btn-retry {
            background: #dc3545;
            color: white;
            padding: 10px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="cancel-container">
            <div class="cancel-icon">✗</div>
            <h2>Payment Cancelled</h2>
            <p>Your payment was cancelled. No amount has been deducted from your account.</p>
            <p>If you faced any issues, please try again or contact our support.</p>
            <a href="booking.php" class="btn-retry">Try Again</a>
            <a href="index.php" class="btn-retry" style="background: #6c757d;">Go to Homepage</a>
        </div>
    </div>
</body>
</html>
<?php
include("footer.php");
?>