<?php session_start(); ini_set('display_errors, 1'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php
      include 'databaseconnect.php';

      if (isset($_POST['appoint'])) {
        $committee_id = $_POST['committee_id'];
        $user = $_POST['user'];

        $insert_sql = "INSERT INTO `Committee Seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
                      SELECT $committee_id, 'Spring 2021', NULL, $user
                      WHERE $user NOT IN(
                          SELECT User_CNU_ID FROM `Committee Seat`
                          WHERE Committee_Committee_ID = '$committee_id')";
        $conn->query($insert_sql);
      }

      # pull posted committee variable
      $entered_id = $_GET['committee'];

      # pull committee information using $_GET
      $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID=?";
      # prepare statement (to prevent mysql injection)
      $com_stmt = $conn->prepare($com_sql);
      # bind inputs
      $com_stmt->bind_param('i', $entered_id);
      # execute statement
      $com_stmt->execute();
      # bind results to variables
      $com_stmt->bind_result($committee_id, $committee_name, $committee_description);
      # fetch row and close
      $com_stmt->fetch();
      $com_stmt->close();

      # return to selection page if invalid id thrown
      validate_inputs(is_null($committee_id), 0, 'committee_selection.php');

      # query chairman
      $chair_sql = "SELECT User_CNU_ID FROM `Chairman` WHERE Committee_Committee_ID='$committee_id'";
      $chair_id = $conn->query($chair_sql)->fetch_assoc()['User_CNU_ID'];

      # query committee seats info
      $committee_seats_sql = "SELECT * FROM `Committee Seat` WHERE Committee_Committee_ID='$committee_id'";
      $committee_seats = $conn->query($committee_seats_sql);

      # query any running elections
      $election_sql = "SELECT * FROM `Election` WHERE Committee_Committee_ID='$committee_id' AND NOT Status='Complete'";
      $election = $conn->query($election_sql)->fetch_assoc();
    ?>

  <title>CNU — <?php echo $committee_name;?></title>
</head>

<body>

  <!-- TODO: Create PHP script to generate this page for all
               committees in a report    -->

  <div class="wrapper">
    <header>
      <h2>Committee Details</h2>
    </header>
    <div class="body">
      <div class="column">
        <span class='major heading'><?php echo $committee_name;?></span>
        <div class="block"><?php echo $committee_description;?></div>
      </div>
      <div class="column">

        <?php

            if (is_null($election)) {

              # TODO: make this functional

                echo "<a href='#'><button type='button'>Start Election</button></a>";
            } else {
                $election_id = $election['Election_ID'];
                echo "<form action='election_details.php' method='get'><button name='election' value='$election_id'>View Election</button></form>";
            }

            echo "<form action='committee_appoint_user.php' method='get'><button name='committee' value='$committee_id'>Appoint User to Seat</button></form>";

          ?>

      </div>
    </div>
    <div class="body">
      <div class="column">
        <span class='major heading'>Committee Seats</span>
        <hr>
        <div class="tiles">
          <?php
              # for each seat
              while ($seat = $committee_seats->fetch_assoc()) {

                # get seatholder ID
                  $user_id = intval($seat['User_CNU_ID']);

                  # query seatholder information
                  $user_sql = "SELECT CNU_ID, Fname, Lname, Department, Position, Photo FROM `User` WHERE CNU_ID='$user_id'";
                  $user = $conn->query($user_sql)->fetch_assoc();

                  # check if seatholder is the chair
                  $is_chair = $user['CNU_ID'] == $chair_id;

                  # show ending term if any
                  $ending_term = $seat['Ending_Term'] ?? 'Present';

                  // TODO: add photos, if desired :)

                  # generate block of data for each user
                  echo "<div class='tile'>";
                  echo $is_chair ? "<span class='heading'>Committee Chair</span><br>" : "";
                  echo "<span class='sub heading'>".$user['Fname']." ".$user['Lname']."</span><br>"
                      .$user['Department'].", ".$user['Position']."<br>"
                      .$seat['Starting_Term']." - ".$ending_term;
                  echo "</div>";
              }

              for ($i=0; $i < $election['Number_Seats']; $i++) {
                  echo "<div class='tile center'>(Seat up for election.)</div>";
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
