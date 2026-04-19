<?php
include_once("Database/connect.php");

header('Content-Type: application/json');

// Get search term
$searchTerm = isset($_GET['term']) ? mysqli_real_escape_string($con, $_GET['term']) : '';

if(strlen($searchTerm) < 2) {
    echo json_encode([]);
    exit;
}

$suggestions = [];

// Search in wedding table
$weddingQuery = "SELECT DISTINCT nm, location, 'Wedding' as type FROM wedding 
                 WHERE nm LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%' 
                 LIMIT 3";
$weddingResult = mysqli_query($con, $weddingQuery);
if($weddingResult) {
    while($row = mysqli_fetch_assoc($weddingResult)) {
        $suggestions[] = [
            'label' => $row['nm'] . ' - ' . $row['location'] . ' (' . $row['type'] . ')',
            'value' => $row['nm'],
            'type' => $row['type']
        ];
    }
}

// Search in birthday table
$birthdayQuery = "SELECT DISTINCT nm, location, 'Birthday' as type FROM birthday 
                  WHERE nm LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%' 
                  LIMIT 3";
$birthdayResult = mysqli_query($con, $birthdayQuery);
if($birthdayResult) {
    while($row = mysqli_fetch_assoc($birthdayResult)) {
        $suggestions[] = [
            'label' => $row['nm'] . ' - ' . $row['location'] . ' (' . $row['type'] . ')',
            'value' => $row['nm'],
            'type' => $row['type']
        ];
    }
}

// Search in anniversary table
$anniversaryQuery = "SELECT DISTINCT nm, location, 'Anniversary' as type FROM anniversary 
                     WHERE nm LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%' 
                     LIMIT 3";
$anniversaryResult = mysqli_query($con, $anniversaryQuery);
if($anniversaryResult) {
    while($row = mysqli_fetch_assoc($anniversaryResult)) {
        $suggestions[] = [
            'label' => $row['nm'] . ' - ' . $row['location'] . ' (' . $row['type'] . ')',
            'value' => $row['nm'],
            'type' => $row['type']
        ];
    }
}

// Search in otherevent table
$otherQuery = "SELECT DISTINCT nm, location, 'Entertainment' as type FROM otherevent 
               WHERE nm LIKE '%$searchTerm%' OR location LIKE '%$searchTerm%' 
               LIMIT 3";
$otherResult = mysqli_query($con, $otherQuery);
if($otherResult) {
    while($row = mysqli_fetch_assoc($otherResult)) {
        $suggestions[] = [
            'label' => $row['nm'] . ' - ' . $row['location'] . ' (' . $row['type'] . ')',
            'value' => $row['nm'],
            'type' => $row['type']
        ];
    }
}

// Limit to 10 suggestions
$suggestions = array_slice($suggestions, 0, 10);

echo json_encode($suggestions);
?>