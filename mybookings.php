<?php
session_start();
include('Database/connect.php');
include('session.php');
include('header.php');

// Get user's bookings
$user_email = $_SESSION['email'] ?? '';
$bookings = [];

if($user_email) {
    $query = "SELECT * FROM booking WHERE email = '" . mysqli_real_escape_string($con, $user_email) . "' ORDER BY date DESC";
    $result = mysqli_query($con, $query);
    
    while($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Classic Event</title>
    <style>
        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status.completed {
            background: #d4edda;
            color: #155724;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
    <h3 class='w3ls-hdg' align="center">MY BOOKINGS</h3>
    
    <?php if(empty($bookings)): ?>
        <div class="alert alert-info">
            <p>You have no bookings yet.</p>
            <a href="gallery.php" class="btn btn-primary">Browse Themes</a>
        </div>
    <?php else: ?>
        <?php foreach($bookings as $booking): ?>
            <div class="booking-card">
                <div class="row">
                    <div class="col-md-3">
                        <img src="./images/<?php echo htmlspecialchars($booking['theme']); ?>" class="img-fluid" style="max-height: 150px;">
                    </div>
                    <div class="col-md-9">
                        <h4><?php echo htmlspecialchars($booking['thm_nm']); ?></h4>
                        <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($booking['date']); ?></p>
                        <p><strong>Price:</strong> Rs. <?php echo number_format($booking['price']); ?></p>
                        <p><strong>Payment Status:</strong> 
                            <span class="status <?php echo htmlspecialchars($booking['payment_status']); ?>">
                                <?php echo strtoupper(htmlspecialchars($booking['payment_status'])); ?>
                            </span>
                        </p>
                        
                        <?php if($booking['payment_status'] == 'completed'): ?>
                            <p><strong>Payment ID:</strong> <?php echo htmlspecialchars($booking['payment_id']); ?></p>
                            <p><strong>Paid on:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['payment_date'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>

<?php include_once("footer.php"); ?>