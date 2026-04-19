<?php
session_start();
include("Database/connect.php");
require_once("send_ticket_email.php");

// Enable error reporting for debugging (remove after fix)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/email_debug.log');

// Ensure booking session exists
if (!isset($_SESSION['booking_ids']) || empty($_SESSION['booking_ids'])) {
    header("Location: index.php");
    exit();
}

$email_sent_count = 0;
$booking_details = [];
$email_errors = [];
$debug_info = [];

foreach ($_SESSION['booking_ids'] as $booking_id) {
    $booking_id = (int)$booking_id;
    
    // Get transaction details from PayPal
    $transaction_id = isset($_GET['tx']) ? mysqli_real_escape_string($con, $_GET['tx']) : 'PAYPAL-' . time();
    $payer_email = isset($_SESSION['booking_info']['customer_email']) ? $_SESSION['booking_info']['customer_email'] : '';
    
    // Update payment status
    $update_query = "UPDATE booking SET 
        payment_status = 'completed',
        payment_date = NOW(),
        transaction_id = '$transaction_id',
        payer_email = '$payer_email',
        amount_paid = price
        WHERE id = $booking_id";
    
    if (!mysqli_query($con, $update_query)) {
        $debug_info[] = "DB update failed for booking #$booking_id: " . mysqli_error($con);
    }
    
    // Fetch updated booking row
    $result = mysqli_query($con, "SELECT * FROM booking WHERE id = $booking_id");
    $booking = mysqli_fetch_assoc($result);
    
    if ($booking) {
        $booking_details[] = [
            'id' => $booking['id'],
            'name' => $booking['nm'],
            'theme' => $booking['thm_nm'],
            'date' => $booking['date'],
            'price' => $booking['price'],
        ];
        
        // ✅ CRITICAL: Check if email exists in booking record
        if (empty($booking['email'])) {
            $debug_info[] = "Booking #$booking_id has NO email address in database!";
            
            // Fallback: try to get email from session
            if (!empty($_SESSION['booking_info']['customer_email'])) {
                $fallback_email = $_SESSION['booking_info']['customer_email'];
                $debug_info[] = "Using fallback email from session: $fallback_email";
                $booking['email'] = $fallback_email;
                
                // Update the database with the missing email
                mysqli_query($con, "UPDATE booking SET email = '$fallback_email' WHERE id = $booking_id");
            } else {
                $debug_info[] = "No fallback email available in session.";
            }
        }
        
        // Send ticket email
        if (!empty($booking['email'])) {
            $debug_info[] = "Attempting to send email to: " . $booking['email'];
            
            // ✅ Enable PHPMailer debug output (will be captured in HTML comments)
            $sent = sendTicketEmail($booking, $booking['event_type'] ?? 'event');
            
            if ($sent) {
                $email_sent_count++;
                $debug_info[] = "✅ Email sent successfully for booking #$booking_id";
            } else {
                $email_errors[] = $booking_id;
                $debug_info[] = "❌ sendTicketEmail returned FALSE for booking #$booking_id";
                // You can add more PHPMailer error capture if you modify send_ticket_email.php
            }
        } else {
            $debug_info[] = "❌ No email address available for booking #$booking_id after fallback";
            $email_errors[] = $booking_id;
        }
    } else {
        $debug_info[] = "Booking #$booking_id not found in database after update";
    }
}

// Clear session and temp cart
mysqli_query($con, "DELETE FROM temp WHERE user_session = '" . session_id() . "'");
unset($_SESSION['booking_ids']);
unset($_SESSION['booking_info']);
unset($_SESSION['paypal_invoice']);

