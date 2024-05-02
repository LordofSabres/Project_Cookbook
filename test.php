<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['UserID'], $_SESSION['FirstName'], $_SESSION['LastName'], $_SESSION['Email'])) {
    // Redirect to login page
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cook Zillion Cookbook</title>
    <?php
    // Include menubar.php at the top of the page
    include 'menu.php';
    ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script>
        // Define the activatePrank() function directly in the HTML
        function activatePrank() {
            var music = new Audio('./nevergonnagiveyouup.MP3');
            music.play();

            // Create HTML elements
            var prankTitle = document.createElement('h1');
            prankTitle.textContent = 'APRIL FOOLS';

            var lineBreak1 = document.createElement('br');

            var prankSubtitle = document.createElement('h2');
            prankSubtitle.textContent = 'Cook Zillion COMING SOON This Summer';

            var lineBreak2 = document.createElement('br');

            // Append elements to the body or any desired container
            document.body.appendChild(prankTitle);
            document.body.appendChild(lineBreak1);
            document.body.appendChild(prankSubtitle);
            document.body.appendChild(lineBreak2);
        }
    </script>
</head>
<body>

    <?php
    // Display user information
    $UserID = $_SESSION['UserID'];
    $FirstName = $_SESSION['FirstName'];
    $LastName = $_SESSION['LastName'];
    $Email = $_SESSION['Email'];

    echo "<h2>Welcome, $FirstName $LastName!</h2>";
    echo "<p>User ID: $UserID</p>";
    ?>

    <br><br>
    <h2>Press the Start Button below to begin!</h2>
    <button onclick="activatePrank()">Start</button>

    <?php include 'footer.php'; ?>

</body>
</html>
