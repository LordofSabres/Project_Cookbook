<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <?php
    // Include menubar.php at the top of the page
    include 'menubar.php';
    ?>

</head>

<body>

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

// Function to capitalize the first letter of a string
function capitalizeFirstLetter($str) {
    return ucfirst(strtolower($str));
}

// Fetch ingredients from the InventoryIngredients table
$ingredientQuery = "SELECT I.Name, I.IngredientID
                    FROM InventoryIngredients INV
                    JOIN Ingredients I ON INV.IngredientID = I.IngredientID
                    WHERE INV.UserID = $userID";
$ingredientResult = $conn->query($ingredientQuery);

// Fetch equipment from the InventoryEquipment table
$equipmentQuery = "SELECT E.Name, E.EquipmentID
                    FROM InventoryEquipment INV
                    JOIN Equipment E ON INV.EquipmentID = E.EquipmentID
                    WHERE INV.UserID = $userID";
$equipmentResult = $conn->query($equipmentQuery);

// Display ingredients
echo "<h2>Ingredients Inventory</h2>";
if ($ingredientResult->num_rows > 0) {
    echo "<ul>";
    while ($row = $ingredientResult->fetch_assoc()) {
        echo "<li>" . capitalizeFirstLetter($row['Name']) . " 
        <button onclick=\"if(confirm('Are you sure you want to delete {$row['Name']}?')) window.location.href='delete_ingredient.php?ingredientID={$row['IngredientID']}'\">Delete</button></li>";
    }
    echo "</ul>";
} else {
    echo "No ingredients in inventory.";
}

// Display equipment
echo "<h2>Equipment Inventory</h2>";
if ($equipmentResult->num_rows > 0) {
    echo "<ul>";
    while ($row = $equipmentResult->fetch_assoc()) {
        echo "<li>" . capitalizeFirstLetter($row['Name']) . " 
        <button onclick=\"if(confirm('Are you sure you want to delete {$row['Name']}?')) window.location.href='delete_equipment.php?equipmentID={$row['EquipmentID']}'\">Delete</button></li>";
    }
    echo "</ul>";
} else {
    echo "No equipment in inventory.";
}

$conn->close();

?>

<br><br>
<button onclick="redirectToAddInventory()">Add to Inventory</button>

<?php include 'footer.php'; ?>


</body>
</html>
