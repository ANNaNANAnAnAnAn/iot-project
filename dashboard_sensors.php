<?php 
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Sensors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_index.css">
    <script>
        function fetchSensorStatus(sensorName, statusElementId, imgElementId, noDataMessage, images, updateElementId, tableDateElementId) {
            fetch(`api.php?name=${sensorName}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById(statusElementId).innerText = data.state === '0' ? noDataMessage[0] : noDataMessage[1];
                    document.getElementById(imgElementId).src = data.state === '0' ? images[0] : images[1];
                    document.getElementById(updateElementId).innerText = 'Update: ' + data.date;
                    document.getElementById(tableDateElementId).innerText = data.date;
                })
                .catch(error => console.error('Error fetching sensor status:', error));
        }

        function fetchAllStatuses() {
            fetchSensorStatus('flame', 'flameStatus', 'flameImg', ['No Flame', 'Flame Detected'], ['images/flame_blue.png', 'images/flame_red.png'], 'flameUpdate', 'flameTableDate');
            fetchSensorStatus('photoresistor', 'photoresistorStatus', 'photoresistorImg', ['Bright', 'Dark'], ['images/day.png', 'images/night.png'], 'photoresistorUpdate', 'photoresistorTableDate');
            fetchSensorStatus('hall_sensor', 'hallSensorStatus', 'hallSensorImg', ['No Magnetic Field', 'Magnetic Field Detected'], ['images/no_field.png', 'images/field_detected.png'], 'hallSensorUpdate', 'hallSensorTableDate');
        }

        setInterval(fetchAllStatuses, 5000);
        window.onload = fetchAllStatuses;
    </script>
</head>
<body>
    <nav class="navbar bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">Home</a>
            <a href="logout.php" class="btn btn-light btn-lg">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>IoT Server - Sensors</h1>
        <h6>User: <?php echo $_SESSION['username']; ?></h6>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm">
                <div class="card">
                    <h5>Flame Sensor</h5>
                    <div class="card-body"><img id="flameImg" width="128" src="images/flame_blue.png" alt="Flame"></div>
                    <div class="card-footer"><span id="flameUpdate">Update: </span></div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card">
                    <h5>Photoresistor</h5>
                    <div class="card-body"><img id="photoresistorImg" width="128" src="images/day.png" alt="Photoresistor"></div>
                    <div class="card-footer"><span id="photoresistorUpdate">Update: </span></div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card">
                    <h5>Linear Hall Sensor</h5>
                    <div class="card-body"><img id="hallSensorImg" width="128" src="images/no_field.png" alt="Hall Sensor"></div>
                    <div class="card-footer"><span id="hallSensorUpdate">Update: </span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Sensor</th>
                    <th>Updated Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Flame Sensor</td>
                    <td><span id="flameTableDate"></span></td>
                    <td><span id="flameStatus">No Flame</span></td>
                </tr>
                <tr>
                    <td>Photoresistor</td>
                    <td><span id="photoresistorTableDate"></span></td>
                    <td><span id="photoresistorStatus">Bright</span></td>
                </tr>
                <tr>
                    <td>Linear Hall Sensor</td>
                    <td><span id="hallSensorTableDate"></span></td>
                    <td><span id="hallSensorStatus">No Magnetic Field</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
