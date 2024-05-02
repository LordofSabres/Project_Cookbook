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

// Process the form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract form data
    $recipeID = $conn->real_escape_string($_POST["RecipeID"]);
    $recipeName = $conn->real_escape_string($_POST["recipeName"]);
    $ingredientCategories = $_POST["ingredientCategory"];
    $ingredientNames = $_POST["ingredientName"];
    $ingredientQuantities = $_POST["ingredientQuantity"];
    $ingredientUnits = $_POST["ingredientMeasurement"];
    $equipmentNames = $_POST["equipmentName"];
    $status = $conn->real_escape_string($_POST["status"]);
    $instructions = $conn->real_escape_string($_POST["instructions"]);

    // Update recipe information in Recipes table
    $recipeUpdateQuery = "UPDATE Recipes SET Name='$recipeName', Status='$status', Instructions='$instructions' WHERE RecipeID=$recipeID";
    if ($conn->query($recipeUpdateQuery)) {
        // Delete existing recipe-ingredient and recipe-equipment associations
        $deleteIngredientsQuery = "DELETE FROM RecipeIngredients WHERE RecipeID=$recipeID";
        $conn->query($deleteIngredientsQuery);

        $deleteEquipmentQuery = "DELETE FROM RecipeEquipment WHERE RecipeID=$recipeID";
        $conn->query($deleteEquipmentQuery);

        // Process ingredients
        for ($i = 0; $i < count($ingredientNames); $i++) {
            // Sanitize user input
            $ingredientName = $conn->real_escape_string($ingredientNames[$i]);
            $ingredientCategory = $conn->real_escape_string($ingredientCategories[$i]);
            $ingredientQuantity = $conn->real_escape_string($ingredientQuantities[$i]);
            $ingredientUnit = $conn->real_escape_string($ingredientUnits[$i]);

            // Check if the ingredient already exists
            $ingredientCheckQuery = "SELECT IngredientID FROM Ingredients WHERE Name = '$ingredientName' AND Category = '$ingredientCategory'";
            $result = $conn->query($ingredientCheckQuery);

            if ($result->num_rows > 0) {
                // If the ingredient exists, get its ID and existing unit
                $row = $result->fetch_assoc();
                $ingredientID = $row['IngredientID'];
                
            } else {
                // If the ingredient doesn't exist, insert it into the Ingredients table
                $ingredientInsertQuery = "INSERT INTO Ingredients (Name, Category) VALUES ('$ingredientName', '$ingredientCategory')";
                $conn->query($ingredientInsertQuery);

                // Get the IngredientID of the inserted ingredient
                $ingredientID = $conn->insert_id;
            }

            // Insert into RecipeIngredients table
            $recipeIngredientInsertQuery = "INSERT INTO RecipeIngredients (RecipeID, IngredientID, Quantity, Unit) VALUES ($recipeID, $ingredientID, '$ingredientQuantity', '$ingredientUnit')";
            
            if (!$conn->query($recipeIngredientInsertQuery)) {
                echo "Error inserting recipe ingredients: " . $conn->error;
            }
        }

        // Process equipment
        for ($i = 0; $i < count($equipmentNames); $i++) {
            // Sanitize user input
            $equipmentName = $conn->real_escape_string($equipmentNames[$i]);

            // Check if the equipment already exists
            $equipmentCheckQuery = "SELECT EquipmentID FROM Equipment WHERE Name = '$equipmentName'";
            $result = $conn->query($equipmentCheckQuery);

            if ($result->num_rows > 0) {
                // If the equipment exists, get its ID
                $row = $result->fetch_assoc();
                $equipmentID = $row['EquipmentID'];
            } else {
                // If the equipment doesn't exist, insert it into the Equipment table
                $equipmentInsertQuery = "INSERT INTO Equipment (Name) VALUES ('$equipmentName')";
                $conn->query($equipmentInsertQuery);

                // Get the EquipmentID of the inserted equipment
                $equipmentID = $conn->insert_id;
            }

            // Insert into RecipeEquipment table
            $recipeEquipmentInsertQuery = "INSERT INTO RecipeEquipment (RecipeID, EquipmentID) VALUES ($recipeID, $equipmentID)";
            if (!$conn->query($recipeEquipmentInsertQuery)) {
                echo "Error inserting recipe equipment: " . $conn->error;
            }
        }

        // Successful update
        echo "Records updated successfully.";
        $conn->close();
        header("location: main.php");
        exit;
    } else {
        // Error in updating recipe
        echo "Error updating recipe: " . $conn->error;
    }
}
?>
