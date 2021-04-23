<?php session_start(); ini_set('display_errors',true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';
    require 'election_functions.php';

    # get entered election id
    $entered_id = $_GET['election'];

    //  GET ELECTION INFO
    query_election($entered_id);

    //  VERIFY ENTERED ID
    validate_inputs(isset($election_id), true, 'election_selection.php');


    //  INSERT SQL FOR SUBMITTING NOMINATIONS   
    if (isset($_POST['nominate'])) {
      # pull posted information
      $nominator_id = $_SESSION['user'];
      $nominee_id = $_POST['nominee'];

      # insert nomination if not exists
      $insert_sql = "INSERT INTO `Nomination`
                    SELECT $election_id, $nominator_id, $nominee_id
                    WHERE $nominee_id NOT IN(
                        SELECT Nominee_CNU_ID FROM `Nomination`
                        WHERE Election_Election_ID = '$election_id')";
      $conn->query($insert_sql);

      header("Location: election_details.php?election=".$election_id);
      exit();
    }

    //  VERIFY ELECTION STATUS
    validate_inputs($status, 'Nomination', 'election_selection.php');

    //  PULL COMMITTEE INFO
    query_committee($committee_id);
    $com_name = $committee['Name'];

    //  SELECT AVAILABLE USER DETAILS
    # excludes users currently in the committee / currently in an election for the committee

    $acceptable_sql = "SELECT * FROM `User`
                    WHERE NOT (CNU_ID IN(
                      SELECT `User_CNU_ID` FROM `Committee Seat` WHERE (`Committee_Committee_ID` = '$committee_id') AND (`Ending_Term` IS NULL)
                    ) OR CNU_ID IN(
                      SELECT `Nominee_CNU_ID` FROM `Nomination` WHERE `Election_Election_ID` = '$election_id'
                    ))";

    $acceptable = $conn->query($acceptable_sql);

    ?>
  <title>CNU Committees - Nominate User</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <h2>Nominate User</h2>
      <?php
          echo "<div class='sub heading'>&nbsp;—&nbsp;Election for the $com_name</div>";
        ?>

    </header>
    <div class="selection">
      <div class="results">
        <form id="nomination" action="election_nominate_user.php?election=<?php echo $election_id; ?>" method="post">
          <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
          <?php
          # pull nominee details
          

          if ($acceptable->num_rows > 0) {
              # Iterate through all users
              while ($user = $acceptable->fetch_assoc()) {

                $id = $user['CNU_ID'];
                $name = $user['Fname'].' '.$user['Lname'];
                $dept = $user['Department'];
                $pos = $user['Position'];

                echo "<div class='data'> <label for='$id'><b>$name</b><br>$dept<br>$pos</label>
                    <div class='result_choices'>
                      <input type='radio' name='nominee' id='$id' value='$id' required>
                    </div>
                  </div>";
                }
            } else {
                # Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }

            ?>
        </form>
      </div>

      <div class="options">

          <!--TODO: add functionality to search options-->
          <h4>Options</h4>
          <!-- Search Bar -->
          <div class='searchbar'>
            <input type="text" name="search" placeholder="Search...">
          </div>

          <hr>
          <!-- Administrative Options -->
          <div class="choices">
            <input type="submit" name="nominate" value="Nominate User" form="nomination">
          </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
