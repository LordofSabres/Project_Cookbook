<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
</head>
<body>

<?php
require './connect.php';
$conn = Connect();

// Initialize variables to store user input
$first_name = $last_name = $email = $username = $password = "";
$firstNameErr = $lastNameErr = $emailErr = $usernameErr = $passwordErr = "";
$signupSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate first name
    if (empty($_POST["FirstName"])) {
        $firstNameErr = "First name is required";
    } else {
        $first_name = test_input($_POST["FirstName"]);
    }

    // Validate last name
    if (empty($_POST["LastName"])) {
        $lastNameErr = "Last name is required";
    } else {
        $last_name = test_input($_POST["LastName"]);
    }

    // Validate email
    if (empty($_POST["Email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["Email"]);
    }

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
        $password = password_hash(test_input($_POST["Password"]), PASSWORD_DEFAULT);
    }

    // If all required fields are provided
    if (!empty($first_name) && !empty($last_name) && !empty($email) && !empty($username) && !empty($password)) {
        // Check if the username is already taken
        $checkUsernameQuery = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($checkUsernameQuery);

        if ($result->num_rows > 0) {
            $usernameErr = "Username is already taken";
        } else {
            // Insert new user into the database
            $insertUserQuery = "INSERT INTO users (FirstName, LastName, Email, Username, Password)
                                VALUES ('$first_name', '$last_name', '$email', '$username', '$password')";

            if ($conn->query($insertUserQuery) === TRUE) {
                $signupSuccess = "Signup successful! You can now <a href='login.php'>login</a>.";
                // You can redirect the user to the login page or perform other actions
            } else {
                echo "Error: " . $insertUserQuery . "<br>" . $conn->error;
            }
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

<h2>Signup</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="FirstName">First Name:</label>
    <input type="text" id="FirstName" name="FirstName">
    <span class="error"><?php echo $firstNameErr; ?></span>

    <br>

    <label for="LastName">Last Name:</label>
    <input type="text" id="LastName" name="LastName">
    <span class="error"><?php echo $lastNameErr; ?></span>

    <br>

    <label for="Email">Email:</label>
    <input type="Email" id="email" name="Email">
    <span class="error"><?php echo $emailErr; ?></span>

    <br>

    <label for="Username">Username:</label>
    <input type="text" id="Username" name="Username">
    <span class="error"><?php echo $usernameErr; ?></span>

    <br>

    <label for="Password">Password:</label>
    <input type="password" id="Password" name="Password">
    <span class="error"><?php echo $passwordErr; ?></span>

    <br>

    <input type="submit" value="Signup">
</form>

<span class="success"><?php echo $signupSuccess; ?></span>

</body>
</html>