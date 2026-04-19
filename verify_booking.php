<?php
include('Database/connect.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get token or booking ID
$token = isset($_GET['token']) ? mysqli_real_escape_string($con, $_GET['token']) : null;
$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

if ($token) {
    $booking_id = (int)base64_decode($token);
}

if (!$booking_id) {
    die("Invalid invitation link.");
}

// Fetch booking including scan_count
$query = "SELECT id, nm, email, thm_nm, date, price, payment_status, scanned, scanned_at, scan_count 
          FROM booking WHERE id = $booking_id AND payment_status = 'completed' LIMIT 1";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Booking not found or payment not completed.");
}

$booking = mysqli_fetch_assoc($result);

// Initialize scan_count if NULL (old records)
if (!isset($booking['scan_count'])) {
    $booking['scan_count'] = 0;
    mysqli_query($con, "UPDATE booking SET scan_count = 0 WHERE id = $booking_id");
}

$remaining_scans = 2 - $booking['scan_count'];
$can_enter = ($remaining_scans > 0);

// If still allowed, increment scan_count and update scanned_at
if ($can_enter) {
    $new_count = $booking['scan_count'] + 1;
    $update = "UPDATE booking SET scan_count = $new_count, scanned_at = NOW() WHERE id = $booking_id";
    mysqli_query($con, $update);
    $booking['scan_count'] = $new_count;
    $booking['scanned_at'] = date('Y-m-d H:i:s');
}

// Also update the old 'scanned' column for backward compatibility (optional)
if ($booking['scan_count'] >= 1 && $booking['scanned'] == 0) {
    mysqli_query($con, "UPDATE booking SET scanned = 1 WHERE id = $booking_id");
}

$event_date = date('F j, Y', strtotime($booking['date']));
$scan_time = $booking['scanned_at'] ? date('g:i A', strtotime($booking['scanned_at'])) : '';

// Generate QR code for this page
include_once('phpqrcode/qrlib.php');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$current_url = $protocol . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$qr_temp = tempnam(sys_get_temp_dir(), 'QR_');
QRcode::png($current_url, $qr_temp, QR_ECLEVEL_L, 6);
$qr_base64 = base64_encode(file_get_contents($qr_temp));
unlink($qr_temp);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Digital Invitation Card</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(145deg, #8b6b4d 0%, #5a3e2b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', 'Poppins', 'Georgia', serif;
            padding: 20px;
        }
        .invitation-card {
            max-width: 550px;
            width: 100%;
            background: #fffef7;
            border-radius: 32px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #c9a87b, #b88b4f);
            padding: 28px 20px;
            text-align: center;
            color: white;
        }
        .card-header h1 { font-size: 32px; letter-spacing: 2px; }
        .card-header p { font-size: 14px; opacity: 0.9; margin-top: 6px; }
        .icon-emoji { font-size: 48px; margin-bottom: 8px; }
        .card-body { padding: 32px 28px; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #e0cfb5;
        }
        .detail-label { font-weight: 600; color: #7a5a3a; font-size: 16px; }
        .detail-value { font-size: 18px; font-weight: 500; color: #2c221b; text-align: right; max-width: 60%; }
        .badge-status {
            margin-top: 20px;
            text-align: center;
            padding: 14px;
            border-radius: 60px;
            font-weight: bold;
            font-size: 18px;
        }
        .badge-valid { background: #e9f7e1; color: #2e6b2f; border-left: 4px solid #4caf50; }
        .badge-used { background: #ffe6e5; color: #b13b3b; border-left: 4px solid #f44336; }
        .warning-message {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            margin: 20px 0 10px;
            border-radius: 8px;
            font-size: 13px;
            color: #856404;
            text-align: center;
        }
        .venue-note {
            background: #faf5e8;
            border-radius: 20px;
            padding: 12px 18px;
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            color: #6b4c2c;
        }
        .qr-section { text-align: center; margin: 25px 0 10px; padding: 15px; background: #f9f5ee; border-radius: 16px; }
        .qr-section img { width: 180px; border-radius: 12px; border: 2px solid #d4b48c; padding: 6px; background: white; }
        .footer { background: #f7f1e2; padding: 16px; text-align: center; font-size: 12px; color: #aa8b65; border-top: 1px solid #e9dbc9; }
        @media (max-width: 480px) { .card-body { padding: 22px 18px; } .detail-value { font-size: 16px; } }
    </style>
</head>
<body>
<div class="invitation-card">
    <div class="card-header">
        <div class="icon-emoji">✨🎟️✨</div>
        <h1>YOU'RE INVITED</h1>
        <p>Classic Events - Entry Pass</p>
    </div>
    <div class="card-body">
        <div class="detail-row">
            <span class="detail-label">🎟️ Booking ID</span>
            <span class="detail-value">#<?php echo htmlspecialchars($booking['id']); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">👑 Host Name</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['nm']); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">🎭 Event Theme</span>
            <span class="detail-value"><?php echo htmlspecialchars($booking['thm_nm']); ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">📅 Event Date</span>
            <span class="detail-value"><?php echo $event_date; ?></span>
        </div>
        <div class="detail-row">
            <span class="detail-label">📍 Event Location</span>
            <span class="detail-value">Classic Events Hall, Kathmandu</span>
        </div>

        <div class="venue-note">
            ⏰ Gates open 1 hour before event | 📞 +977-9861522230
        </div>

        <?php if ($can_enter): ?>
            <div class="badge-status badge-valid">
                ✅ VALID ENTRY – Scan #<?php echo $booking['scan_count']; ?> of 2
            </div>
            <?php if ($scan_time): ?>
                <div class="scan-time" style="text-align:center; margin-top:5px;">
                    Last scan time: <?php echo $scan_time; ?>
                </div>
            <?php endif; ?>
            <div class="warning-message">
                ⚠️ <strong>Two-time use only.</strong> This ticket allows up to 2 entries.<br>
                Remaining entries: <strong><?php echo (2 - $booking['scan_count']); ?></strong>
            </div>
        <?php else: ?>
            <div class="badge-status badge-used">
                ⚠️ TICKET EXHAUSTED – This ticket has been scanned <?php echo $booking['scan_count']; ?> times.<br>
                No further entry allowed.
            </div>
            <div class="warning-message" style="background:#f8d7da; border-left-color:#f44336; color:#721c24;">
                This invitation has already been used the maximum number of times (2).
            </div>
        <?php endif; ?>

        <div class="qr-section">
            <h3>📱 Scan for Entry</h3>
            <img src="data:image/png;base64,<?php echo $qr_base64; ?>" alt="Entry QR Code">
            <p style="font-size: 11px; margin-top: 8px; color: #9b7e5f;">Staff will scan this code to validate your entry.</p>
        </div>
    </div>
    <div class="footer">
        Please present this screen at the entrance.<br>
        This is a digital invitation – no physical ticket required.
    </div>
</div>
</body>
</html>