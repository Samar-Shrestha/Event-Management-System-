<?php
session_start();
include("header.php");
include('Database/connect.php');

if (!isset($_SESSION['uname'])) {
    echo "<script>alert('Please login first'); window.location='login.php';</script>";
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user_id = mysqli_real_escape_string($con, $_SESSION['uname']);
$event_type = 'wedding';

// ----- UNIQUE VIEW COUNT (database‑based) -----
if ($id > 0) {
    $check_view = mysqli_query($con, "SELECT id FROM user_event_views 
                                      WHERE user_id = '$user_id' 
                                      AND event_type = '$event_type' 
                                      AND event_id = $id");
    if ($check_view && mysqli_num_rows($check_view) == 0) {
        mysqli_query($con, "UPDATE wedding SET view_count = view_count + 1 WHERE id = $id");
        mysqli_query($con, "INSERT INTO user_event_views (user_id, event_type, event_id) 
                            VALUES ('$user_id', '$event_type', $id)");
    }
}
// --------------------------------------------

if (isset($_POST['submit'])) {
    $res = mysqli_query($con, "SELECT * FROM wedding WHERE id = $id");
    $item = mysqli_fetch_assoc($res);
    if ($item) {
        $session_id = session_id();
        $check = mysqli_query($con, "SELECT * FROM temp WHERE user_session = '$session_id' AND nm = '{$item['nm']}'");
        if ($check && mysqli_num_rows($check) > 0) {
            echo "<script>alert('This theme is already in your cart!'); window.location='cart.php';</script>";
            exit();
        }
        
        // ✅ Insert WITHOUT id (auto‑increment)
        $insert = "INSERT INTO temp (img, nm, price, user_session, event_type) 
                   VALUES ('{$item['img']}', '{$item['nm']}', {$item['price']}, '$session_id', 'wedding')";
        
        if (mysqli_query($con, $insert)) {
            echo "<script>window.location='cart.php';</script>";
        } else {
            echo "<script>alert('Failed to add to cart: " . mysqli_error($con) . "');</script>";
        }
    }
}

$res = mysqli_query($con, "SELECT * FROM wedding WHERE id = $id");
$item = mysqli_fetch_assoc($res);
if (!$item) {
    echo "<script>alert('Event not found'); window.location='gallery.php';</script>";
    exit();
}
?>
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <a href="gallery.php" class="btn btn-default">← BACK TO WEDDING</a>
        </div>
        <form method="post">
            <div class="modal-body">
                <img src="images/<?php echo $item['img']; ?>" height="300" width="545">
                <p>
                    <strong><?php echo $item['nm']; ?></strong><br>
                    Price: Rs. <?php echo number_format($item['price']); ?><br>
                    <input type="submit" name="submit" value="Book Now" class="btn btn-primary" style="background:#667eea; padding:10px 30px;">
                </p>
            </div>
        </form>
    </div>
</div>
<?php include("footer.php"); ?>