<?php
// PayPal Configuration for Sandbox (Testing)
define('PAYPAL_SANDBOX', true); // Set to false for production

if (PAYPAL_SANDBOX) {
    // Sandbox Credentials
    define('PAYPAL_CLIENT_ID', 'AUTEqz85GlH6AYb8GWYJv1zK-G_9POJLB4OlAczu5q1n8pUno-SoNJeEIaktws3IdbNFB3QxIGuJ9NYx');
    define('PAYPAL_SECRET', 'ELMSIwe91tCSHvdOWFMDJ549oVMcDiFDQERaBEhaxb_KccsyJBbb_L2F6a_NDcvrR9b5P4oXyBHj92L9');
    define('PAYPAL_BASE_URL', 'https://api.sandbox.paypal.com');
    define('PAYPAL_RETURN_URL', 'http://127.0.0.1/classic_event/paypal_success.php');
    define('PAYPAL_CANCEL_URL', 'http://127.0.0.1/classic_event/paypal_cancel.php');
} else {
    // Live Credentials
    define('PAYPAL_CLIENT_ID', 'YOUR_LIVE_CLIENT_ID');
    define('PAYPAL_SECRET', 'YOUR_LIVE_SECRET');
    define('PAYPAL_BASE_URL', 'https://api.paypal.com');
    define('PAYPAL_RETURN_URL', 'https://yourdomain.com/classic_event/paypal_success.php');
    define('PAYPAL_CANCEL_URL', 'https://yourdomain.com/classic_event/paypal_cancel.php');
}

// Database configuration
include('Database/connect.php');
?>