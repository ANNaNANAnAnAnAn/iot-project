<?php 
  session_start();

  if(!isset($_SESSION['username'])) {
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style_index.css">
    <script>
      function fetchActuatorStatus(actuatorName, elementImgId, images, statusElementId, updateElementId, tableDateElementId) {
        fetch(`api.php?name=${actuatorName}`)
          .then(response => response.json())
          .then(data => {
            document.getElementById(elementImgId).src = data.state == '0' ? images[0] : images[1];
            document.getElementById(updateElementId).innerText = 'Update: ' + data.date;
            document.getElementById(statusElementId).innerText = data.state == '0' ? 'Off' : 'On';
            document.getElementById(tableDateElementId).innerText = data.date;
          })
          .catch(error => console.error('Error fetching actuator status:', error));
      }

      function fetchAllStatuses() {
        fetchActuatorStatus('led', 'ledImg', ['images/led_off.png', 'images/led_on.png'], 'ledState', 'ledUpdate', 'ledTableDate');
        fetchActuatorStatus('buzzer', 'buzzerImg', ['images/buzzer_off.png', 'images/buzzer_on.png'], 'buzzerState', 'buzzerUpdate', 'buzzerTableDate');
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
      <img id="homePic" width="300px" height="93px" src="images/homesecurity.png" alt="ESTG logo">
      <div id="title-header">
        <h1>IoT Server</h1>
        <h6 class="text-center">User: <?php echo $_SESSION['username']; ?></h6>
      </div>
    </div>


    <div class="container text-center d-flex justify-content-around">
      <div class="row">
        <div id="" class="col-sm">
          <div class="card">
            <div class="sensor"><h5>LED</h5></div>
            <div class="card-body" style="height: 168.22px"> <img id="ledImg" width="128px" height="auto" src="images/led_off.png" alt="led"></div>
            <div class="card-footer">
              <span id="ledUpdate">Update: </span>
              <a href="led_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div id="" class="card" >
            <div class="sensor"><h5>Active Buzzer</h5></div>
            <div class="card-body"  ><img id="buzzerImg" width="128px" height="auto" src="images/buzzer_off.png" alt="buzzer"></div>
            <div class="card-footer">
              <span id="buzzerUpdate">Update: </span>
              <a href="buzzer_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
        <div class="col-sm">
          <div id="" class="card" >
            <div class="sensor"><h5>Door</h5></div>
            <div class="card-body"><img id="buzzerImg" width="128px" height="auto" src="images/buzzer_off.png" alt="buzzer"></div>
            <div class="card-footer">
              <span id="buzzerUpdate">Update: </span>
              <a href="buzzer_history.php" class="link-primary">History</a>
            </div>
          </div>
        </div>
      </div>
    </div>



    <div style="width: 80%;" class="container d-flex justify-content-around">
      <table style="margin-top: 30px;" class="table text-start border">
        <thead class="head">
          <tr>
            <th scope="col"></th>
            <th scope="col">Table of Actuators</th>
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
            <th class="text-start" scope="row">LED</th>
            <td><span id="ledTableDate"></span></td>
            <td><span class="badge badge-normal rounded-pill text-uppercase" id="ledState">Off</span></td>
          </tr>
          <tr>
            <th class="text-start" scope="row">Active Buzzer</th>
            <td><span id="buzzerTableDate"></span></td>
            <td><span class="badge badge-normal rounded-pill text-uppercase" id="buzzerState">Off</span></td>
          </tr>
          <tr>
            <th class="text-start" scope="row">Door </th>
            <td><span id="buzzerTableDate"></span></td>
            <td><span class="badge badge-normal rounded-pill text-uppercase" id="buzzerState">Off</span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></script>
  </body>
</html>
