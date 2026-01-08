<?php
session_start();
include('Database/connect.php');
include('paypal_config.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if booking info exists
if(!isset($_SESSION['booking_info']) || !isset($_SESSION['booking_ids'])) {
    header("Location: gallery.php");
    exit();
}

$booking_info = $_SESSION['booking_info'];
$booking_ids = $_SESSION['booking_ids'];
$total_price = $booking_info['total_price'];

// For testing, use a small amount (sandbox has limits)
// Remove this in production
if(PAYPAL_SANDBOX) {
    $usd_amount = "1.00"; // $1 for testing
} else {
    // Convert to USD (adjust exchange rate as needed)
    $usd_amount = round($total_price / 120, 2);
}

// 1. Get Access Token
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . '/v1/oauth2/token');
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, PAYPAL_CLIENT_ID . ":" . PAYPAL_SECRET);
curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Accept: application/json",
    "Accept-Language: en_US"
));

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

if($curl_error) {
    die("cURL Error: " . $curl_error);
}

if(empty($result)) {
    die("Error: No response from PayPal");
}

$json = json_decode($result);

if($http_code != 200) {
    echo "Failed to get access token.<br>";
    echo "HTTP Code: " . $http_code . "<br>";
    echo "Response: " . $result . "<br>";
    exit();
}

if(!isset($json->access_token)) {
    echo "No access token in response:<br>";
    print_r($json);
    exit();
}

$access_token = $json->access_token;
curl_close($ch);

// Debug: Show token info
// echo "Access Token Obtained: " . substr($access_token, 0, 50) . "...<br>";

// 2. Create Payment
$payment_data = [
    "intent" => "sale",
    "payer" => [
        "payment_method" => "paypal"
    ],
    "transactions" => [[
        "amount" => [
            "total" => $usd_amount,
            "currency" => "USD"
        ],
        "description" => "Event Theme Booking - Classic Event",
        "custom" => json_encode(['booking_ids' => $booking_ids]),
        "invoice_number" => "INV-" . time()
    ]],
    "redirect_urls" => [
        "return_url" => PAYPAL_RETURN_URL,
        "cancel_url" => PAYPAL_CANCEL_URL
    ]
];

// Debug: Show payment data
// echo "<pre>Payment Data: ";
// print_r($payment_data);
// echo "</pre>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, PAYPAL_BASE_URL . "/v1/payments/payment");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $access_token,
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true); // Enable for debugging

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

// Debug output
if($curl_error) {
    echo "cURL Error creating payment: " . $curl_error . "<br>";
}

// echo "HTTP Code: " . $http_code . "<br>";
// echo "Response: " . $result . "<br>";

curl_close($ch);

if($result) {
    $json = json_decode($result);
    
    if($http_code == 201 && isset($json->id)) {
        $payment_id = $json->id;
        
        // Store payment ID in session
        $_SESSION['paypal_payment_id'] = $payment_id;
        
        // Find approval URL
        if(isset($json->links)) {
            foreach($json->links as $link) {
                if($link->rel == "approval_url") {
                    $approval_url = $link->href;
                    
                    // Debug
                    // echo "Redirecting to: " . $approval_url . "<br>";
                    
                    header("Location: " . $approval_url);
                    exit();
                }
            }
        }
        
        echo "Payment created but no approval URL found.<br>";
        print_r($json);
        
    } else {
        echo "Error creating PayPal payment.<br>";
        echo "HTTP Code: " . $http_code . "<br>";
        echo "Error Details: <br>";
        if(isset($json->name)) {
            echo "Error: " . $json->name . "<br>";
        }
        if(isset($json->message)) {
            echo "Message: " . $json->message . "<br>";
        }
        if(isset($json->details)) {
            echo "Details: ";
            print_r($json->details);
        }
        echo "<br>Full Response: " . $result;
    }
} else {
    echo "Failed to connect to PayPal. Please try again later.<br>";
    if($curl_error) {
        echo "cURL Error: " . $curl_error;
    }
}
?>