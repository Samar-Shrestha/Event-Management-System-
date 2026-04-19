<?php
session_start();
include('Database/connect.php');

// Check if user is logged in
if (!isset($_SESSION['uname'])) {
    echo "<script>alert('Please login to view your bookings'); window.location='login.php';</script>";
    exit();
}

$username = mysqli_real_escape_string($con, $_SESSION['uname']);

// Get user details from registration
$user_query = mysqli_query($con, "SELECT nm, email FROM registration WHERE unm='$username' LIMIT 1");
if (!$user_query || mysqli_num_rows($user_query) == 0) {
    include("header.php");
    echo "<div class='container'><div class='alert alert-danger'>User not found. Please contact support.</div></div>";
    include("footer.php");
    exit();
}

$user             = mysqli_fetch_assoc($user_query);
$registered_email = mysqli_real_escape_string($con, $user['email']);

// Also check session email in case user typed a different email during booking
$extra_email = '';
if (!empty($_SESSION['booking_info']['customer_email'])) {
    $extra_email = mysqli_real_escape_string($con, $_SESSION['booking_info']['customer_email']);
}

if (!empty($extra_email) && $extra_email !== $registered_email) {
    $email_condition = "email IN ('$registered_email', '$extra_email')";
} else {
    $email_condition = "email = '$registered_email'";
}

// ── Handle hide booking (store in session, no DB delete) ─────────────────────
if (isset($_GET['hide_id'])) {
    $hide_id = (int)$_GET['hide_id'];
    if (!isset($_SESSION['hidden_bookings'])) {
        $_SESSION['hidden_bookings'] = [];
    }
    if (!in_array($hide_id, $_SESSION['hidden_bookings'])) {
        $_SESSION['hidden_bookings'][] = $hide_id;
    }
    header("Location: my_bookings.php");
    exit();
}

// ── Auto-expire: mark past bookings as expired in DB ─────────────────────────
mysqli_query($con, "
    UPDATE booking
    SET payment_status = 'expired'
    WHERE payment_status = 'completed'
    AND date < CURDATE()
    AND ($email_condition)
");

// ── Fetch all bookings for this user ─────────────────────────────────────────
$query  = "SELECT * FROM booking
           WHERE payment_status IN ('completed', 'expired')
           AND $email_condition
           ORDER BY date DESC";
$result = mysqli_query($con, $query);

$today           = date('Y-m-d');
$hidden_bookings = isset($_SESSION['hidden_bookings']) ? $_SESSION['hidden_bookings'] : [];

// Separate into active and expired, skip hidden ones
$active_bookings  = [];
$expired_bookings = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($booking = mysqli_fetch_assoc($result)) {
        if (in_array((int)$booking['id'], $hidden_bookings)) {
            continue; // skip hidden
        }
        if ($booking['payment_status'] === 'expired' || $booking['date'] < $today) {
            $expired_bookings[] = $booking;
        } else {
            $active_bookings[] = $booking;
        }
    }
}

