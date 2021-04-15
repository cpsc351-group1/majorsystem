<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    # erase current session if coming from logout
    if (isset($_POST['logout'])) {
      session_unset();
    }

    # if session variable already set, redirect to homepage
    if (isset($_SESSION['user'])) {
      header('Location: homepage.php');
    }

    # connect to database
    include 'databaseconnect.php';

    # pull data from post
    $entered_user = intval($_POST['cnu_id']);
    $entered_pass = $_POST['pass'];

    # sql query for matching user pass
    $sql = "SELECT * FROM `User` WHERE CNU_ID=$entered_user";
    $result = $conn->query($sql);

  ?>
  <title>CNU Committee Database</title>
  <link href="css/main.css" rel="stylesheet">
</head>
<body>
  <h1>Committee Database</h1>
  <div class="wrapper small">
    <div class="body">
      <form action="index.php" method="post">
        <div class="credentials">
          <label for="cnu_id">CNU ID</label>
          <input type="text" name="cnu_id" placeholder="CNU ID">
          <label for="pass">Password</label>
          <input type="password" name="pass" placeholder="Password">
        </div>
        <?php
        # if submit button is set ...
        if (isset($_POST['login'])) {
            # check that username and password entered match
            if ($result->num_rows > 0 and $result->fetch_assoc()['Password'] == $entered_pass) {
                # open login session
                $_SESSION['user'] = $entered_user;

                # redirect to homepage
                header('Location: homepage.php');
            } else {
                echo "<hr><div class='error'>Invalid credentials entered.</div>";
            }
        }
        ?>
        <hr>
        <div class="credentials">
          <a href="user_registration.php"><b>Create Account</b></a>
          <input type="submit" name="login" value="Login">
        </div>
      </form>
    </div>
    <footer>
    </footer>
  </div>
</body>
<?php $conn->close(); ?>
</html>
