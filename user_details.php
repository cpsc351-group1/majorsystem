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
      $stmt = $conn->prepare("SELECT * FROM `User` WHERE CNU_ID=?");
      # bind parameter to int
      $stmt->bind_param('i', $entered_id);
      # execute statement
      $stmt->execute();
      # obtain results object
      $user = $stmt->get_result()->fetch_assoc();
      # close connection
      $stmt->close();

      validate_inputs(is_null($user), 0, 'user_selection.php');

      # store user id variable
      $user_id = $user['CNU_ID'];

      # sql to pull committee memberships
      $seat_sql = "SELECT * FROM `Committee Seat` WHERE User_CNU_ID = $user_id";
      $seats = $conn->query($seat_sql);
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

          <div class="tiles">
            <div class="tile">
              <span class="sub heading">Personal Info</span>
              <div class="list">
                <span class="label">Email:</span>
                <span><?php echo $user['Email'];?></span>

                <span class="label">Birthday:</span>
                <span><?php echo $user['Birthday'];?></span>
              </div>
            </div>

            <div class="tile">
              <span class="sub heading">Other</span>
              <div class="list">
                <span class="label">Race:</span>
                <span><?php echo $user['Race'];?></span>

                <span class="label">Gender:</span>
                <span><?php echo $user['Gender'];?></span>
              </div>
            </div>

            <div class="tile">
              <span class="sub heading">Employment</span>
              <div class="list">
                <span class="label">College:</span>
                <span><?php echo $user['Department'];?></span>

                <span class="label">Position:</span>
                <span><?php echo $user['Position'];?></span>

                <span class="label">Year of Hiring:</span>
                <span><?php echo $user['Hiring_Year'];?></span>
              </div>
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
        <div class="tiles">
          <?php
          if ($seats->num_rows != 0) {
              # iterate through each seat
              while ($seat = $seats->fetch_assoc()) {
                  # pull committee details
                  $committee_id = $seat['Committee_Committee_ID'];
                  $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID = '$committee_id'";

                  $committee = $conn->query($committee_sql)->fetch_assoc();

                  # store committee variables
                  $committee_name = $committee['Name'];
                  $committee_description = $committee['Description'];

                  # pull committee chairman
                  $chairman_sql = "SELECT * FROM `Chairman` WHERE Committee_Committee_ID = '$committee_id' AND User_CNU_ID = $user_id";
                  $chairman = $conn->query($chairman_sql)->fetch_assoc();

                  # render tile for committee
                  echo "<div class='tile'>";
                        if (!is_null($chairman)) {
                          echo "<div class='sub heading'>Committee Chair</div>";
                        }
                  echo  " <div class='sub heading'>$committee_name</div>
                          <div>$committee_description</div>
                        </div>";
              }
          } else {
              # error message in case user has no seats
              echo "<div class='center'>No committee memberships to display.</div>";
          }
          ?>
        </div>
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>
</html>
