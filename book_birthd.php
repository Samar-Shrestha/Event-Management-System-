<?php
session_start();
include("header.php");
include('Database/connect.php');

if (!isset($_SESSION['uname'])) {
    echo "<script>alert('Please login first to book an event');</script>";
    echo "<script>window.location.assign('login.php');</script>";
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = mysqli_real_escape_string($con, $_SESSION['uname']);
$event_type = 'birthday';

// ----- UNIQUE VIEW COUNT (database‑based) -----
if ($id > 0) {
    $check_view = mysqli_query($con, "SELECT id FROM user_event_views 
                                      WHERE user_id = '$user_id' 
                                      AND event_type = '$event_type' 
                                      AND event_id = $id");
    if ($check_view && mysqli_num_rows($check_view) == 0) {
        mysqli_query($con, "UPDATE birthday SET view_count = view_count + 1 WHERE id = $id");
        mysqli_query($con, "INSERT INTO user_event_views (user_id, event_type, event_id) 
                            VALUES ('$user_id', '$event_type', $id)");
    }
}
// --------------------------------------------

if (isset($_POST['submit'])) {
    $list = mysqli_query($con, "SELECT * FROM birthday WHERE id = $id");
    $q = mysqli_fetch_assoc($list);
    
    if ($q) {
        $image = $q['img'];
        $name = $q['nm'];
        $price = $q['price'];
        
        $session_id = session_id();
        $check_cart = mysqli_query($con, "SELECT * FROM temp WHERE user_session = '$session_id' AND nm = '$name'");
        
        if ($check_cart && mysqli_num_rows($check_cart) > 0) {
            echo "<script>alert('This theme is already in your cart!'); window.location='cart.php';</script>";
            exit();
        }
        
        // ✅ Insert WITHOUT id (auto‑increment)
        $qr1 = mysqli_query($con, "INSERT INTO temp (img, nm, price, user_session, event_type) 
                                   VALUES ('$image', '$name', $price, '$session_id', 'birthday')");
        
        if ($qr1) {
            echo "<script>window.location.assign('cart.php');</script>";
        } else {
            echo "<script>alert('Failed to add to cart. Error: " . mysqli_error($con) . "');</script>";
        }
    }
}

$list = mysqli_query($con, "SELECT * FROM birthday WHERE id = $id");
$q = mysqli_fetch_assoc($list);
?>
<div role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">    
                <a href="bday_gal.php" class="btn btn-default">← BACK TO BIRTHDAY</a>                    
            </div> 
            <form method="post">
                <div class="modal-body">
                    <img src="images/<?php echo $q['img']; ?>" alt="img" height="300" width="545"> 
                    <p>
                        <br/><strong>Theme Name:</strong> <?php echo $q['nm']; ?><br/>
                        <strong>Price:</strong> Rs. <?php echo number_format($q['price']); ?><br/>
                        <strong>Description:</strong> Exciting birthday celebration theme<br/><br/>
                        <input type='submit' name='submit' value='Book Now' class='btn btn-primary' style="background:#667eea; padding:10px 30px;"/>
                    </p>
                </div>
            </form>
        </div> 
    </div>
</div>
<br/><br><br>
<?php include("footer.php"); ?>