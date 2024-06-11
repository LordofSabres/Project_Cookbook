<?php
// Start the session
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Include menubar.php at the top of the page -->
    <?php include 'menubar.php'; ?>
</head>

<body>
    <?php
    // Assume you have a database connection stored in $conn
    require './connect.php';
    $conn = Connect();

    // Check if the user is logged in
    if (isset($_SESSION['UserID'], $_SESSION['FirstName'], $_SESSION['LastName'], $_SESSION['Email'])) {
        $UserID = $_SESSION['UserID'];
        $FirstName = $_SESSION['FirstName'];
        $LastName = $_SESSION['LastName'];
        $Email = $_SESSION['Email'];
        echo "<h2>Welcome, $FirstName $LastName!</h2>";
        echo "<p>User ID: $UserID</p>";
        // Fetch recipes from the Recipes table with ingredients, quantities, measurements, and equipment
        $sql = "SELECT R.RecipeID, R.Name,
                GROUP_CONCAT(CONCAT(RI.Quantity, ' ', RI.Unit, ' of ', I.Name) SEPARATOR '\n') AS Ingredients,
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
            echo '<table class="table">';
            echo '<thead><tr><th>Name</th><th>Ingredients</th><th>Equipment</th><th>Instructions</th><th>Actions</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                $id = $row["RecipeID"];
                echo '<tr>';
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td>' . nl2br($row['Ingredients']) . '</td>';
                echo '<td>' . $row['Equipment'] . '</td>';
                echo '<td>' . $row['Instructions'] . '</td>';
                echo '<td>
                        <a href="editrecipe.php?RecipeID=' . $id . '" class="btn btn-secondary">Edit</a>
                        <a href="deleterecipe.php?RecipeID=' . $id . '" class="btn btn-danger">Delete</a>
                    </td>';
                echo '</tr>';
            }
            echo '</tbody></table>';
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
    <button onclick="redirectToMealGenerator()">What Can I Cook?</button>

    <!-- Footer -->
    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="web_background.js" type="text/javascript"></script>
    <!-- END OF SCRIPTS -->

</body>

</html>
