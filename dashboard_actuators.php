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
    <title>Home Actuators</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style_index.css">
    <script>
        function fetchActuatorStatus(actuatorName, elementImgId, images, statusElementId, updateElementId, tableDateElementId) {
            fetch(`api.php?name=${actuatorName}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById(elementImgId).src = data.state === '0' ? images[0] : images[1];
                    document.getElementById(updateElementId).innerText = 'Update: ' + data.date;
                    document.getElementById(statusElementId).innerText = data.state === '0' ? 'Off' : 'On';
                    document.getElementById(tableDateElementId).innerText = data.date;
                })
                .catch(error => console.error('Error fetching actuator status:', error));
        }

        function fetchAllStatuses() {
            fetchActuatorStatus('led', 'ledImg', ['images/led_off.png', 'images/led_on.png'], 'ledState', 'ledUpdate', 'ledTableDate');
            fetchActuatorStatus('buzzer', 'buzzerImg', ['images/buzzer_off.png', 'images/buzzer_on.png'], 'buzzerState', 'buzzerUpdate', 'buzzerTableDate');
            fetchActuatorStatus('servo', 'servoImg', ['images/servo_closed.png', 'images/servo_open.png'], 'servoState', 'servoUpdate', 'servoTableDate');
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

    <div class="container d-flex justify-content-around">
        <h1>IoT Server - Actuators</h1>
        <h6>User: <?php echo $_SESSION['username']; ?></h6>
    </div>

    <div class="container text-center d-flex justify-content-around">
        <div class="row">
            <div class="col-sm">
                <div class="card">
                    <h5>LED</h5>
                    <div class="card-body"><img id="ledImg" width="128" src="images/led_off.png" alt="LED"></div>
                    <div class="card-footer">
                        <span id="ledUpdate">Update: </span>
                        <a href="led_history.php">History</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card">
                    <h5>Active Buzzer</h5>
                    <div class="card-body"><img id="buzzerImg" width="128" src="images/buzzer_off.png" alt="Buzzer"></div>
                    <div class="card-footer">
                        <span id="buzzerUpdate">Update: </span>
                        <a href="buzzer_history.php">History</a>
                    </div>
                </div>
            </div>
            <div class="col-sm">
                <div class="card">
                    <h5>Servo Motor</h5>
                    <div class="card-body"><img id="servoImg" width="128" src="images/servo_closed.png" alt="Servo"></div>
                    <div class="card-footer">
                        <span id="servoUpdate">Update: </span>
                        <a href="servo_history.php">History</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Actuator</th>
                    <th>Updated Date</th>
                    <th>State</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>LED</td>
                    <td><span id="ledTableDate"></span></td>
                    <td><span id="ledState">Off</span></td>
                </tr>
                <tr>
                    <td>Active Buzzer</td>
                    <td><span id="buzzerTableDate"></span></td>
                    <td><span id="buzzerState">Off</span></td>
                </tr>
                <tr>
                    <td>Servo Motor</td>
                    <td><span id="servoTableDate"></span></td>
                    <td><span id="servoState">Off</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
