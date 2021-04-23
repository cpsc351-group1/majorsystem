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

      //  PERMISSIONS REDIRECTS
      # defined in databaseconnect.php
      admin_redirect($_SESSION['permissions'], "election_details_admin.php?election=".$entered_id);

      //  SELECT ELECTION INFO
      # defined in election_functions.php
      query_election($entered_id);

      //  INVALID ID REDIRECT
      # defined in databaseconnect.php
      validate_inputs(is_null($election_id), 0, 'election_selection.php');

      //  SELECT COMMITTEE INFO
      $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$committee_id'";
      $committee = $conn->query($committee_sql)->fetch_assoc();

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
                echo "<form action='election_nominate_user.php' method='get'><button name='election' value='$election_id'>Nominate User</button></form>";
                break;
              case 'Voting':
                // vote in election option
                echo "<form action='election_vote_user.php' method='get'><button name='election' value='$election_id'>Vote in Election</button></form>";
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
