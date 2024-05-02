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
    require './connect.php';
    $conn = Connect();

    session_start();

    if (!isset($_SESSION['UserID'])) {
        header("Location: login.php");
        exit();
    }

    echo "<h2>You can cook this with your inventory! Happy cooking!</h2>";


    // Fetch user's inventory ingredients
    $userID = $_SESSION['UserID'];
    $userInventoryQuery = "SELECT IngredientID FROM InventoryIngredients WHERE UserID = $userID";
    $userInventoryResult = $conn->query($userInventoryQuery);

    // Check if the query was successful
    if ($userInventoryResult) {
        // Extract ingredient IDs from the result
        $userInventory = [];
        while ($row = $userInventoryResult->fetch_assoc()) {
            $userInventory[] = $row['IngredientID'];
        }

        // Fetch recipes that can be made from user's inventory
        $recipesQuery = "SELECT R.RecipeID, R.Name,
                        GROUP_CONCAT(CONCAT(RI.Quantity, ' ', RI.Unit, ' of ', I.Name) SEPARATOR '<br>') AS Ingredients,
                        GROUP_CONCAT(DISTINCT E.Name) AS Equipment, R.Instructions
                        FROM Recipes R
                        LEFT JOIN RecipeIngredients RI ON R.RecipeID = RI.RecipeID
                        LEFT JOIN Ingredients I ON RI.IngredientID = I.IngredientID
                        LEFT JOIN RecipeEquipment RE ON R.RecipeID = RE.RecipeID
                        LEFT JOIN Equipment E ON RE.EquipmentID = E.EquipmentID
                        WHERE R.RecipeID NOT IN (
                            SELECT DISTINCT R.RecipeID
                            FROM Recipes R
                            LEFT JOIN RecipeIngredients RI ON R.RecipeID = RI.RecipeID
                            WHERE RI.IngredientID NOT IN (" . implode(',', $userInventory) . ")
                        )
                        GROUP BY R.RecipeID";
        $recipesResult = $conn->query($recipesQuery);

        // Check if the query was successful
        if ($recipesResult) {
            // Display recipes that can be made
            if ($recipesResult->num_rows > 0) {
                while ($row = $recipesResult->fetch_assoc()) {
                    $id = $row["RecipeID"];
                    echo '<div class="card m-2" style="width: 18rem;">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['Name'] . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">Ingredients</h6>';
                    echo '<p class="card-text">' . nl2br($row['Ingredients']) . '</p>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">Equipment</h6>';
                    echo '<p class="card-text">' . $row['Equipment'] . '</p>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">Instructions</h6>';
                    echo '<p class="card-text">' . $row['Instructions'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "No recipes can be made from your inventory.";
            }
        } else {
            echo "Error fetching recipes: " . $conn->error;
        }
    } else {
        echo "Error fetching user inventory: " . $conn->error;
    }

    $conn->close();
    ?>

    <button onclick="redirectToMain()">Return to Cookbook</button>

</body>

<footer>
    <?php include 'footer.php'; ?>
</footer>
