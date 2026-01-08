<?php
include('Database/connect.php');

if(isset($_POST['date']) && isset($_POST['theme_names'])) {
    $date = mysqli_real_escape_string($con, $_POST['date']);
    
    if(is_array($_POST['theme_names'])) {
        $theme_names = $_POST['theme_names'];
        $unavailable = [];
        
        foreach($theme_names as $theme_name) {
            $theme_name_clean = mysqli_real_escape_string($con, $theme_name);
            $check_booking = mysqli_query($con, "SELECT * FROM booking WHERE thm_nm='$theme_name_clean' AND date='$date' AND payment_status='completed'");
            
            if(mysqli_num_rows($check_booking) > 0) {
                $unavailable[] = $theme_name;
            }
        }
        
        if(empty($unavailable)) {
            echo "available";
        } else {
            echo "The following themes are not available: " . implode(", ", $unavailable);
        }
    } else {
        $theme_name = mysqli_real_escape_string($con, $_POST['theme_names']);
        $check_booking = mysqli_query($con, "SELECT * FROM booking WHERE thm_nm='$theme_name' AND date='$date' AND payment_status='completed'");
        
        if(mysqli_num_rows($check_booking) > 0) {
            echo "not_available";
        } else {
            echo "available";
        }
    }
}
?>