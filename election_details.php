<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';

      $election_id = intval($_GET['election']);
      $election_sql = "SELECT * FROM `Election` WHERE Election_ID='$election_id'";
      $election = $conn->query($election_sql)->fetch_assoc();
      if (is_null($election)) {
        header('Location: 404.php');
      }

      $committee_id = intval($election['Committee_Committee_ID']);
      $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$committee_id'";
      $committee = $conn->query($committee_sql)->fetch_assoc();

      $status = $election['Status'];
    ?>

  <title>CNU — Election Details</title>
</head>

<body>

  <!-- TODO: Create PHP script to generate this page for all
               committees in a report    -->

  <div class="wrapper">
    <h2>Election Details</h2>
    <div class="body">

      <div class="column">
        <!-- Details -->
        <span class='major heading'><?php echo $committee['Name']; ?> Election</span>
        <hr>
        <div class="block">
          <span class="emphasis"><?php $num_seats=$election['Number_Seats']; echo $num_seats." seat".($num_seats==1?'':'s'); ?> being elected</span>
          Election Status: <?php echo $status; ?>
        </div>
      </div>
      <div class="column">
        <!-- Standard User Options -->
        <?php

            switch ($status) {
              case 'Nomination':
                echo "<a><button type='button' name='nomination'>Nominate User</button></a>";
                break;
              case 'Voting':
                echo "<a><button type='button' name='vote'>Vote in Election</button></a>";
                break;
              case 'Complete':
                echo "<span class='centered'>This election has been completed.</span>";
                break;
            }
          ?>

      </div>
    </div>
    <div class="body">

    </div>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
