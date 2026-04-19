<?php
session_start();
include('Database/connect.php');
include("header.php");

// Check if user is logged in
if (!isset($_SESSION['uname'])) {
    echo "<script>alert('Please login to view your bookings'); window.location='login.php';</script>";
    exit();
}

$username = mysqli_real_escape_string($con, $_SESSION['uname']);

echo "<div class='container' style='background:#fff3cd;padding:15px;border-radius:8px;margin:20px 0;font-family:monospace;font-size:13px;'>";
echo "<strong>DEBUG INFO (remove this block once fixed)</strong><br><br>";
echo "Session username (unm): <b>" . htmlspecialchars($username) . "</b><br>";

// Get user from registration
$user_query = mysqli_query($con, "SELECT nm, email, unm FROM registration WHERE unm='$username' LIMIT 1");
if (!$user_query) {
    echo "Registration query failed: " . mysqli_error($con) . "<br>";
} elseif (mysqli_num_rows($user_query) == 0) {
    echo "<span style='color:red;'>No user found in registration table with unm = '$username'</span><br>";
} else {
    $user = mysqli_fetch_assoc($user_query);
    echo "Found in registration — nm: <b>" . htmlspecialchars($user['nm']) . "</b> | email: <b>" . htmlspecialchars($user['email']) . "</b><br>";
    $user_email = mysqli_real_escape_string($con, $user['email']);

    // Check what's in booking table for this email
    $check = mysqli_query($con, "SELECT id, nm, email, payment_status, date FROM booking WHERE email = '$user_email'");
    if (!$check) {
        echo "Booking query failed: " . mysqli_error($con) . "<br>";
    } else {
        $total = mysqli_num_rows($check);
        echo "Total bookings with email '$user_email': <b>$total</b><br>";
        if ($total > 0) {
            echo "<table border='1' cellpadding='5' style='margin-top:8px;background:white;'>";
            echo "<tr><th>ID</th><th>nm (in booking)</th><th>email (in booking)</th><th>payment_status</th><th>date</th></tr>";
            while ($row = mysqli_fetch_assoc($check)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['nm']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }

    // Also check ALL bookings regardless of email (last 10)
    $all = mysqli_query($con, "SELECT id, nm, email, payment_status FROM booking ORDER BY id DESC LIMIT 10");
    echo "<br>Last 10 rows in booking table (all users):<br>";
    if (mysqli_num_rows($all) > 0) {
        echo "<table border='1' cellpadding='5' style='background:white;'>";
        echo "<tr><th>ID</th><th>nm</th><th>email</th><th>payment_status</th></tr>";
        while ($row = mysqli_fetch_assoc($all)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($row['nm']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<span style='color:red;'>Booking table is empty.</span><br>";
    }
}
echo "</div>";
?>

<?php include("footer.php"); ?>