<?php
echo "Checking PHPMailer installation...<br>";

// Try different possible paths
$paths = [
    __DIR__ . '/PHPMailer/PHPMailer/src/PHPMailer.php',
    __DIR__ . '/PHPMailer/src/PHPMailer.php', 
    __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php',
    __DIR__ . '/includes/PHPMailer/src/PHPMailer.php'
];

foreach($paths as $path) {
    if(file_exists($path)) {
        echo "✅ Found PHPMailer at: " . $path . "<br>";
    } else {
        echo "❌ Not found: " . $path . "<br>";
    }
}
?>