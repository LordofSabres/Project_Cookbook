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

    <h1>Add to Inventory</h1>

    <form action="process_inventory.php" method="post">
        <label for="inventoryType">Select Inventory Type:</label>
        <select id="inventoryType" name="inventoryType" required>
            <option value="Ingredients">Ingredients</option>
            <option value="Equipment">Equipment</option>
        </select>

        <br><br>

        <label for="itemName">Item Names (comma-separated):</label>
        <input type="text" id="itemName" name="itemName[]" required>

        <?php
        // If the selected type is Ingredients, show a category input
        if (isset($_POST["inventoryType"]) && $_POST["inventoryType"] == "Ingredients") {
            echo '<br><br>';
            echo '<label for="itemCategory">Item Category:</label>';
            echo '<input type="text" id="itemCategory" name="itemCategory[]" required>';
        }
        ?>

        <br><br>

        <button type="submit">Add to Inventory</button>
    </form>

    <?php include 'footer.php'; ?>
</body>

</html>
