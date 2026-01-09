<?php
// paypal_config.php - CORRECTED VERSION

// Always use sandbox for testing
define('PAYPAL_ENV', 'sandbox'); // 'sandbox' or 'live'

// Sandbox Credentials
define('PAYPAL_BUSINESS_EMAIL','sb-oddy4748589805@business.example.com');

// PayPal URLs - FIXED (with correct dots)
if (PAYPAL_ENV === 'sandbox') {
    define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
    define('PAYPAL_BUTTON_URL', 'https://www.sandbox.paypal.com/checkoutweb');
} else {
    define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
    define('PAYPAL_BUTTON_URL', 'https://www.paypal.com/checkoutweb');
}

// Use YOUR ACTUAL LOCALHOST URL - make sure it's accessible
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$base_url = rtrim($base_url, '/');

define('PAYPAL_RETURN_URL', $base_url . '/paypal_success.php');
define('PAYPAL_CANCEL_URL', $base_url . '/paypal_cancel.php');
define('PAYPAL_NOTIFY_URL', $base_url . '/ipn.php');

// Currency - Use USD for sandbox
define('PAYPAL_CURRENCY', 'USD');

// Debug mode
define('PAYPAL_DEBUG', true);
?>