<?php
// Assume you have a database connection stored in $conn
require './connect.php';
$conn = Connect();

// Check if the user input is provided via GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['term'])) {
    // Sanitize user input
    $term = $conn->real_escape_string($_GET['term']);

    // Query the database for ingredient or equipment names that match the user input
    $query = "SELECT Name FROM Ingredients WHERE Name LIKE '%$term%' UNION SELECT Name FROM Equipment WHERE Name LIKE '%$term%'";
    $result = $conn->query($query);

    // Array to store autocomplete suggestions
    $suggestions = array();

    if ($result) {
        // Fetch associative array
        while ($row = $result->fetch_assoc()) {
            // Add each ingredient or equipment name to the suggestions array
            $suggestions[] = $row['Name'];
        }
    }

    // Close the database connection
    $conn->close();

    // Encode the suggestions array as JSON and send it back to the frontend
    echo json_encode($suggestions);
} else {
    // If input is not provided or the request method is not GET, return an empty response
    echo json_encode(array());
}
?>
