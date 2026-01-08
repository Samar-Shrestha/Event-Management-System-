<?php
session_start();
include('Database/connect.php');
include('paypal_config.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate required parameters
if(!isset($_GET['paymentId']) || !isset($_GET['PayerID']) || !isset($_GET['token'])) {
    header("Location: paypal_cancel.php?error=missing_params");
    exit();
}

$payment_id = $_GET['paymentId'];
$payer_id = $_GET['PayerID'];
$token = $_GET['token'];

// Debug: Show received parameters (remove in production)
// echo "Payment ID: $payment_id<br>";
// echo "Payer ID: $payer_id<br>";
// echo "Token: $token<br>";

// Get access token
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . '/v1/oauth2/token');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Accept: application/json",
    "Accept-Language: en_US"
]);

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if(!$result) {
    die("Failed to get access token from PayPal");
}

$json = json_decode($result);

if($http_code != 200 || !isset($json->access_token)) {
    echo "Failed to get access token.<br>";
    echo "HTTP Code: $http_code<br>";
    echo "Response: " . $result . "<br>";
    exit();
}

$access_token = $json->access_token;
curl_close($ch);

// Execute payment
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . "/v1/payments/payment/$payment_id/execute");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["payer_id" => $payer_id]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $access_token",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

if($curl_error) {
    die("cURL Error: " . $curl_error);
}

curl_close($ch);

if(!$result) {
    die("No response from PayPal when executing payment");
}

$json = json_decode($result);

// Debug: Show PayPal response (remove in production)
// echo "<pre>PayPal Response: ";
// print_r($json);
// echo "</pre>";

if($http_code == 200 && isset($json->state) && $json->state == "approved") {
    // Payment successful
    
    // Get transaction details safely
    $transaction_id = "";
    $amount = "0.00";
    $currency = "USD";
    
    if(isset($json->transactions[0])) {
        if(isset($json->transactions[0]->related_resources[0]->sale->id)) {
            $transaction_id = $json->transactions[0]->related_resources[0]->sale->id;
        }
        
        if(isset($json->transactions[0]->amount)) {
            $amount = $json->transactions[0]->amount->total ?? "0.00";
            $currency = $json->transactions[0]->amount->currency ?? "USD";
        }
    }
    
    // Update booking records
    if(isset($_SESSION['booking_ids']) && is_array($_SESSION['booking_ids'])) {
        foreach($_SESSION['booking_ids'] as $booking_id) {
            // Sanitize booking ID
            $booking_id = mysqli_real_escape_string($con, $booking_id);
            
            // Update booking status
            $update_query = "UPDATE booking SET 
                payment_status = 'completed',
                payment_id = '" . mysqli_real_escape_string($con, $payment_id) . "',
                transaction_id = '" . mysqli_real_escape_string($con, $transaction_id) . "',
                payment_date = NOW(),
                amount_paid = '" . mysqli_real_escape_string($con, $amount) . "'
                WHERE id = '$booking_id'";
            
            if(!mysqli_query($con, $update_query)) {
                error_log("Failed to update booking $booking_id: " . mysqli_error($con));
            }
            
            // Insert into payments table if exists
            $payments_query = "INSERT INTO payments (booking_id, payment_id, payer_id, amount, currency, payment_status, payment_method, created_at) 
                VALUES ('$booking_id', 
                '" . mysqli_real_escape_string($con, $payment_id) . "', 
                '" . mysqli_real_escape_string($con, $payer_id) . "', 
                '" . mysqli_real_escape_string($con, $amount) . "', 
                '" . mysqli_real_escape_string($con, $currency) . "', 
                'completed', 
                'paypal', 
                NOW())";
            
            mysqli_query($con, $payments_query);
        }
        
        // Clear temp table
        mysqli_query($con, "DELETE FROM temp");
        
        // Clear session data
        unset($_SESSION['booking_ids']);
        unset($_SESSION['booking_info']);
        unset($_SESSION['paypal_payment_id']);
        
        // Display success message
        include("header.php");
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Payment Successful - Classic Event</title>
            <style>
                .success-container {
                    text-align: center;
                    padding: 50px;
                    background: #f8f9fa;
                    border-radius: 10px;
                    margin: 50px auto;
                    max-width: 600px;
                }
                .success-icon {
                    color: #28a745;
                    font-size: 80px;
                    margin-bottom: 20px;
                }
                .btn-home {
                    background: #007bff;
                    color: white;
                    padding: 12px 30px;
                    text-decoration: none;
                    border-radius: 5px;
                    display: inline-block;
                    margin: 10px;
                    border: none;
                    cursor: pointer;
                }
                .btn-home:hover {
                    opacity: 0.9;
                    text-decoration: none;
                    color: white;
                }
                .details {
                    background: white;
                    padding: 20px;
                    border-radius: 5px;
                    margin: 20px 0;
                    text-align: left;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="success-container">
                    <div class="success-icon">✓</div>
                    <h2>Payment Successful!</h2>
                    <p>Thank you for your payment. Your event has been booked successfully.</p>
                    
                    <div class="details">
                        <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($payment_id); ?></p>
                        <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($transaction_id); ?></p>
                        <p><strong>Amount Paid:</strong> $<?php echo htmlspecialchars($amount); ?> <?php echo htmlspecialchars($currency); ?></p>
                        <p><strong>Date:</strong> <?php echo date('F j, Y, g:i a'); ?></p>
                    </div>
                    
                    <p>A confirmation email has been sent to your registered email address.</p>
                    <a href="index.php" class="btn-home">Go to Homepage</a>
                    <a href="my_bookings.php" class="btn-home" style="background: #28a745;">View My Bookings</a>
                </div>
            </div>
        </body>
        </html>
        <?php
        include("footer.php");
        
    } else {
        // No booking IDs in session
        include("header.php");
        echo "<div class='container'><div class='alert alert-warning'>No booking found. Please contact support.</div></div>";
        include("footer.php");
    }
    
} else {
    // Payment failed
    $error_msg = "Payment could not be processed.";
    if(isset($json->name)) $error_msg .= " Error: " . $json->name;
    if(isset($json->message)) $error_msg .= " - " . $json->message;
    
    header("Location: paypal_cancel.php?error=" . urlencode($error_msg));
    exit();
}
?>