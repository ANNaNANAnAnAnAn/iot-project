<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit; // Stop further processing
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style_index.css">
</head>
<body>
    <nav style="margin-bottom: 20px;" class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a style="padding-left: 10px;" class="navbar-brand" href="home.php">Home</a>
            <a style="margin-right: 10px;" href="logout.php" class="nav-item btn btn-light btn-lg" tabindex="1" role="button" aria-disabled="true">Logout</a>
        </div>
    </nav>

    <div class="container d-flex justify-content-around">
        <img id="homePic" width="300px" height="93px" src="images/homesecurity.png" alt="ESTG logo">
        <div id="title-header">
            <h1>IoT Server</h1>
            <h6 class="text-center">User: <?php echo $_SESSION['username']; ?></h6>
        </div>
    </div>
    <div class="container d-flex justify-content-around text-center">
        <div class="row">
            <div class="col-sm">
                <div id="homeCard" class="card">
                    <div class="homeSen"><h5>Home Sensor</h5></div>
                    <div class="card-body"><img id="pic" width="128px" height="auto" src="images/homeSensors.png" alt="fingerprint"></div>
                    <a id="goHome" href="dashboard_sensors.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Check Sensors</a>
                </div>
            </div>
            <div class="col-sm">
                <div id="homeCard" class="card">
                    <div class="homeAct"><h5>Home Actuators</h5></div>
                    <div class="card-body">
                        <img width="128px" height="auto" src="images/homeActuators.png" alt="temperature">
                    </div>
                    <a id="goHome" href="dashboard_actuators.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Check Actuators</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
