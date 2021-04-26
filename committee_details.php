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
      # pull posted committee variable
      $entered_id = $_GET['committee'];

      //  PERMISSIONS REDIRECTS
      # pulled from databaseconnect.php
      admin_redirect($current_user_permissions, "committee_details_admin.php?committee=$entered_id");

      // SELECT COMMITTEE INFO
      query_committee($conn, $entered_id);

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

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
     <?php print_back_button("Committee Selection", "committee_selection.php"); ?>
      <h2>Committee Details</h2>
    </header>
    <div class="body">
      <div class="column">
        <span class='major heading center'><?php echo $committee_name;?></span>
        <div class="block center"><?php echo $committee_description;?></div>
      </div>
      <div class="column">

        <?php

            if (!is_null($election)) {

                # View election, if exists

                $election_id = $election['Election_ID'];
                echo "<form action='election_details.php' method='get'><button name='election' value='$election_id'>View Election</button></form>";
            }
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
              if ($committee_seats->num_rows > 0) {
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
                    // name and employment details
                    echo "<span class='sub heading'>".$user['Fname']." ".$user['Lname']."</span><br>";
                    // displays if user is committee chair
                    echo ($is_chair ? "<span class='heading'>Committee Chair</span><br>" : "")
                         .$user['Department'].", ".$user['Position']."<br>"
                         .$seat['Starting_Term']." - ".$ending_term;
                    echo "</div>";
                }
              } else {
                echo "<div class='center'>No members currently appointed.</div>";
              }

              # Render blank tiles for every seat currently up for election

              if ($election != NULL) {
                for ($i=0; $i < $election['Number_Seats']; $i++) {
                  echo "<div class='tile center greyed'>(Seat up for election.)</div>";
                }
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
