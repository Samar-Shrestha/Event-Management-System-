<?php
// Include phpqrcode library
include_once('phpqrcode/qrlib.php');

function generateQRCodeBase64($data, $size = 10) {
    // Temporary file path
    $tempDir = __DIR__ . '/temp_qr/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    
    $fileName = $tempDir . 'qr_' . md5($data) . '.png';
    
    // Generate QR code
    QRcode::png($data, $fileName, QR_ECLEVEL_L, $size, 2);
    
    // Convert to base64
    $imageData = base64_encode(file_get_contents($fileName));
    
    // Delete temp file
    unlink($fileName);
    
    return 'data:image/png;base64,' . $imageData;
}
?>