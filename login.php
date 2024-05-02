<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>

<?php

session_start(); //Start the session

require './connect.php';
$conn = Connect();

// Initialize variables to store user input
$username = $password = "";
$usernameErr = $passwordErr = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["Username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["Username"]);
    }

    // Validate password
    if (empty($_POST["Password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["Password"]);
    }

    // If both username and password are provided
    if (!empty($username) && !empty($password)) {
        // Retrieve hashed password from the database based on the username
        $sql = "SELECT UserID, FirstName, LastName, Email, Username, Password FROM users WHERE Username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password_hash = $row["Password"];

            // Check if entered password matches the stored hashed password
            if (password_verify($password, $stored_password_hash)) {
                // Password is correct, allow login
                $_SESSION["UserID"] = $row["UserID"];
                $_SESSION['FirstName'] = $row["FirstName"];
                $_SESSION['LastName'] = $row["LastName"];
                $_SESSION['Email'] = $row["Email"];
                
                header("Location: main.php");
                
                // echo "Login successful! Welcome, " . $row["first_name"] . " " . $row["last_name"];
                // header("Location: main.php?first_name=" . $row["first_name"] . "&last_name=" . $row["last_name"]);
                exit();
            } else {
                // Password is incorrect
                $loginError = "Invalid username or password";
            }
        } else {
            // Username not found
            $loginError = "Invalid username or password";
        }
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>Login</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="Username">Username:</label>
    <input type="text" id="Username" name="Username">
    <span class="error"><?php echo $usernameErr; ?></span>

    <br>

    <label for="Password">Password:</label>
    <input type="password" id="Password" name="Password">
    <span class="error"><?php echo $passwordErr; ?></span>

    <br>

    <input type="submit" value="Login">
</form>

<span class="error"><?php echo $loginError; ?></span>

</body>
</html>