<?php
define("PAYPAL_URL", "https://www.sandbox.paypal.com/cgi-bin/webscr");
define("PAYPAL_BUSINESS_EMAIL", "sb-647x0u48254548@business.example.com"); // sandbox business email
define("PAYPAL_CURRENCY", "USD");

// Return URLs
define("PAYPAL_RETURN_URL", "http://localhost/Event-Management-System/paypal_success.php");
define("PAYPAL_CANCEL_URL", "http://localhost/Event-Management-System/paypal_cancel.php");
define("PAYPAL_NOTIFY_URL", "http://localhost/Event-Management-System/ipn.php");
?>
