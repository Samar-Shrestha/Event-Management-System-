<?php
// paypal-sdk/autoload.php

// Load the PayPal SDK autoloader
if (file_exists(__DIR__ . '/PayPal-PHP-SDK/autoload.php')) {
    require_once __DIR__ . '/PayPal-PHP-SDK/autoload.php';
} else {
    // Alternative: Manual loading if autoload doesn't exist
    require_once __DIR__ . '/PayPal-PHP-SDK/lib/PayPal/autoload.php';
}

// If still not working, manually include required classes
function loadPayPalClasses() {
    $paypalPath = __DIR__ . '/PayPal-PHP-SDK/lib/';
    
    // Basic required classes
    $requiredClasses = [
        'PayPal/Api/Amount.php',
        'PayPal/Api/Payer.php',
        'PayPal/Api/Payment.php',
        'PayPal/Api/RedirectUrls.php',
        'PayPal/Api/Transaction.php',
        'PayPal/Auth/OAuthTokenCredential.php',
        'PayPal/Rest/ApiContext.php'
    ];
    
    foreach ($requiredClasses as $class) {
        if (file_exists($paypalPath . $class)) {
            require_once $paypalPath . $class;
        }
    }
}

// Try to load classes
if (!class_exists('PayPal\Api\Payment')) {
    loadPayPalClasses();
}
?>