// Now safe to include header (no redirects after this point)
include("header.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Classic Event</title>
    <style>
        .bookings-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px 15px 40px;
        }
        .section-title {
            font-size: 20px;
            font-weight: 600;
            margin: 30px 0 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #eee;
        }
        .section-title.active-title  { color: #1a7a3c; border-color: #28a745; }
        .section-title.expired-title { color: #888;    border-color: #ccc; }

        .booking-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 18px;
            background: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.09);
        }
        .booking-card.expired-card {
            background: #fafafa;
            border-color: #e0e0e0;
            opacity: 0.82;
        }
        .booking-img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }
        .booking-img.expired-img {
            filter: grayscale(70%);
        }
        .badge-completed {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .badge-expired {
            display: inline-block;
            background: #e2e3e5;
            color: #555;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .btn-invitation {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 18px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-invitation:hover {
            background: linear-gradient(135deg, #5a67d8, #6b46a0);
            color: white;
            text-decoration: none;
        }
        .btn-hide {
            background: white;
            color: #d9534f;
            border: 1px solid #d9534f;
            padding: 7px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 10px;
            margin-left: 8px;
            text-decoration: none;
            display: inline-block;
            transition: 0.2s;
        }
        .btn-hide:hover {
            background: #d9534f;
            color: white;
            text-decoration: none;
        }
        .empty-state {
            text-align: center;
            padding: 35px 20px;
            color: #888;
            background: #f9f9f9;
            border-radius: 10px;
            border: 1px dashed #ddd;
        }
        .empty-state a { color: #007bff; }

        @media (max-width: 768px) {
            .btn-hide { margin-left: 0; margin-top: 8px; }
        }
    </style>
</head>
<body>
<div class="bookings-wrapper">
    <h2 class="w3ls-hdg" align="center">&#128203; My Bookings</h2>
    <hr>

    <!-- ── ACTIVE BOOKINGS ───────────────────────────────────────────────── -->
    <div class="section-title active-title">&#9989; Upcoming Bookings</div>

    <?php if (!empty($active_bookings)): ?>
        <?php foreach ($active_bookings as $booking): ?>
            <div class="booking-card">
                <div class="row">
                    <div class="col-md-4">
                        <img src="./images/<?php echo htmlspecialchars($booking['theme']); ?>"
                             class="booking-img" alt="Theme Image">
                    </div>
                    <div class="col-md-8">
                        <h4><?php echo htmlspecialchars($booking['thm_nm']); ?></h4>
                        <p><strong>Booking ID:</strong> #<?php echo (int)$booking['id']; ?></p>
                        <p><strong>Event Date:</strong> <?php echo date('F j, Y', strtotime($booking['date'])); ?></p>
                        <p><strong>Amount:</strong> NPR <?php echo number_format($booking['price']); ?></p>
                        <p><strong>Status:</strong> <span class="badge-completed">CONFIRMED</span></p>
                        <?php if (!empty($booking['payment_date'])): ?>
                            <p><strong>Payment Date:</strong> <?php echo htmlspecialchars($booking['payment_date']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($booking['transaction_id'])): ?>
                            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($booking['transaction_id']); ?></p>
                        <?php endif; ?>
                        <div style="margin-top:8px;">
                            <a href="verify_booking.php?booking_id=<?php echo (int)$booking['id']; ?>"
                               class="btn-invitation" target="_blank">
                                &#127903;&#65039; View Invitation Card
                            </a>
                            <a href="my_bookings.php?hide_id=<?php echo (int)$booking['id']; ?>"
                               class="btn-hide"
                               onclick="return confirm('Remove this booking from your list?')">
                                &#10006; Remove
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p style="font-size:16px;">You have no upcoming bookings.</p>
            <p><a href="gallery.php">Browse themes</a> to book your next event!</p>
        </div>
    <?php endif; ?>

    <!-- ── EXPIRED BOOKINGS ──────────────────────────────────────────────── -->
    <div class="section-title expired-title" style="margin-top:40px;">&#128337; Past / Expired Bookings</div>

    <?php if (!empty($expired_bookings)): ?>
        <?php foreach ($expired_bookings as $booking): ?>
            <div class="booking-card expired-card">
                <div class="row">
                    <div class="col-md-4">
                        <img src="./images/<?php echo htmlspecialchars($booking['theme']); ?>"
                             class="booking-img expired-img" alt="Theme Image">
                    </div>
                    <div class="col-md-8">
                        <h4 style="color:#777;"><?php echo htmlspecialchars($booking['thm_nm']); ?></h4>
                        <p><strong>Booking ID:</strong> #<?php echo (int)$booking['id']; ?></p>
                        <p><strong>Event Date:</strong> <?php echo date('F j, Y', strtotime($booking['date'])); ?></p>
                        <p><strong>Amount:</strong> NPR <?php echo number_format($booking['price']); ?></p>
                        <p><strong>Status:</strong> <span class="badge-expired">EXPIRED</span></p>
                        <?php if (!empty($booking['transaction_id'])): ?>
                            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($booking['transaction_id']); ?></p>
                        <?php endif; ?>
                        <div style="margin-top:8px;">
                            <a href="my_bookings.php?hide_id=<?php echo (int)$booking['id']; ?>"
                               class="btn-hide"
                               onclick="return confirm('Remove this booking from your list?')">
                                &#10006; Remove
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state">
            <p>No past bookings to show.</p>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
<?php include("footer.php"); ?>