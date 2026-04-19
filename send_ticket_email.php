<?php
/**
 * send_ticket_email.php - Fixed QR code generation & embedding
 * Uses inline attachment (CID) for better email client compatibility
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

// Include phpqrcode library
require_once __DIR__ . '/phpqrcode/qrlib.php';

// ========== YOUR GMAIL CREDENTIALS ==========
define('SMTP_HOST',      'smtp.gmail.com');
define('SMTP_USERNAME',  'shresthasamar76@gmail.com');
define('SMTP_PASSWORD',  'gnibmzrariykbahd');   // App Password
define('SMTP_PORT',      587);
define('MAIL_FROM',      'shresthasamar76@gmail.com');
define('MAIL_FROM_NAME', 'Classic Events');
// =============================================

// Get base URL for QR code (use your public IP or domain later)
function getBaseUrl() {
    // For now use local IP – works for QR image generation, but scan will only work on same WiFi
    // Later replace with ngrok or domain
    $local_ip = '192.168.1.9';   // <-- YOUR LOCAL IP
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $script_dir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    return $protocol . '://' . $local_ip . $script_dir;
}

/**
 * Generate QR code image file and return file path
 */
function generateQRCodeFile($data, $booking_id) {
    $tempDir = __DIR__ . '/temp_qr/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }
    $fileName = 'qr_booking_' . $booking_id . '.png';
    $filePath = $tempDir . $fileName;
    
    // Generate QR code directly to file
    QRcode::png($data, $filePath, QR_ECLEVEL_L, 10);
    
    if (!file_exists($filePath)) {
        error_log("QR code generation failed for booking #$booking_id");
        return false;
    }
    return $filePath;
}

/**
 * Send ticket email with embedded QR code (as inline attachment)
 */
function sendTicketEmail(array $booking, string $table = 'event'): bool
{
    if (empty($booking['email'])) {
        error_log("sendTicketEmail: No email for booking #" . ($booking['id'] ?? 'unknown'));
        return false;
    }
    
    $id = $booking['id'] ?? '';
    $nm = $booking['nm'] ?? '';
    $email = $booking['email'] ?? '';
    $thm_nm = $booking['thm_nm'] ?? '';
    $price = $booking['price'] ?? '';
    $date = $booking['date'] ?? '';
    
    // Build invitation link
    $base_url = getBaseUrl();
    $invitation_link = $base_url . "/verify_booking.php?booking_id=" . $id;
    
    // Generate QR code file
    $qr_file = generateQRCodeFile($invitation_link, $id);
    if (!$qr_file) {
        error_log("QR code generation FAILED for booking #$id");
        // Fallback: send email without QR code but with link
        $qr_available = false;
    } else {
        $qr_available = true;
    }
    
    // Build HTML email
    $html = buildEmailHTML($booking, $table, $qr_available, $invitation_link);
    
    try {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = 0;               // set to 2 for debugging
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress($email, $nm);
        
        $mail->isHTML(true);
        $mail->Subject = '🎉 Your Digital Invitation Card - Booking #' . $id;
        $mail->Body = $html;
        $mail->AltBody = "Booking Confirmed!\nBooking ID: $id\nHost Name: $nm\nTheme: $thm_nm\nDate: $date\nAmount: NPR $price\n\nShow this email at the entrance.\n\nInvitation link: $invitation_link";
        
        // Attach QR code inline if generated
        if ($qr_available) {
            $mail->addEmbeddedImage($qr_file, 'qr_image', 'qrcode.png');
        }
        
        $mail->send();
        error_log("✅ Email sent to: $email for booking #$id");
        
        // Delete temp QR file after sending
        if ($qr_available && file_exists($qr_file)) {
            unlink($qr_file);
        }
        return true;
    } catch (Exception $e) {
        error_log("❌ Email failed for booking #$id: " . $mail->ErrorInfo);
        return false;
    }
}

function buildEmailHTML(array $b, string $table, bool $qr_available, string $invitation_link): string
{
    $booking_id = htmlspecialchars($b['id'] ?? '');
    $name = htmlspecialchars($b['nm'] ?? '');
    $theme_name = htmlspecialchars($b['thm_nm'] ?? '');
    $date = htmlspecialchars($b['date'] ?? '');
    $price = htmlspecialchars($b['price'] ?? '');
    
    $qr_html = '';
    if ($qr_available) {
        $qr_html = '
            <div style="text-align:center;margin:30px 0;">
                <h3 style="color:#667eea;">Your Entry QR Code</h3>
                <img src="cid:qr_image" alt="QR Code" style="border:3px solid #667eea;border-radius:10px;padding:10px; width:200px; height:200px;">
                <p style="font-size:12px;color:#999;margin-top:10px;">
                    Scan this QR code at the venue entrance.<br>
                    Or click: <a href="'.$invitation_link.'" style="color:#667eea;">'.$invitation_link.'</a>
                </p>
            </div>';
    } else {
        $qr_html = '
            <div style="text-align:center;margin:30px 0;">
                <p style="color:red;">QR code could not be generated. Please use this link:</p>
                <a href="'.$invitation_link.'" style="color:#667eea;">'.$invitation_link.'</a>
            </div>';
    }
    
    return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Digital Invitation Card</title>
</head>
<body style="margin:0;padding:0;font-family:Arial,sans-serif;background:#f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:20px;">
        <tr>
            <td align="center">
                <table width="550" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background:linear-gradient(135deg, #667eea, #764ba2);padding:30px;text-align:center;">
                            <h1 style="color:#ffffff;margin:0;">✨ YOU'RE INVITED ✨</h1>
                            <p style="color:#ffffff;opacity:0.9;margin:10px 0 0;">Classic Events - Digital Pass</p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding:30px;">
                            <p style="font-size:16px;color:#333;">Dear Host <strong>$name</strong>,</p>
                            <p style="font-size:14px;color:#666;">Your event booking is confirmed. Present this invitation at the entrance.</p>
                            
                            <table width="100%" cellpadding="10" cellspacing="0" style="background:#f9f9f9;border-radius:8px;margin:20px 0;">
                                <tr><td style="border-bottom:1px solid #eee;"><strong>Booking ID:</strong></td><td>#$booking_id</td></tr>
                                <tr><td style="border-bottom:1px solid #eee;"><strong>Event Theme:</strong></td><td>$theme_name</td></tr>
                                <tr><td style="border-bottom:1px solid #eee;"><strong>Event Date:</strong></td><td>$date</td></tr>
                                <tr><td style="border-bottom:1px solid #eee;"><strong>Host Name:</strong></td><td>$name</td></tr>
                                <tr><td style="border-bottom:1px solid #eee;"><strong>📍 Event Location:</strong></td><td>Classic Events Hall, Kathmandu</td></tr>
                                <tr><td><strong>Amount Paid:</strong></td><td>NPR $price</td></tr>
                            </table>
                            
                            <!-- ADDED: Warning message -->
                            <div style="background:#fff3cd;border-left:4px solid #ffc107;padding:10px 15px;margin:20px 0;border-radius:6px;font-size:13px;color:#856404;">
                                ⚠️ <strong>One-time use only.</strong> This ticket will be invalid after scanning.
                            </div>
                            
                            $qr_html
                            
                            <p style="font-size:12px;color:#666;margin-top:20px;">
                                📍 Venue: Classic Events Hall, Kathmandu<br>
                                ⏰ Gates open 1 hour before event
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background:#f9f9f9;padding:20px;text-align:center;border-top:1px solid #eee;">
                            <p style="font-size:12px;color:#999;">Classic Events Hall, Kathmandu</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
}