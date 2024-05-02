function redirectToLogin() {
    window.location.href = "login.php";
}

function redirectToSignUp() {
    window.location.href = "signup.php";
}

function redirectToAddRecipe() {
    window.location.href = "addrecipe.php";
}

function redirectToAddInventory() {
    window.location.href = "addinventory.php";
}

function redirectToEditRecipe() {
    window.location.href = "editrecipe.php";
}

function redirectToEditInventory() {
    window.location.href = "editinventory.php";
}

function redirectToMealGenerator() {
    window.location.href = "mealgenerate.php";
}

function redirectToMain() {
    window.location.href = "main.php";
}

function openModal() {
    document.getElementById('loginModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// function activatePrank() {
//     var music = new Audio('./nevergonnagiveyouup.MP3');
//     music.play();

//      // Create HTML elements
//      var prankTitle = document.createElement('h1');
//      prankTitle.textContent = 'APRIL FOOLS';
 
//      var lineBreak1 = document.createElement('br');
 
//      var prankSubtitle = document.createElement('h2');
//      prankSubtitle.textContent = 'Cook Zillion COMING SOON This Summer';
 
//      var lineBreak2 = document.createElement('br');
 
//      // Append elements to the body or any desired container
//      document.body.appendChild(prankTitle);
//      document.body.appendChild(lineBreak1);
//      document.body.appendChild(prankSubtitle);
//      document.body.appendChild(lineBreak2);
// }

function authenticateUser() {
    // In a real-world scenario, you would perform server-side authentication here.
    // This example uses hardcoded credentials for demonstration purposes.
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === 'demo' && password === 'password') {
        alert('Login successful!');
        closeModal();
        // Redirect to the main page or perform other actions after successful login.
        return false; // Prevent form submission
    } else {
        alert('Invalid username or password. Please try again.');
        return false; // Prevent form submission
    }
}

function addIngredient() {
    var ingredientDiv = document.createElement('div');
    ingredientDiv.className = 'ingredient';
    ingredientDiv.innerHTML =
        '<select name="ingredientCategory[]" required>' +
        '<option value="Dairy">Dairy</option>' +
        '<option value="Fruits">Fruits</option>' +
        '<option value="Vegetables">Vegetables</option>' +
        '<option value="Carbs">Carbs</option>' +
        '<option value="Preserves">Preserves</option>' +
        '<option value="Protein">Protein</option>' +
        '<option value="Condiments">Condiments</option>' +
        '<option value="Spices">Spices</option>' +
        '</select>' +
        '<input type="text" name="ingredientName[]" required>' +
        '<input type="text" name="ingredientQuantity[]" placeholder="Quantity" required>' +
        '<select name="ingredientMeasurement[]" required>' +
        '<option value="tsp">tsp</option>' +
        '<option value="tbsp">tbsp</option>' +
        '<option value="cup">cup</option>' +
        '<option value="floz">fl oz</option>' +
        '<option value="pint">pint</option>' +
        '<option value="quart">qt</option>' +
        '<option value="gal">gal</option>' +
        '<option value="mililiter">mL</option>' +
        '<option value="liter">L</option>' +
        '<option value="oz">oz</option>' +
        '<option value="lb">lb</option>' +
        '<option value="g">g</option>' +
        '<option value="kg">kg</option>' +
        '<option value="pc">piece</option>' +
        '<option value="pinch">pinch</option>' +
        '<option value="dash">dash</option>' +
        '<option value="drop">drop</option>' +
        '<option value="stick">stick</option>' +
        '</select>' +
        '<button type="button" onclick="removeElement(this)">Remove</button>';

    document.getElementById('ingredients').appendChild(ingredientDiv);
}


function addEquipment() {
    var equipmentDiv = document.createElement('div');
    equipmentDiv.className = 'equipment';
    equipmentDiv.innerHTML = '<input type="text" name="equipmentName[]" required>' +
        '<button type="button" onclick="removeElement(this)">Remove</button>';

    document.getElementById('equipment').appendChild(equipmentDiv);
}

function removeElement(button) {
    button.parentNode.parentNode.removeChild(button.parentNode);
}

function getCurrentYear() {
    return new Date().getFullYear();
  }
