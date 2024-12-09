<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Save the hashed password and username in the users file
    $usersFile = "users.txt";
    file_put_contents($usersFile, $username . ',' . $password . PHP_EOL, FILE_APPEND | LOCK_EX);

    // Redirect to login page after registration
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post">
        Username: <input type="text" name="username" required>
        Password: <input type="password" name="password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
