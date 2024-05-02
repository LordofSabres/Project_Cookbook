<?php
require './connect.php';
$conn = Connect();

session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Check if the request method is GET and 'ingredientID' is set in the URL parameter
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ingredientID'])) {
    // Get the user ID from the session
    $userID = $_SESSION['UserID'];

    // Retrieve 'ingredientID' from the URL parameter ($_GET)
    $ingredientID = $_GET['ingredientID'];

    // Prepare and execute the delete query
    $deleteQuery = "DELETE FROM InventoryIngredients WHERE UserID = ? AND IngredientID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("ii", $userID, $ingredientID);
    
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
