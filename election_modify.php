<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';
      include 'election_functions.php';

      //  GET
      $entered_id = $_GET['election'];

      //  SELECT ELECTION INFO
      # defined in election_functions.php
      query_election($entered_id);

      //  POSTED STATUS UPDATE
      # not sensitive to SQL injection
      if (isset($_POST['status'])) {
        $new_status = $_POST['status'];

        $status_sql = "UPDATE `Election` SET `Status` = '$new_status' WHERE `Election_ID` = '$entered_id'";
        $conn->query($status_sql);

        // if election gets moved to complete, appoint winners automatically
        if ($new_status == 'Complete') {
          $noms = query_election_nominees($election_id);

          // get list of vote counts [user_id] => [num_votes]
          $vote_counts = array();

          while ($nominee = $noms->fetch_assoc()) {
            $votee_id = $nominee['Nominee_CNU_ID'];
            $vote_counts[$votee_id] = query_user_votes($conn, $election_id, $votee_id);
          }

          // sort them by value
          asort($vote_counts);

          // for the number of seats being appointed, insert users at the end of the array
          for ($seat_count = $num_seats; $seat_count>0; $seat_count--) {
            // pull last key
            $votee_id = array_key_last($vote_counts);
            array_pop($vote_counts);

            // insert as committee seat
            $insert_sql = "INSERT INTO `Committee Seat` (Committee_Committee_ID, Starting_Term, User_CNU_ID)
                           VALUES ('$committee_id', DATE(NOW()), '$votee_id')";

            $conn->query($insert_sql) or die($insert_sql."<br>".$conn->error);

            if ($committee_id == 1) {
              $superuser_sql = "UPDATE `User`
                                SET `Permissions` = 'Super'
                                WHERE CNU_ID = $votee_id";
              $conn->query($superuser_sql);
            }
          }

          // and go to the new committee page
          header("Location: committee_details_admin.php?committee=".$committee_id);
          exit();
        }

        // otherwise, return to election page

        header("Location: election_details_admin.php?election=".$entered_id);
        exit();
      }

      // UPDATED SEAT COUNT
      if (isset($_POST['update_seats'])) {
        $new_seat_count = $_POST['seat_count'];

        $insert_sql = "UPDATE `Election`
                       SET `Number_Seats` = '$new_seat_count'
                       WHERE Election_ID = $election_id";
        $conn->query($insert_sql);

        header("Location: election_details_admin.php?election=".$entered_id);
      }

      // DELETE ELECTION SQL
      if (isset($_POST['delete'])) {
        // delete dependencies

        $delete_nominations_sql = "DELETE FROM `Nomination`
                                   WHERE Election_Election_ID = $election_id";
        $delete_votes_sql = "DELETE FROM `Vote`
                             WHERE Election_Election_ID = $election_id";
        $conn->query($delete_nominations_sql) or die($conn->error);
        $conn->query($delete_votes_sql) or die($conn->error);
        
        // delete election

        $delete_sql = "DELETE FROM `Election`
                       WHERE Election_ID = $election_id";
        $conn->query($delete_sql) or die($conn->error);

        // redirect
        header("Location: election_selection.php");
        exit();
      }

      //  VALIDATE GET INPUTS
      # return to selection page if invalid id thrown
      # defined in databaseconnect.php
      validate_inputs(is_null($election_id), 0, 'election_selection.php');

      //  COMMITTEE INFO
      # get committee info
      $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$committee_id'";
      $committee = $conn->query($committee_sql)->fetch_assoc();

      // NOMINATIONS INFO
      # defined in election_functions.php
      query_election_nominees($election_id);

      // PREVIOUS VOTE INFO
      # defined in election_functions.php
      query_election_votes($election_id);
    ?>

  <title>CNU — Election Details</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
     <?php print_back_button("Election Details", "election_details.php", array('election' => $election_id)); ?>
      <h2>Modify Election</h2>
    </header>

    <div class="body">
      <div class="column">
        <!-- Details -->
        <span class='major heading'><?php echo $committee['Name']; ?> Election</span>
        <div class="tiles center">
          <div class="tile">
            <?php
                if (in_array($status, array("Voting", "Nomination"))) {
                  $num_nominees = $noms->num_rows;
                  echo "<form class='center inputs tile' id='update_seats' action='election_modify.php?election=$election_id' method='post'>
                          <label for='num_seats'>Number of Seats</label>
                          <input id='num_seats' name='seat_count' value='$num_seats' type='number' min='1' ".( $status=='Voting' ? "max='$num_nominees'" : "" )." required></input>
                          <input type='submit' name='update_seats' value='Update'></input>
                        </form>";
                } else {
                  echo "<div class='heading sub'>".$num_seats." seat".($num_seats==1?'':'s')." being elected</div>";
                }
              ?>
              <div> Election Status: <b><?php echo $status; ?></b></div>
          </div>
        </div>
      </div>
      <div class="column">
        <!-- Standard User Options -->
        <?php
            $delete_html = "<form id='delete' action='election_modify.php?election=$election_id' method='post'>
                                    <button class='danger' name='delete' value='$election_id'>Delete Election</button>
                                  </form>";
            # different options render based on status
            switch ($status) {
              case 'Nomination':
                // nominate user option
                $disabled = $noms_count < $num_seats;
                echo "<form id='status' action='election_modify.php?election=$election_id' method='post'>"
                        .($disabled ? "<div class='tip bottom'>Less nominations than electable seats</div>" : "")
                        ."<button class='admin' name='status' value='Voting'".($disabled ? 'disabled' : '').">End Nominations</button>
                      </form>
                      $delete_html";
                
                break;
              case 'Voting':
                // vote in election option
                $disabled = !($votes_count >= $num_seats and $noms_count != $num_seats);
                echo "<form id='status' action='election_modify.php?election=$election_id' method='post'>"
                        .($disabled ? "<div class='tip bottom'>Less votes submitted than electable seats</div>" : "")
                        ."<button class='admin' name='status' value='Complete'".($disabled ? 'disabled' : '').">End Election</button>
                      </form>
                      $delete_html";
                break;

              case 'Complete':
                // election complete (no option)
                echo "<span class='center'>This election has been completed.</span>";
                break;
            }
          ?>

      </div>
    </div>
    <div class="body">
      <div class="column">
        <span class="major heading">Nominees</span>
        <div class="tiles">
          <?php
            print_nominees();
          ?>
        </div>
      </div>
    </div>
    <?php
      no_election:
      # jump to here if no election of matching id is found.
    ?>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