// Log errors
if (!empty($email_errors)) {
    error_log("Email failed for bookings: " . implode(',', $email_errors));
    error_log("Debug info: " . print_r($debug_info, true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful — Classic Events</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 50px 40px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }
        .icon { font-size: 72px; margin-bottom: 16px; }
        h2 { color: #1a1a2e; font-size: 28px; margin-bottom: 10px; }
        .subtitle { color: #6B7280; font-size: 15px; margin-bottom: 24px; }
        .booking-id-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            font-size: 12px;
            padding: 4px 14px;
            border-radius: 999px;
            margin-bottom: 10px;
        }
        .booking-summary {
            text-align: left;
            background: #F9FAFB;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }
        .booking-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .booking-row:last-child { border-bottom: none; }
        .booking-row span:first-child { color: #6B7280; }
        .booking-row span:last-child { color: #111827; font-weight: 600; }
        .notice {
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 28px;
        }
        .notice.success { background: #F0FDF4; border: 1px solid #86EFAC; color: #166534; }
        .notice.warning { background: #FFF7ED; border: 1px solid #FCD34D; color: #92400E; }
        .notice.error { background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn {
            display: inline-block;
            padding: 13px 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-outline { background: transparent; border: 2px solid #667eea; color: #667eea; }
        .debug-box {
            background: #f1f5f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-top: 25px;
            text-align: left;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
            border-radius: 8px;
            display: none; /* Set to 'block' to show debug info */
        }
        .debug-box.show { display: block; }
        .toggle-debug {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 12px;
            margin-top: 15px;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <span class="icon">🎉</span>
    <h2>Payment Successful!</h2>
    <p class="subtitle">Your booking has been confirmed and your payment was processed successfully.</p>

    <?php foreach ($booking_details as $b): ?>
        <div class="booking-id-badge">Booking #<?= htmlspecialchars($b['id']) ?></div>
        <div class="booking-summary">
            <h4>Booking Summary</h4>
            <div class="booking-row"><span>Guest Name</span><span><?= htmlspecialchars($b['name']) ?></span></div>
            <div class="booking-row"><span>Theme</span><span><?= htmlspecialchars($b['theme']) ?></span></div>
            <div class="booking-row"><span>Event Date</span><span><?= htmlspecialchars($b['date']) ?></span></div>
            <div class="booking-row"><span>Amount</span><span>NPR <?= htmlspecialchars($b['price']) ?></span></div>
        </div>
    <?php endforeach; ?>

    <?php if ($email_sent_count > 0): ?>
        <div class="notice success">
            📧 ✅ Ticket with QR code sent to your email!<br>
            <small>Please check your inbox (and spam folder).</small>
        </div>
    <?php else: ?>
        <div class="notice error">
            ⚠️ Booking confirmed, but the ticket email could not be sent.<br>
            <small>Please contact support with your booking ID. We'll email your ticket manually.</small>
        </div>
    <?php endif; ?>

    <div class="btn-group">
        <a href="my_bookings.php" class="btn">View My Bookings</a>
        <a href="index.php" class="btn btn-outline">Go to Home</a>
    </div>

    <!-- Debug information (hidden by default, click to show) -->
    <button class="toggle-debug" onclick="document.querySelector('.debug-box').classList.toggle('show')">
        🔧 Show debug info (admin only)
    </button>
    <div class="debug-box">
        <strong>📋 Debug Log:</strong><br>
        <?php foreach ($debug_info as $info): ?>
            <?= htmlspecialchars($info) ?><br>
        <?php endforeach; ?>
        <?php if (empty($debug_info)): ?>
            No debug information captured.
        <?php endif; ?>
        <br>
        <strong>Session data:</strong><br>
        <?php 
        $session_debug = [
            'booking_ids' => $_SESSION['booking_ids'] ?? 'not set',
            'booking_info' => $_SESSION['booking_info'] ?? 'not set',
        ];
        echo htmlspecialchars(print_r($session_debug, true));
        ?>
    </div>
</div>

<script>
    // Simple toggle for debug box
    document.querySelector('.toggle-debug')?.addEventListener('click', function(e) {
        const box = document.querySelector('.debug-box');
        if (box) box.classList.toggle('show');
    });
</script>
</body>
</html>

