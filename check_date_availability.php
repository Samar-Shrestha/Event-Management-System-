<?php
// This file checks if a specific date is available for booking (globally)
// It's called via AJAX from cart.php

include('Database/connect.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_POST['date']) && isset($_POST['theme_names']))
{
    // Get and sanitize input
    $date = mysqli_real_escape_string($con, $_POST['date']);
    $theme_names = $_POST['theme_names']; // This is an array (not used for global check, but kept for compatibility)

    // Check if ANY booking (pending or completed) exists for this date
    $check = mysqli_query($con, "SELECT id FROM booking WHERE date = '$date' AND payment_status IN ('pending','completed') LIMIT 1");
    
    if(!$check)
    {
        // Database error
        echo "Database error: " . mysqli_error($con);
        exit();
    }
    
    if(mysqli_num_rows($check) > 0)
    {
        // Date is already booked
        echo "The selected date is already booked. Please choose another date.";
    }
    else
    {
        // Date is free
        echo "available";
    }
}
else
{
    echo "Missing required data (date or theme_names)";
}
?>