<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php include 'databaseconnect.php';

      # query committee details
      $com_id = intval($_GET['committee']);
      $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$com_id'";
      $com = $conn->query($com_sql)->fetch_assoc();

      # query chairman
      $chair_sql = "SELECT User_CNU_ID FROM `Chairman` WHERE Committee_Committee_ID='$com_id'";
      $chair_id = $conn->query($chair_sql)->fetch_assoc()['User_CNU_ID'];

      # query committee seats info
      $com_seats_sql = "SELECT * FROM `Committee Seat` WHERE Committee_Committee_ID='$com_id'";
      $com_seats = $conn->query($com_seats_sql);

      # query any running elections
      $election_sql = "SELECT * FROM `Election` WHERE Committee_Committee_ID='$com_id' AND NOT Status='Complete'";
      $election = $conn->query($election_sql)->fetch_assoc();
    ?>

  <title>CNU — <?php echo $com['Name'];?></title>
</head>

<body>

  <!-- TODO: Create PHP script to generate this page for all
               committees in a report    -->

  <div class="wrapper">
    <h2>Committee Details</h2>
    <div class="body">
      <div class="column">
        <span class='major heading'><?php echo $com['Name'];?></span>
        <div class="block"><?php echo $com['Description'];?></div>
      </div>
      <div class="column">

        <?php

            if (is_null($election)) {

              # TODO: make this functional

                echo "<a href='#'><button type='button'>Start Election</button></a>";
            } else {
                echo "<a href='election_details.php?election=".$election['Election_ID']."'><button type='button'>View Election</button></a>";
            }

          ?>

        <!-- TODO: make this functional -->

        <a href="#"><button type="button">Appoint User to New Seat</button></a>
      </div>
    </div>
    <div class="body">
      <div class="column">
      <span class='major heading'>Committee Seats</span>
      <hr>
      <div class="tiles">
        <?php
              # for each seat
              while ($seat = $com_seats->fetch_assoc()) {

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
                  echo "<div class='tile'><span class='centered'>Seat up for election</span></div>";
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
