<?php
session_start();
include('Database/connect.php');
include("header.php");

// Check if user is logged in (you need to implement this)
if(!isset($_SESSION['user_email'])) {
    echo "<script>alert('Please login to view your bookings');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

$user_email = $_SESSION['user_email'];

// Get user's bookings
$query = "SELECT * FROM booking WHERE email='$user_email' ORDER BY id DESC";
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Classic Event</title>
    <style>
        .booking-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: white;
        }
        .status-completed {
            color: #28a745;
            font-weight: bold;
        }
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        .booking-img {
            max-width: 200px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="w3ls-hdg" align="center">My Bookings</h2>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($booking = mysqli_fetch_assoc($result)): ?>
                <div class="booking-card">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="./images/<?php echo $booking['theme']; ?>" class="booking-img" alt="Theme Image">
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo $booking['thm_nm']; ?></h4>
                            <p><strong>Booking Date:</strong> <?php echo $booking['date']; ?></p>
                            <p><strong>Amount:</strong> Rs. <?php echo number_format($booking['price']); ?></p>
                            <p><strong>Payment Status:</strong> 
                                <span class="status-<?php echo $booking['payment_status']; ?>">
                                    <?php echo strtoupper($booking['payment_status']); ?>
                                </span>
                            </p>
                            <?php if($booking['payment_status'] == 'completed'): ?>
                                <p><strong>Payment Date:</strong> <?php echo $booking['payment_date']; ?></p>
                                <p><strong>Transaction ID:</strong> <?php echo $booking['transaction_id']; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info">
                <p>You have no bookings yet. <a href="gallery.php">Browse themes</a> to get started!</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php include("footer.php"); ?>