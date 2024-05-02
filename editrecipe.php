<!DOCTYPE html>
<html>

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Jquery stuff -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Project Cookbook</title>
    <script src="web_background.js" type="text/javascript"></script>
</head>

<body>

    <?php
    // Assume you have a database connection stored in $conn
    require './connect.php';
    $conn = Connect();

    // Check if the RecipeID is provided in the URL
    if (isset($_GET['RecipeID'])) {
        $RecipeID = $_GET['RecipeID'];

        // Fetch recipe details
        $sql = "SELECT * FROM Recipes WHERE RecipeID = $RecipeID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Retrieve recipe details
            $recipeName = $row['Name'];
            $status = $row['Status'];
            $instructions = $row['Instructions'];

            // Fetch associated ingredients, quantities, measurements, categories, and equipment
            // Adjust the queries based on your database structure
            $ingredientSql = "SELECT I.Name, RI.Quantity, RI.Unit AS MeasurementType, I.Category
                             FROM Ingredients I
                             INNER JOIN RecipeIngredients RI ON I.IngredientID = RI.IngredientID
                             WHERE RI.RecipeID = $RecipeID";
            $ingredientResult = $conn->query($ingredientSql);

            $equipmentSql = "SELECT E.Name
                             FROM Equipment E
                             INNER JOIN RecipeEquipment RE ON E.EquipmentID = RE.EquipmentID
                             WHERE RE.RecipeID = $RecipeID";
            $equipmentResult = $conn->query($equipmentSql);
        } else {
            echo "Recipe not found.";
            exit();
        }
    } else {
        echo "RecipeID not provided.";
        exit();
    }
    ?>

    <h1>Edit Recipe</h1>

    <form action="process_editrecipe.php" method="post">
        <input type="hidden" name="RecipeID" value="<?php echo $RecipeID; ?>">
        <label for="recipeName">Recipe Name:</label>
        <input type="text" id="recipeName" name="recipeName" value="<?php echo $recipeName; ?>" required>

        <br><br>

        <!-- Add ingredient and equipment fields based on fetched data -->
        <div id="ingredients">
            <label>Ingredients (One per line):</label>
            <?php
            while ($ingredientRow = $ingredientResult->fetch_assoc()) {
                echo '<div class="ingredient">
                        <select name="ingredientCategory[]" required>
                            <option value="Dairy" ' . ($ingredientRow['Category'] == 'Dairy' ? 'selected' : '') . '>Dairy</option>
                            <option value="Fruits" ' . ($ingredientRow['Category'] == 'Fruits' ? 'selected' : '') . '>Fruits</option>
                            <option value="Vegetables" ' . ($ingredientRow['Category'] == 'Vegetables' ? 'selected' : '') . '>Vegetables</option>
                            <option value="Carbs" ' . ($ingredientRow['Category'] == 'Carbs' ? 'selected' : '') . '>Carbs</option>
                            <option value="Preserves" ' . ($ingredientRow['Category'] == 'Preserves' ? 'selected' : '') . '>Preserves</option>
                            <option value="Protein" ' . ($ingredientRow['Category'] == 'Protein' ? 'selected' : '') . '>Protein</option>
                            <option value="Condiments" ' . ($ingredientRow['Category'] == 'Condiments' ? 'selected' : '') . '>Condiments</option>
                            <option value="Spices" ' . ($ingredientRow['Category'] == 'Spices' ? 'selected' : '') . '>Spices</option>
                        </select>
                        <input type="text" name="ingredientName[]" value="' . $ingredientRow['Name'] . '" required>
                        <input type="text" name="ingredientQuantity[]" value="' . $ingredientRow['Quantity'] . '" required>
                        <select name="ingredientMeasurement[]" required>
                            <option value="tsp" ' . ($ingredientRow['MeasurementType'] == 'tsp' ? 'selected' : '') . '>tsp</option>
                            <option value="tbsp" ' . ($ingredientRow['MeasurementType'] == 'tbsp' ? 'selected' : '') . '>tbsp</option>
                            <option value="cup" ' . ($ingredientRow['MeasurementType'] == 'cup' ? 'selected' : '') . '>cup</option>
                            <option value="floz" ' . ($ingredientRow['MeasurementType'] == 'floz' ? 'selected' : '') . '>fl oz</option>
                            <option value="pint" ' . ($ingredientRow['MeasurementType'] == 'pint' ? 'selected' : '') . '>pint</option>
                            <option value="quart" ' . ($ingredientRow['MeasurementType'] == 'quart' ? 'selected' : '') . '>qt</option>
                            <option value="gal" ' . ($ingredientRow['MeasurementType'] == 'gal' ? 'selected' : '') . '>gal</option>
                            <option value="mililiter" ' . ($ingredientRow['MeasurementType'] == 'mililiter' ? 'selected' : '') . '>mL</option>
                            <option value="liter" ' . ($ingredientRow['MeasurementType'] == 'liter' ? 'selected' : '') . '>L</option>
                            <option value="oz" ' . ($ingredientRow['MeasurementType'] == 'oz' ? 'selected' : '') . '>oz</option>
                            <option value="lb" ' . ($ingredientRow['MeasurementType'] == 'lb' ? 'selected' : '') . '>lb</option>
                            <option value="g" ' . ($ingredientRow['MeasurementType'] == 'g' ? 'selected' : '') . '>g</option>
                            <option value="kg" ' . ($ingredientRow['MeasurementType'] == 'kg' ? 'selected' : '') . '>kg</option>
                            <option value="pc" ' . ($ingredientRow['MeasurementType'] == 'pc' ? 'selected' : '') . '>piece</option>
                            <option value="pinch" ' . ($ingredientRow['MeasurementType'] == 'pinch' ? 'selected' : '') . '>pinch</option>
                            <option value="dash" ' . ($ingredientRow['MeasurementType'] == 'dash' ? 'selected' : '') . '>dash</option>
                            <option value="drop" ' . ($ingredientRow['MeasurementType'] == 'drop' ? 'selected' : '') . '>drop</option>
                            <option value="stick" ' . ($ingredientRow['MeasurementType'] == 'stick' ? 'selected' : '') . '>stick</option>
                            <!-- Add more measurement options as needed -->
                        </select>
                        <button type="button" onclick="addIngredient()">Add Ingredient</button>
                    </div>';
            }
            ?>
        </div>
        <br><br>

        <div id="equipment">
            <label>Equipment (One per line):</label>
            <?php
            while ($equipmentRow = $equipmentResult->fetch_assoc()) {
                echo '<div class="equipment">
                        <input type="text" name="equipmentName[]" value="' . $equipmentRow['Name'] . '" required>
                        <button type="button" onclick="addEquipment()">Add Equipment</button>
                    </div>';
            }
            ?>
        </div>
        <br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Public" <?php if ($status == 'Public') echo 'selected'; ?>>Public</option>
            <option value="Private" <?php if ($status == 'Private') echo 'selected'; ?>>Private</option>
        </select>

        <br><br>

        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="4" required><?php echo $instructions; ?></textarea>

        <button type="submit">Update Recipe</button>
    </form>

</body>

</html>
