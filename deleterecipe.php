<?php
// Assume you have a database connection stored in $conn
require './connect.php';
$conn = Connect();

session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get user ID from the session
$userID = $_SESSION['UserID'];

// Check if the RecipeID is provided in the URL
if (isset($_GET['RecipeID'])) {
    $RecipeID = $_GET['RecipeID'];

    // Delete associated records from RecipeIngredients and RecipeEquipment tables
    $deleteIngredientsQuery = "DELETE FROM RecipeIngredients WHERE RecipeID = $RecipeID";
    $deleteEquipmentQuery = "DELETE FROM RecipeEquipment WHERE RecipeID = $RecipeID";

    if ($conn->query($deleteIngredientsQuery) && $conn->query($deleteEquipmentQuery)) {
        // Delete the recipe from the Recipes table
        $deleteRecipeQuery = "DELETE FROM Recipes WHERE RecipeID = $RecipeID";

        if ($conn->query($deleteRecipeQuery)) {
            echo "Recipe deleted successfully.";
             
        } else {
            echo "Error deleting recipe: " . $conn->error;
        }
    } else {
        echo "Error deleting associated records: " . $conn->error;
    }
} else {
    echo "RecipeID not provided.";
}

$conn->close();

header("location: main.php");
exit;
?>