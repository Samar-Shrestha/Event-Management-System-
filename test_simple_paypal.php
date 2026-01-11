<?php
// test_simple_paypal.php - MINIMAL TEST
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test PayPal</title>
</head>
<body>
    <h2>Test PayPal Payment</h2>
    
    <!-- Simple PayPal Button -->
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="sb-647x0u48254548@business.example.com">
        <input type="hidden" name="lc" value="US">
        <input type="hidden" name="item_name" value="Test Item">
        <input type="hidden" name="item_number" value="TEST-001">
        <input type="hidden" name="amount" value="10.00">
        <input type="hidden" name="currency_code" value="USD">
        <input type="hidden" name="button_subtype" value="services">
        <input type="hidden" name="no_note" value="0">
        <input type="hidden" name="cn" value="Add special instructions to the seller:">
        <input type="hidden" name="no_shipping" value="2">
        <input type="hidden" name="rm" value="1">
        <input type="hidden" name="return" value="http://localhost/Event-Management-System/paypal_success.php">
        <input type="hidden" name="cancel_return" value="http://localhost/Event-Management-System/paypal_cancel.php">
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
        
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    
    <p><strong>Test Card for Sandbox:</strong></p>
    <ul>
        <li>Card: 4032036343571986</li>
        <li>Exp: 12/2025</li>
        <li>CVV: 123</li>
    </ul>
</body>
</html>