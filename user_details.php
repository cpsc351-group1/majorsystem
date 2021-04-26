<?php session_start(); ini_set('display_errors', 1)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      require 'databaseconnect.php';
      require 'committee_functions.php';

      // $user_id = intval($_GET['user']);
      // $sql = "SELECT * FROM `User` WHERE CNU_ID='$user_id';";
      // $user = $conn->query($sql)->fetch_assoc();

      # pull posted user id variable
      
      if (isset($_GET['user'])) {
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
      }
      
      validate_inputs($user == NULL, false, 'user_selection.php');

      # store user id variable
      $user_id = $user['CNU_ID'];

      # sql to pull committee memberships
      $seat_sql = "SELECT * FROM `Committee Seat` WHERE User_CNU_ID = $user_id";
      $seats = $conn->query($seat_sql);
    ?>

  <title>CNU — <?php echo $user['Fname']." ".$user['Lname']; ?></title>
</head>

<body>

  <!-- INCLUDE HAMBURGER MENU -->
  <?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <h2>User Details</h2>
    </header>
    <div class="body">
      <div class="column">
        <!-- Heading -->
        <span class="major sub heading"><?php echo $user['Fname'].' '.$user['Lname'];?>
          <i>
            <?php
              if ($user['Archival_Date'] != NULL) {
                echo " (Archived)";
              }
            ?>
          </i>
        </span>
        <div class="profile">
        

          <div class="tiles">
            <div class="tile center tall">
              <div class="image">
                <!-- TODO: dynamically insert image -->
                <?php
                  echo '<img src="data:image/png;base64,'.base64_encode($user['Photo']).'"/>';
                ?>
              </div>
              <?php
                if (($user_id == $current_user_id) or ($current_user_permissions == "Admin")) {
                  echo "<form action='user_modify.php' method='get'><button name='user' value='$user_id'>Modify Profile</button></form>";
                }
              ?>
            <form action="user_details.php" method="post"></form>
          </div>
            <div class="tile">
              <div class="sub heading">Personal Info</div>
              <div class="list ruled">
                <div class="label">Email:</div>
                <div><?php echo $user['Email'];?></div>

                <div class="label">Birthday:</div>
                <div><?php echo $user['Birthday'];?></div>
              </div>
            </div>

            <div class="tile">
              <div class="sub heading">Other</div>
              <div class="list ruled">
                <div class="label">Race:</div>
                <div><?php echo $user['Race'];?></div>

                <div class="label">Gender:</div>
                <div><?php echo $user['Gender'];?></div>
              </div>
            </div>

            <div class="tile">
              <div class="sub heading">Employment</div>
              <div class="list ruled">
                <div class="label">Department:</div>
                <div><?php echo $user['Department'];?></div>

                <div class="label">Position:</div>
                <div><?php echo $user['Position'];?></div>

                <div class="label">Year of Hiring:</div>
                <div><?php echo $user['Hiring_Year'];?></div>
              </div>
            </div>
            <?php if ($user['Archival_Date'] == NULL) {goto skip_archival;}?>

            <div class="tile center">
              <span class="heading">Account archived on <?php echo $user['Archival_Date'];?>.</span>
            </div>

            <?php skip_archival: ?>
          </div>
        </div>
    </div>
    </div>
    <div class="body">
      <div class="column">
        <span class="heading major sub">Committee Memberships</span>
        <div class="profile">
          <div class="tiles">
            <?php
            if ($seats->num_rows != 0) {
                # iterate through each seat
                while ($seat = $seats->fetch_assoc()) {
                    # pull committee details
                    $committee_id = $seat['Committee_Committee_ID'];
                    query_committee($conn, $committee_id);

                    # pull committee chairman
                    $chairman = query_committee_chair($conn, $committee_id);

                    # render tile for committee
                    echo "<div class='tile'>";
                          if (!is_null($chairman)) {
                            echo "<div class='heading'>Committee Chair</div>";
                          }
                    echo  " <div class='sub heading'>$committee_name</div>
                            <div>$committee_description</div>
                          </div>";
                }
            } else {
                # error message in case user has no seats
                echo "<div class='center'><i>No committee memberships to display.</i></div>";
            }
            ?>
          </div>
        </div>
      </div>
        </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>
</html>
