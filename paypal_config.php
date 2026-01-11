<?php
/**
 * PayPal Configuration File
 * Environment-ready (Sandbox / Live)
 */

// Environment: 'sandbox' or 'live'
define('PAYPAL_ENV', 'sandbox');

// PayPal Business Email
define('PAYPAL_BUSINESS_EMAIL', 'sb-oddy4748589805@business.example.com');

// PayPal Endpoints
if (PAYPAL_ENV === 'sandbox') {
    define('PAYPAL_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');
    define('PAYPAL_BUTTON_URL', 'https://www.sandbox.paypal.com/checkoutweb');
} else {
    define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr');
    define('PAYPAL_BUTTON_URL', 'https://www.paypal.com/checkoutweb');
}

// Base URL (Auto-detected)
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/');

// PayPal Redirect URLs
define('PAYPAL_RETURN_URL', $base_url . '/paypal_success.php');
define('PAYPAL_CANCEL_URL', $base_url . '/paypal_cancel.php');
define('PAYPAL_NOTIFY_URL', $base_url . '/ipn.php');

// Currency
define('PAYPAL_CURRENCY', 'USD');

// Debug (keep FALSE for production)
define('PAYPAL_DEBUG', false);
