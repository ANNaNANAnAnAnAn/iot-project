<?php
session_start();

// Check if both username and password are submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Read the users from the text file
    $usersFile = "users.txt";
    $usersData = file($usersFile, FILE_IGNORE_NEW_LINES);

    // Loop through each line in the file
    foreach ($usersData as $user) {
        // Split the line into username and hashed password
        list($storedUsername, $storedPassword) = explode(',', $user);

        // Check if the submitted username matches
        if (trim($username) == trim($storedUsername)) {
            // Verify the submitted password with the stored hashed password
            if (password_verify($password, trim($storedPassword))) {
                // Successful login
                $_SESSION['username'] = $username;
                // Redirect to the home page
                header("Location: home.php");
                exit; // Stop further processing
            } else {
                // Password does not match
                error_log("Password mismatch for user: $username");
                header("Location: index.php?error=invalid_password");
                exit;
            }
        }
    }
    // Username not found
    error_log("Username not found: $username");
    header("Location: index.php?error=invalid_username");
    exit;
} else {
    // If not submitted correctly, redirect to the login page
    error_log("Missing parameters");
    header("Location: index.php?error=missing_parameters");
    exit;
}

?>
