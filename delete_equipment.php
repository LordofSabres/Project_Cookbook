<?php
require './connect.php';
$conn = Connect();

session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Check if the request method is GET and 'equipmentID' is set in the URL parameter
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['equipmentID'])) {
    // Get the user ID from the session
    $userID = $_SESSION['UserID'];

    // Retrieve 'equipmentID' from the URL parameter ($_GET)
    $equipmentID = $_GET['equipmentID'];

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM InventoryEquipment WHERE UserID = ? AND EquipmentID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $userID, $equipmentID);
    
    if ($deleteStmt->execute()) {
        echo "Item deleted successfully.";
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error deleting item: " . $conn->error;
    }

    // Close the prepared statement
    $deleteStmt->close();
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
?>
