<!DOCTYPE html>
<html>

<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
        crossorigin="anonymous"></script> -->
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
    crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Project Cookbook</title>
    <script src="web_background.js" type="text/javascript"></script>
</head>

<body>

    <h1>Add a New Recipe</h1>

    <form action="process_recipe.php" method="post">
        <label for="recipeName">Recipe Name:</label>
        <input type="text" id="recipeName" name="recipeName" required>

        <br><br>

        <div id="ingredients">
            <label>Ingredients:</label>
            <div class="ingredient">
                <select name="ingredientCategory[]" required>
                    <option value="Dairy">Dairy</option>
                    <option value="Fruits">Fruits</option>
                    <option value="Vegetables">Vegetables</option>
                    <option value="Carbs">Carbs</option>
                    <option value="Preserves">Preserves</option>
                    <option value="Protein">Protein</option>
                    <option value="Condiments">Condiments</option>
                    <option value="Spices">Spices</option>
                </select>
                <input type="text" name="ingredientName[]" class="autocomplete" required>
                <input type="text" name="ingredientQuantity[]" required placeholder="Quantity">
                <select name="ingredientMeasurement[]" required>
                    <option value="tsp">tsp</option>
                    <option value="tbsp">tbsp</option>
                    <option value="cup">cup</option>
                    <option value="floz">fl oz</option>
                    <option value="pint">pint</option>
                    <option value="quart">qt</option>
                    <option value="gal">gal</option>
                    <option value="mililiter">mL</option>
                    <option value="liter">L</option>
                    <option value="oz">oz</option>
                    <option value="lb">lb</option>
                    <option value="g">g</option>
                    <option value="kg">kg</option>
                    <option value="pc">piece</option>
                    <option value="pinch">pinch</option>
                    <option value="dash">dash</option>
                    <option value="drop">drop</option>
                    <option value="stick">stick</option>
                    <!-- Add more measurement options as needed -->
                </select>
                <button type="button" onclick="addIngredient()">Add Ingredient</button>
            </div>
        </div>
        <br><br>

        <div id="equipment">
            <label>Equipment:</label>
            <div class="equipment">
                <input type="text" name="equipmentName[]" class="autocomplete" required>
                <button type="button" onclick="addEquipment()">Add Equipment</button>
            </div>
        </div>
        <br><br>

        <label for="status">Status:</label>
        <select id="status" name="status" required>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
        </select>

        <br><br>

        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="4" required></textarea>

        <button type="submit">Add Recipe</button>
    </form>

    <!-- JavaScript for Autocomplete -->
    <script>
        $(function() {
            $(".autocomplete").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "autocomplete.php",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 2 // Minimum characters before autocomplete kicks in
            });
        });
    </script>

</body>

</html>
