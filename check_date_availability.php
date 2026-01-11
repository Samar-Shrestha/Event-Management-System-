<?php
// This file checks if a theme is available for booking on a specific date
// It's called via AJAX from booking.php

include('Database/connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['date']) && isset($_POST['theme_name']))
{
    // Get and sanitize input
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $theme_name = mysqli_real_escape_string($con, $_POST['theme_name']);
    
    // Check if this theme is already booked for this date
    $check = mysqli_query($con, "SELECT * FROM booking WHERE thm_nm='$theme_name' AND date='$date'");
    
    if(!$check)
    {
        // Database error
        echo "error";
    }
    else
    {
        if(mysqli_num_rows($check) > 0)
        {
            // Theme is already booked for this date
            echo "booked";
        }
        else
        {
            // Theme is available for this date
            echo "available";
        }
    }
}
else
{
    echo "error";
}

?>