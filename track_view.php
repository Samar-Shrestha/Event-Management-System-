<?php
include_once("Database/connect.php");

// Get parameters
$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$eventType = isset($_GET['type']) ? mysqli_real_escape_string($con, $_GET['type']) : '';

if($eventId > 0 && !empty($eventType)) {
    // Determine which table to update
    $table = '';
    switch($eventType) {
        case 'wedding':
            $table = 'wedding';
            break;
        case 'birthday':
            $table = 'birthday';
            break;
        case 'anniversary':
            $table = 'anniversary';
            break;
        case 'entertainment':
            $table = 'otherevent';
            break;
        default:
            exit;
    }
    
    // Update view count
    $updateQuery = "UPDATE $table SET view_count = view_count + 1 WHERE id = $eventId";
    mysqli_query($con, $updateQuery);
}
?>