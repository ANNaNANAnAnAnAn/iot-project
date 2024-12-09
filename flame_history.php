<?php
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Flame History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style_index.css">
</head>
<body>
<nav style="margin-bottom: 20px;" class="navbar navbar-expand-sm bg-body-tertiary">
    <ul class="navbar-nav mr-auto">
        <a style="margin-left: 50px;" class="navbar-brand" href="dashboard_sensors.php">Home Sensors</a>
        <a class="navbar-brand" href="dashboard_actuators.php">Home Actuators</a>
    </ul>
    <a style="margin-left: 63%;" href="logout.php" class="nav-item btn btn-light btn-lg" tabindex="1" role="button" aria-disabled="true">Logout</a>
</nav>

<div class="container d-flex justify-content-around labels">
    <img id="homePic" width=300px height=93px src="images/homesecurity.png" alt="ESTG logo">
    <div id="title-header">
        <h1>IoT Server</h1>
        <h6 class="text-center">User: <?php echo $_SESSION['username']; ?></h6>
    </div>
</div>

<?php
$historyFilePath = "ti/api/flame/history.txt";

if (!file_exists($historyFilePath)) {
    echo "<div class='alert alert-danger' role='alert'>The file cannot be found!</div>";
    exit;
}

$fp = fopen($historyFilePath, "r");

if (!$fp) {
    echo "<div class='alert alert-danger' role='alert'>File cannot be opened</div>";
    exit;
}
?>

<div class="container w-50 p-3">
    <h2 style="color:#298cba;" class="text-center">Flame Sensor History</h2>
    <table class="table text-center border">
        <thead class="text-center">
            <tr>
                <th>Date</th>
                <th>State</th>
            </tr>
        </thead>
        <tbody>
            <?php while (($line = fgets($fp)) !== false): ?>
                <?php list($date, $state) = explode(';', trim($line)); ?>
                <tr>
                    <td><?php echo htmlspecialchars($date); ?></td>
                    <td><?php echo $state == '0' ? 'Off' : 'On'; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php fclose($fp); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
</body>
</html>
