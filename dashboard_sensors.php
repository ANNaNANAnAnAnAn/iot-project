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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style_index.css">
    <script>
      function fetchSensorStatus(sensorName, statusElementId, imgElementId, noDataMessage, images, updateElementId, tableDateElementId) {
        fetch(`api.php?name=${sensorName}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById(statusElementId).innerText = data.state == '0' ? noDataMessage[0] : noDataMessage[1];
            document.getElementById(imgElementId).src = data.state == '0' ? images[0] : images[1];
            document.getElementById(updateElementId).innerText = 'Update: ' + data.date;
            document.getElementById(tableDateElementId).innerText = data.date;
          })
          .catch(error => console.error('Error fetching sensor status:', error));
      }

      function fetchAllStatuses() {
        fetchSensorStatus('flame', 'flameStatus', 'flameImg', ['No Flame', 'Flame Detected'], ['images/flame_blue_01.png', 'images/flame_red.png'], 'flameStatusUpdate', 'flameTableDate');
        fetchSensorStatus('photoresistor', 'photoresistorStatus', 'photoresistorImg', ['Bright', 'Dark'], ['images/day.png', 'images/night.png'], 'photoresistorStatusUpdate', 'photoresistorTableDate');
      }

      setInterval(fetchAllStatuses, 5000);
      window.onload = fetchAllStatuses;
    </script>
  </head>
  <body>
    <nav style="margin-bottom: 20px;" class="navbar bg-body-tertiary">
      <div class="container-fluid">
        <a style="padding-left: 10px;" class="navbar-brand" href="home.php">Home</a>
        <a style="margin-right: 10px;" href="logout.php" class="nav-item btn btn-light btn-lg" tabindex="1" role="button" aria-disabled="true">Logout</a>
      </div>
    </nav>

    <div class="container d-flex justify-content-around">
      <img id="homePic" width=300px height=93px src="images/homesecurity.png" alt="ESTG logo">
      <div id="title-header">
        <h1>IoT Server</h1>
        <h6 class="text-center">User: <?php echo $_SESSION['username']; ?></h6>
      </div>
    </div>



    <div class="container text-center d-flex  justify-content-around">
      <div class="row">
        <div class="col-sm">
          <div id="flame" class="card" style="margin-left: 0px;">
            <div class="sensor"><h5>Flame Sensor</h5></div>
            <div class="card-body"><img id="flameImg" width=128px height=auto src="images/flame_blue_01.png" alt="flame"></div>
            <div class="card-footer">
              <span id="flameStatusUpdate">Update: </span>
              <a href="flame_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div id="photores" class="card" style="margin-left: 2px;">
            <div class="sensor"><h5>Photoresistor</h5></div>
            <div class="card-body"><img id="photoresistorImg" width=128px height=auto src="images/day.png" alt="photoresistor"></div>
            <div class="card-footer">
              <span id="photoresistorStatusUpdate">Update: </span>
              <a href="photoresistor_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div id="door" class="card">
            <div class="sensor"><h5>Door</h5></div>
            <div class="card-body"><img id="flameImg" width=128px height=auto src="images/flame_blue_01.png" alt="flame"></div>
            <div class="card-footer">
              <span id="flameStatusUpdate">Update: </span>
              <a href="flame_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
      </div>
    </div>




    </div>
    <div style="width: 80%; margin-right:90px;" class="container d-flex justify-content-around">
    <table style="margin-top: 30px;" class="table text-start border">
      <thead class="head">
        <tr>
          <th scope="col"></th>
          <th scope="col">Table of Sensors</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th class="text-start" scope="col">IOT Device Type</th>
          <th scope="col">Updated Date</th>
          <th scope="col">State</th>
        </tr>
        <tr>
          <th class="text-start" scope="row">Flame Sensor</th>
          <td><span id="flameTableDate"></span></td>
          <td><span class="badge badge-normal rounded-pill text-uppercase" id="flameStatus">No Flame</span></td>
        </tr>
        <tr>
          <th class="text-start" scope="row">Photoresistor</th>
          <td><span id="photoresistorTableDate"></span></td>
          <td><span class="badge badge-normal rounded-pill text-uppercase" id="photoresistorStatus">Bright</span></td>
        </tr>
        <tr>
          <th class="text-start" scope="row">door</th>
          <td><span id="photoresistorTableDate"></span></td>
          <td><span class="badge badge-normal rounded-pill text-uppercase" id="photoresistorStatus">Bright</span></td>
        </tr>
      </tbody>
    </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
  </body>
</html>
