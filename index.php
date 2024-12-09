<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>
    <link rel="stylesheet" href="style_index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="welcomeT text-center"><h2>WELCOME TO HOME SECURITY</h2></div>
    <div class="container d-flex justify-content-center">
      <img id="homePic" width=300px heigh=auto src="images/homesecurity.png" alt="ESTG logo"> 
    </div>
    <div class="container d-flex flex-column w-25">
        <form action="login.php" method="POST" role="form">
            <div id="userinput" class="form-group">
                <input id="username" name="username" type="text" class="form-control" placeholder="Enter your username">
            </div>
            <div id="passinput" class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            </div>
            <button id="submitButon" type="submit" value="Login" class="btn btn-primary w-100">Submit</button>
        </form>
        <?php
          if (isset($_GET['error'])) {
              echo '<div class="alert alert-danger mt-3">';
              if ($_GET['error'] == 'invalid_password') {
                  echo 'Invalid password. Please try again.';
              } else if ($_GET['error'] == 'invalid_username') {
                  echo 'Invalid username. Please try again.';
              } else if ($_GET['error'] == 'missing_parameters') {
                  echo 'Please enter both username and password.';
              }
              echo '</div>';
          }
        ?>
    </div>
  </body>
</html>

