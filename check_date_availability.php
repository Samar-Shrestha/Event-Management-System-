<?php
// This file checks if themes are available for booking on a specific date
// It's called via AJAX from cart.php

include('Database/connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['date']) && isset($_POST['theme_names']))
{
    // Get and sanitize input
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $theme_names = $_POST['theme_names']; // This is an array now
    
    $unavailable_themes = [];
    
    // Check each theme
    foreach($theme_names as $theme_name)
    {
        $theme_name = mysqli_real_escape_string($con, $theme_name);
        
        // Check if this theme is already booked for this date with completed payment
        $check = mysqli_query($con, "SELECT * FROM booking WHERE thm_nm='$theme_name' AND date='$date' AND payment_status='completed'");
        
        if(!$check)
        {
            // Database error
            echo "Database error: " . mysqli_error($con);
            exit();
        }
        
        if(mysqli_num_rows($check) > 0)
        {
            // Theme is already booked for this date
            $unavailable_themes[] = $theme_name;
        }
    }
    
    // Return result
    if(empty($unavailable_themes))
    {
        // All themes are available
        echo "available";
    }
    else
    {
        // Some themes are booked
        $booked_list = implode(", ", $unavailable_themes);
        echo "The following themes are already booked: " . $booked_list;
    }
}
else
{
    echo "Missing required data (date or theme_names)";
}
?>