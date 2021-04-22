<?php session_start(); ini_set('display_errors', true); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php
      require 'databaseconnect.php';
      require 'committee_functions.php';

      //  GET
      $entered_id = $_GET['committee'];

      //  SELECT COMMITTEE INFO
      $committee = query_committee($conn, $entered_id);

      //  MEMBER APPOINTMENT INSERT
      if (isset($_POST['appoint'])) {
          $user = $_POST['user'];

          $insert_sql = "INSERT INTO `Committee Seat` (Committee_Committee_ID, Starting_Term, Ending_Term, User_CNU_ID)
                      SELECT $committee_id, 'Spring 2021', NULL, $user
                      WHERE $user NOT IN(
                          SELECT User_CNU_ID FROM `Committee Seat`
                          WHERE Committee_Committee_ID = '$committee_id')";
          $conn->query($insert_sql);
      }

      //  CHAIR SELECTION INSERT
      if (isset($_POST['chair'])) {
          $user = intval($_POST['chair']);

          $chair_sql = "UPDATE `Chairman`
                        SET User_CNU_ID = $user
                        WHERE Committee_Committee_ID = $committee_id";
          $conn->query($chair_sql);
      }

      //  MEMBER REMOVAL INSERT
      if (isset($_POST['delete'])) {
          $user = intval($_POST['delete']);

          $delete_sql = "DELETE FROM `Committee Seat`
                         WHERE User_CNU_ID = $user";
          $conn->query($delete_sql) or die(mysqli_error($conn));
      }

      # return to selection page if invalid id thrown
      validate_inputs(is_null($committee_id), 0, 'committee_selection.php');

      # query chairman
      $chair_id = query_committee_chair($conn, $committee_id);

      # query committee seats info
      $committee_seats = query_committee_seats($conn, $committee_id);

      # query any running elections
      $election = query_committee_election($conn, $committee_id);
    ?>

  <title>CNU — <?php echo $committee_name;?></title>
</head>

<body>
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

                // CREATE ELECTION

                echo "<form action='election_setup.php' method='get'><button name='committee' value='$committee_id'>Start Election</button></form>";
            } else {

                // VIEW ELECTION

                $election_id = $election['Election_ID'];
                echo "<form action='election_details_admin.php' method='get'><button name='election' value='$election_id'>View Election</button></form>";
            }

            // ADD USER TO COMMITTEE

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
                  // TODO: remove/chair options
                  echo "<div class='member_options'>"
                          .($is_chair ? "" : "<form action='committee_details_admin.php?committee=$committee_id' method='post'><span class='tip'>Appoint as Chairman</span><button name='chair' value='$user_id'>★</button></form>
                                              <form action='committee_details_admin.php?committee=$committee_id' method='post'><span class='tip'>Delete User</span><button name='delete' value='$user_id'>X</button></form>");
                  echo "</div>";
                  // name and employment details
                  echo "<span class='sub heading'>".$user['Fname']." ".$user['Lname']."</span><br>";
                  // displays if user is committee chair
                  echo ($is_chair ? "<span class='heading'>Committee Chair</span><br>" : "")
                       .$user['Department'].", ".$user['Position']."<br>"
                       .$seat['Starting_Term']." - ".$ending_term;
                  echo "</div>";
              }

              # Render blank tiles for every seat currently up for election

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
