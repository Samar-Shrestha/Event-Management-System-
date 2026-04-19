<?php
// This file checks if the selected themes are available on a given date
// Called via AJAX from cart.php
// Returns "available" only if ALL themes are free on that date.

include('Database/connect.php');
header('Content-Type: text/plain');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_POST['date']) || !isset($_POST['theme_names'])) {
    echo "Missing required data";
    exit();
}

$date = mysqli_real_escape_string($con, $_POST['date']);
$theme_names_raw = $_POST['theme_names'];
$theme_names = is_array($theme_names_raw) ? $theme_names_raw : json_decode($theme_names_raw, true);

if (!is_array($theme_names) || empty($theme_names)) {
    echo "Invalid theme list";
    exit();
}

$unavailable_themes = [];

foreach ($theme_names as $theme_name) {
    $theme_name = mysqli_real_escape_string($con, $theme_name);
    // Check if this specific theme is already booked on this date (pending or completed)
    $check = mysqli_query($con, "SELECT id FROM booking WHERE thm_nm = '$theme_name' AND date = '$date' AND payment_status IN ('pending','completed') LIMIT 1");
    if ($check && mysqli_num_rows($check) > 0) {
        $unavailable_themes[] = $theme_name;
    }
}

if (empty($unavailable_themes)) {
    echo "available";
} else {
    $booked_list = implode(", ", $unavailable_themes);
    echo "The following themes are already booked on $date: " . $booked_list;
}
?>