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

    // Check if the user is logged in
    session_start();

    if (isset($_SESSION['UserID'], $_SESSION['FirstName'], $_SESSION['LastName'], $_SESSION['Email'])) {
        $UserID = $_SESSION['UserID'];
        $FirstName = $_SESSION['FirstName'];
        $LastName = $_SESSION['LastName'];
        $Email = $_SESSION['Email'];

        echo "<h2>Welcome, $FirstName $LastName!</h2>";
        echo "<p>User ID: $UserID</p>";

        // Fetch recipes from the Recipes table with ingredients, quantities, measurements, and equipment
        $sql = "SELECT R.RecipeID, R.Name,
                GROUP_CONCAT(CONCAT(RI.Quantity, ' ', RI.Unit, ' of ', I.Name) SEPARATOR '<br>') AS Ingredients,
                GROUP_CONCAT(DISTINCT E.Name) AS Equipment, R.Instructions
                FROM Recipes R
                LEFT JOIN RecipeIngredients RI ON R.RecipeID = RI.RecipeID
                LEFT JOIN Ingredients I ON RI.IngredientID = I.IngredientID
                LEFT JOIN RecipeEquipment RE ON R.RecipeID = RE.RecipeID
                LEFT JOIN Equipment E ON RE.EquipmentID = E.EquipmentID
                WHERE R.OwnerID = $UserID
                GROUP BY R.RecipeID";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Loops through each recipe and create a card for it
            while ($row = $result->fetch_assoc()) {
                $id = $row["RecipeID"];
                echo '<div class="card m-2" style="width: 18rem;">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row['Name'] . '</h5>';
                echo '<h6 class="card-subtitle mb-2 text-muted">Ingredients</h6>';
                echo '<p class="card-text">' . nl2br($row['Ingredients']) . '</p>'; // Use nl2br to convert newlines to <br>
                echo '<h6 class="card-subtitle mb-2 text-muted">Equipment</h6>';
                echo '<p class="card-text">' . $row['Equipment'] . '</p>';
                echo '<h6 class="card-subtitle mb-2 text-muted">Instructions</h6>';
                echo '<p class="card-text">' . $row['Instructions'] . '</p>';
                echo '<a href="editrecipe.php?RecipeID=' . $id . '">Edit Recipe </a>';
                echo '<br>';
                echo '<a href="deleterecipe.php?RecipeID=' . $id . '">Delete Recipe</a>';
                echo '</div>
                    </div>';
            }
        } else {
            echo "0 results";
        }

        $conn->close();
    } else {
        header('Location: login.php');
        exit();
    }
    ?>

    <br><br>
    <!-- <button onclick="redirectToAddRecipe()">Add Recipe</button>
    <button onclick="redirectToAddInventory()">Add to Inventory</button>
    <button onclick="redirectToEditRecipe()">Edit Recipes</button>
    <button onclick="redirectToEditInventory()">Edit Inventory</button> -->
    <button onclick="redirectToMealGenerator()">What Can I Cook?</button>

    
</body>

<footer>
    <?php include 'footer.php'; ?>
</footer>

</html>
