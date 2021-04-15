<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php
    # connect to database
    include 'databaseconnect.php';

    # pull data from post
    $entered_user = intval($_POST['cnu_id']);
    $entered_pass = $_POST['pass'];

    # sql query for matching user pass
    $sql = "SELECT * FROM `User` WHERE CNU_ID=$entered_user";
    $result = $conn->query($sql);

    # if submit button is set ...
    if (isset($_POST['login'])) {
        # check that username and password entered match
        if ($result->num_rows > 0 and $result->fetch_assoc()['Password'] == $entered_pass) {
            # open login session
            $_SESSION['user'] = $entered_user;

            # redirect to homepage
            header('Location: homepage.php');
        } else {
            echo "epic fail";
        }
    } else {
        session_unset();
    }

  ?>
  <title>CNU Committee Database</title>
  <link href="css/main.css" rel="stylesheet">
</head>
<h1>
  Committee Database
</h1>

<body>
  <div id="box">
    <br>
    <form action="index.php" method="post">
      <label for="cnu_id"></label>
      <input type="text" name="cnu_id" placeholder="CNU ID">
      <br><br>
      <label for="pass"></label>
      <input type="password" name="pass" placeholder="Password">
      <p>
        <input type="submit" name="login" value="Login">
      <footer>
        <a href="user_registration.html" style="color: gray"><b>Create Account</b></a>
      </footer>
  </div>
</body>

</html>
