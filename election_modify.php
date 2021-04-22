<?php session_start(); ?>
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

  <div class="wrapper">
    <header>
      <h2>Election Details</h2>
    </header>

    <div class="body">
      <div class="column">
        <!-- Details -->
        <span class='major heading'><?php echo $committee['Name']; ?> Election</span>
        <hr>
        <div class="block">
          <div class="heading sub"><?php echo $num_seats." seat".($num_seats==1?'':'s'); ?> being elected</div>
          <div> Election Status: <?php echo $status; ?></div>
        </div>
      </div>
      <div class="column">
        <!-- Standard User Options -->
        <?php
            # different options render based on status
            switch ($status) {
              case 'Nomination':
                // nominate user option
                $disabled = $noms_count < $num_seats;
                echo "<form action='election_details_admin.php?election=$election_id' method='post'>"
                        .($disabled ? "<div class='tip'>Less nominations than electable seats</div>" : "")
                        ."<input type='hidden' name='election' value='$election_id'>"
                        ."<button name='status' value='Voting'".($disabled ? 'disabled' : '').">End Nominations</button>
                      </form>";
                
                break;
              case 'Voting':
                // vote in election option
                $disabled = !($votes_count > $num_seats or $noms_count == $num_seats);
                echo "<form action='election_details_admin.php?election=$election_id' method='post'>"
                        .($disabled ? "<div class='tip'>Less votes submitted than electable seats</div>" : "")
                        ."<input type='hidden' name='election' value='$election_id'>"
                        ."<button name='status' value='Complete'".($disabled ? 'disabled' : '').">End Voting</button>
                      </form>";
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
        <hr>
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
