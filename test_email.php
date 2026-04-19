<?php
// Test PHPMailer with your credentials
require_once 'send_ticket_email.php';

echo "<h2>Testing PHPMailer Configuration</h2>";

// Test booking data
$test_booking = [
    'id' => '999',
    'nm' => 'Test User',
    'email' => 'shresthasamar76@gmail.com', // CHANGE THIS TO YOUR EMAIL
    'thm_nm' => 'Test Theme - Anniversary',
    'price' => '15000',
    'date' => date('Y-m-d'),
    'transaction_id' => 'TEST-' . time()
];

echo "<p>Attempting to send test email to: <strong>" . $test_booking['email'] . "</strong></p>";

if(sendTicketEmail($test_booking, 'event')) {
    echo "<p style='color:green;'>✅ Email sent successfully! Check your inbox.</p>";
} else {
    echo "<p style='color:red;'>❌ Email failed. Check error log.</p>";
}

// Display PHPMailer path info
echo "<hr>";
echo "<h3>PHPMailer Status:</h3>";
$phpmailer_path = __DIR__ . '/PHPMailer/src/PHPMailer.php';
if(file_exists($phpmailer_path)) {
    echo "<p style='color:green;'>✅ PHPMailer found at: " . $phpmailer_path . "</p>";
} else {
    echo "<p style='color:red;'>❌ PHPMailer not found</p>";
}
?>