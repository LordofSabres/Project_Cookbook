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
    $inventoryType = $conn->real_escape_string($_POST["inventoryType"]);
    $itemNamesInput = $_POST["itemName"]; // Input is an array

    // Initialize arrays to track duplicate items and new items added
    $existingItems = [];
    $newItems = [];

    // Prepare statement for inserting new items into Ingredients or Equipment table
    $insertItemStmt = $conn->prepare("INSERT INTO Ingredients (Name) VALUES (?)");
    if ($inventoryType === "Equipment") {
        $insertItemStmt = $conn->prepare("INSERT INTO Equipment (Name) VALUES (?)");
    }

    // Prepare statement for inserting new items into InventoryIngredients or InventoryEquipment table
    $insertInventoryStmt = $conn->prepare("INSERT INTO InventoryIngredients (UserID, IngredientID) VALUES (?, ?)");
    if ($inventoryType === "Equipment") {
        $insertInventoryStmt = $conn->prepare("INSERT INTO InventoryEquipment (UserID, EquipmentID) VALUES (?, ?)");
    }

    // Insert entries into InventoryIngredients or InventoryEquipment based on inventoryType
    foreach ($itemNamesInput as $itemNameEntry) {
        // Split the entry into individual items
        $itemNames = array_map('trim', explode(',', $itemNameEntry));

        foreach ($itemNames as $itemName) {
            $itemName = trim($itemName); // Remove leading/trailing whitespace

            // Convert item name to lowercase for storage in the database
            $itemNameLower = strtolower($itemName);

            // Convert item name to uppercase for display
            $itemNameUpper = strtoupper($itemName);

            // Check if the item already exists in the user's inventory
            $checkQuery = "SELECT COUNT(*) AS count FROM InventoryIngredients WHERE UserID = ? AND IngredientID = (SELECT IngredientID FROM Ingredients WHERE LOWER(Name) = ?)";
            if ($inventoryType === "Equipment") {
                $checkQuery = "SELECT COUNT(*) AS count FROM InventoryEquipment WHERE UserID = ? AND EquipmentID = (SELECT EquipmentID FROM Equipment WHERE LOWER(Name) = ?)";
            }
            $checkStmt = $conn->prepare($checkQuery);
            $checkStmt->bind_param("is", $userID, $itemNameLower);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $checkRow = $checkResult->fetch_assoc();
            $count = $checkRow['count'];
            $checkStmt->close();

            if ($count > 0) {
                // Item already exists in the user's inventory, add it to the existingItems array
                $existingItems[] = $itemNameUpper;
                continue; // Skip inserting duplicate items
            }

            // Check if the item exists in Ingredients or Equipment table
            $itemID = null;
            $checkItemQuery = "SELECT IngredientID FROM Ingredients WHERE LOWER(Name) = ?";
            if ($inventoryType === "Equipment") {
                $checkItemQuery = "SELECT EquipmentID FROM Equipment WHERE LOWER(Name) = ?";
            }
            $checkItemStmt = $conn->prepare($checkItemQuery);
            $checkItemStmt->bind_param("s", $itemNameLower);
            $checkItemStmt->execute();
            $checkResult = $checkItemStmt->get_result();

            if ($checkResult->num_rows == 0) {
                // Item does not exist in Ingredients or Equipment table, insert it
                $insertItemStmt->bind_param("s", $itemNameLower);
                $insertItemStmt->execute();
                $newItems[] = $itemNameUpper; // Track new items added
                $itemID = $insertItemStmt->insert_id; // Get the generated ID
            } else {
                // Item exists in Ingredients or Equipment table, get its ID
                $row = $checkResult->fetch_assoc();
                $itemID = $row["IngredientID"];
                if ($inventoryType === "Equipment") {
                    $itemID = $row["EquipmentID"];
                }
            }

            $checkItemStmt->close();

            // Insert into InventoryIngredients or InventoryEquipment
            $insertInventoryStmt->bind_param("ii", $userID, $itemID);
            $insertInventoryStmt->execute();
        }
    }

    // Display a message for duplicate items
    if (!empty($existingItems)) {
        echo "The following items were skipped as they already exist in the inventory: " . implode(", ", $existingItems) . "<br>";
    }

    // Display a message for new items added
    if (!empty($newItems)) {
        echo "The following new items were added to the $inventoryType table: " . implode(", ", $newItems) . "<br>";
    }

    // Successful insertion
    echo "Inventory updated successfully.";
    $conn->close();
    header("location: inventory.php"); // Redirect to the inventory page
    exit;
}
?>
