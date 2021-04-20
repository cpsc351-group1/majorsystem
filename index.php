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
                      WHERE $posted_id NOT IN(SELECT CNU_ID FROM `User`)";
        # input explicit data types
        $types='isssssssisss';
        # prepare statement
        $stmt = $conn->prepare($insert_sql);
        # bind statement inputs from array
        $stmt->bind_param($types, ...$posted_data);
        // call_user_func_array(array($stmt, 'bind_param'), array_merge($types, $posted_data));
        # execute statement
        if ($stmt->execute()) {
            $stmt->close();
            $_SESSION['user']=$posted_id;
            header("Location:user_details.php?user=$posted_id");
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
