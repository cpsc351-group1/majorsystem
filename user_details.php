<?php session_start(); ini_set('display_errors', 1)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';

      // $user_id = intval($_GET['user']);
      // $sql = "SELECT * FROM `User` WHERE CNU_ID='$user_id';";
      // $user = $conn->query($sql)->fetch_assoc();

      # pull posted user id variable
      $entered_id = $_GET['user'];

      # prepare statement (this is done to prevent sql injection)
      $election = $conn->prepare("SELECT * FROM `User` WHERE CNU_ID=?");
      # bind parameter to int
      $election->bind_param('i', $entered_id);
      # execute statement
      $election->execute();
      # obtain results object
      $user = $election->get_result()->fetch_assoc();
      # close connection
      $election->close();

    ?>

  <title>CNU — <?php echo $user['Fname']." ".$user['Lname']; ?></title>
</head>

<body>

  <!-- TODO: Create PHP script to generate this page for all
               users in a report    -->

  <div class="wrapper">
    <header>
      <h2>User Details</h2>
    </header>
    <div class="body">
      <div class="column">
        <!-- Heading -->
        <span class="major heading"><?php echo $user['Fname'].' '.$user['Lname'];?></span>
        <hr>
        <div class="profile">

            <div class="wrap_profile">
              <div class="block">
                <span class="sub heading">Personal Info</span>

                <span class="label">Email:</span>
                <span><?php echo $user['Email'];?></span>

                <span class="label">Birthday:</span>
                <span><?php echo $user['Birthday'];?></span>
              </div>

              <div class="block">
                <span class="sub heading">Other</span>
                <span class="label">Race:</span>
                <span><?php echo $user['Race'];?></span>

                <span class="label">Gender:</span>
                <span><?php echo $user['Gender'];?></span>
              </div>

              <div class="block">
                <span class="sub heading">Employment</span>
                <span class="label">College:</span>
                <span><?php echo $user['Department'];?></span>

                <span class="label">Position:</span>
                <span><?php echo $user['Position'];?></span>

                <span class="label">Date of Hiring:</span>
                <span><?php echo $user['Date_of_Hiring'];?></span>
              </div>
            </div>
            <div class="right">
              <div class="image">
                <!-- TODO: dynamically insert image -->
              </div>
              <?php
                $user_id = $user['CNU_ID'];
                echo "<form action='user_details_update.php' method='get'><button name='user' value='$user_id'>Modify Profile</button></form>";
              ?>
            </div>

        </div>
        <hr>
        <span class="heading major">Committee Membership</span>
        <div class="profile">
          <!-- TODO: dynamically insert committee memberships -->
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>
</html>
