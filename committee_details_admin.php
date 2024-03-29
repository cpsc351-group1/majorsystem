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

      //  INVALID ID REDIRECT
      validate_inputs(is_null($committee_id), 0, 'committee_selection.php');
      
      //  PERMISSIONS CHECK (ADMIN ONLY)
      validate_inputs($current_user_permissions, 'Admin', 'election_selection.php');


      //  CHAIR SELECTION INSERT
      if (isset($_POST['chair'])) {
        $user = $_POST['chair'];

        $chair_sql = "INSERT INTO `Chairman` (`Committee_Committee_ID`, `User_CNU_ID`) VALUES('$committee_id', '$user')
                        ON DUPLICATE KEY UPDATE `User_CNU_ID` = VALUES(`User_CNU_ID`)";
        $conn->query($chair_sql) or die($conn->error);

        header("Location: committee_details_admin.php?committee=".$committee_id);
        exit();
      }

      if (isset($_POST['delete_committee'])) {

        $delete_seats_sql = "DELETE FROM `Committee Seat`
                             WHERE Committee_Committee_ID = '$committee_id'";

        $delete_chair_sql = "DELETE FROM `Chairman`
                             WHERE Committee_Committee_ID = '$committee_id'";

        $delete_nominations_sql = "DELETE FROM `Nomination`
                               WHERE Election_Election_ID IN(
                                 SELECT Election_ID FROM `Election`
                                 WHERE `Committee_Committee_ID` = '$committee_id'
                               )";

        $delete_votes_sql = "DELETE FROM `Vote`
                         WHERE Election_Election_ID IN(
                            SELECT Election_ID FROM `Election`
                            WHERE `Committee_Committee_ID` = '$committee_id'
                         )";

        $delete_elections_sql = "DELETE FROM `Election`
                               WHERE Committee_Committee_ID = '$committee_id'";

        $delete_committee_sql = "DELETE FROM `Committee`
                                  WHERE Committee_ID = '$committee_id'";

        $conn->query($delete_seats_sql) or die($conn->error);
        $conn->query($delete_chair_sql) or die($conn->error);
        $conn->query($delete_nominations_sql) or die($conn->error);
        $conn->query($delete_votes_sql) or die($conn->error);
        $conn->query($delete_elections_sql) or die($conn->error);
        $conn->query($delete_committee_sql) or die($conn->error);

        header("Location: committee_selection.php");
        exit();
                            
      }

      //  MEMBER REMOVAL INSERT
      if (isset($_POST['delete_seat'])) {
        $user = intval($_POST['delete_seat']);

        $archive_sql = "UPDATE `Committee Seat`
                        SET `Ending_Term` = now()
                        WHERE `User_CNU_ID` = $user
                        AND `Ending_Term` IS NULL
                        AND `Committee_Committee_ID` = $committee_id";
        $conn->query($archive_sql) or die($user." -> ".$today." -> ".$archive_sql." -> ".mysqli_error($conn));

        // Remove superuser status
        if ($committee_id == 1) {

          $superuser_sql = "UPDATE `User`
                            SET `Permissions` = 'User'
                            WHERE CNU_ID = $user";
          $conn->query($superuser_sql);
        }

        header("Location: committee_details_admin.php?committee=".$committee_id);
        exit();
      }

      //  SELECT CHAIRMAN ID
      $chair_id = query_committee_chair($conn, $committee_id);

      //  SELECT SEAT INFO
      $committee_seats = query_committee_seats($conn, $committee_id);

      //  SELECT ARCHIVED SEAT INFO
      $archived_seats = query_archived_committee_seats($conn, $committee_id);

      //  SELECT CURRENT ELECTION
      $election = query_committee_election($conn, $committee_id);
    ?>

  <title>CNU — <?php echo $committee_name;?></title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <?php print_back_button("Committee Selection", "committee_selection_admin.php"); ?>
      <h2>Committee Details</h2>
    </header>
    <div class="body tiles">
      <div class="tile">
        <span class='major heading center'><?php echo $committee_name;?></span>
        <div class="block center"><?php echo $committee_description;?></div>
      </div>
      <div class="column">

        <?php
            if ($committee_id != 1) {
              echo "<form id='delete_committee' action='committee_details_admin.php?committee=$committee_id' method='post'>
                      <button class='danger' name='delete_committee' value='$committee_id'>Delete Committee</button>
                    </form>";
            }

            if (is_null($election)) {

                // CREATE ELECTION

                echo "<form action='election_setup.php' method='get'><button name='committee' value='$committee_id'>Start Election</button></form>";
            } else {

                // VIEW ELECTION

                $election_id = $election['Election_ID'];
                echo "<form action='election_details_admin.php' method='get'><button name='election' value='$election_id'>View Election</button></form>";
            }

            // ADD USER TO COMMITTEE

            echo "<form action='committee_appoint_user.php' method='get'><button class='admin' name='committee' value='$committee_id'>Appoint User to Seat</button></form>";

          ?>

      </div>
    </div>
    <div class="body">
      <div class="column">
        <span class='major heading'>Committee Seats</span>
        <div class="tiles">
          <?php
              # for each seat
              if ($committee_seats->num_rows > 0) {
                while ($seat = $committee_seats->fetch_assoc()) {

                  # get seatholder ID
                    $user_id = intval($seat['User_CNU_ID']);
  
                    # query seatholder information
                    $user = query_user($user_id);
  
                    # check if seatholder is the chair
                    $is_chair = $user['CNU_ID'] == $chair_id;

                    # check if seatholder is the admin, and the committee is #1 (the UFOC)
                    $ufoc_exclusion = $committee_id == 1;
  
                    # show ending term if any
                    $ending_term = $seat['Ending_Term'] ?? 'Present';
  
                    // TODO: add photos, if desired :)
  
                    # generate block of data for each user
                    echo "<div class='tile'>";
                    // TODO: remove/chair options
                    // name and employment details
                    echo "<span class='sub heading'>".$user['Fname']." ".$user['Lname']."</span>";
                    // displays if user is committee chair
                    echo ($is_chair ? "<span class='heading'>Committee Chair</span>" : "")
                         ."<div>".$user['Department'].", ".$user['Position']."</div>"
                         ."<div>".$seat['Starting_Term']." - ".$ending_term."</div>";
                    if (!$is_chair) {
                      echo "<div class='member_options'>";
                      if (!$ufoc_exclusion) {
                        echo "<form action='committee_details_admin.php?committee=$committee_id' method='post'><span class='tip'>Appoint as Chairman</span><button class='admin' name='chair' value='$user_id'>★</button></form>";
                      }
                      echo "<form action='committee_details_admin.php?committee=$committee_id' method='post'><span class='tip'>Remove User</span><button class='danger' name='delete_seat' value='$user_id'>X</button></form>";
                      echo "</div>";
                    }
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
        <?php if ($archived_seats->num_rows == 0) {goto skip_archived;}?>
        <span class="sub heading">Archived Seats</span>
        <div class="tiles">
          <?php
            while ($archived_seat = $archived_seats -> fetch_assoc()) {
              $user_id = $archived_seat['User_CNU_ID'];

              $user = query_user($user_id);

              $user_name = $user['Fname']." ".$user['Lname'];
              $user_department = $user['Department'];
              $user_position = $user['Position'];
              $user_start = $archived_seat['Starting_Term'];
              $user_end = $archived_seat['Ending_Term'];

              echo "<div class='tile greyed'><b>$user_name</b><br>"
                   ."$user_department, $user_position<br>$user_start — $user_end</div>";
            }
          ?>
        </div>
        <?php skip_archived: ?>
      </div>
    </div>
  </div>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
