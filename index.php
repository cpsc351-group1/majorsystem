<?php session_start(); ini_set('display_errors', true)?>
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
    # nullify login error
    $login_error = false;

    if (isset($_POST['login'])) {
      # pull data from post
      $entered_user = intval($_POST['cnu_id']);
      $entered_pass = $_POST['pass'];

      # sql query for matching user pass
      $login_sql = "SELECT `CNU_ID`, `Password`, `Permissions` FROM `User` WHERE CNU_ID=?";
      $stmt = $conn->prepare($login_sql);
      
      # bind inputs, execute, bind outputs and close
      $stmt->bind_param("i",$entered_user);
      $stmt->execute();
      $stmt->bind_result($username, $password, $permissions);
      $stmt->fetch();
      $stmt->close();

      #set login_error
      $login_error = true;

      # if user exists        and passwords match
      if (!is_null($username) and $entered_pass == $password) {
        open_session:

        # open login session
        $_SESSION['user'] = $username;
        $_SESSION['permissions'] = $permissions;
        # redirect to homepage
        header('Location: homepage.php');
      }
    }

    # USER ACCOUNT CREATION

    if (isset($_POST['create'])) {
        $posted_data = array(
          $_POST['cnu_id'],
          $_POST['pass'],
          $_POST['fname'],
          $_POST['lname'],
          $_POST['email'],
          $_POST['department'],
          $_POST['position'],
          $_POST['bday'],
          $_POST['hiring_year'],
          $_POST['gender'],
          $_POST['race'],
          $_POST['img']
        );

        $posted_id = $_POST['cnu_id'];

        $insert_sql = "INSERT INTO `User`
                      SELECT ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                      WHERE $entered_ NOT IN(SELECT CNU_ID FROM `User`)";
        # input explicit data types
        $types='isssssssisss';
        # prepare statement
        $stmt = $conn->prepare($insert_sql);
        # bind statement inputs from array
        $stmt->bind_param($types, ...$posted_data);
        # execute statement
        if ($stmt->execute()) {
            $stmt->close();
            goto open_session;
        } else {
            #TODO: echo insert fail message
        }
        $stmt->close();
    }

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
        # if login error...
        if ($login_error) {
          # throw up error
          echo "<hr><div class='error'>Invalid credentials entered.</div>";
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
