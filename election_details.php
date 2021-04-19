<?php session_start(); ini_set('display_errors', 1); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';
      include 'commonfns.php';

      if (isset($_POST['nominate'])) {
        $election_id = $_POST['election_id'];
        $nominator_id = $_SESSION['user'];
        $nominee_id = $_POST['nominee'];

        $insert_sql = "INSERT INTO `Nomination`
                      SELECT $election_id, $nominator_id, $nominee_id
                      WHERE $nominee_id NOT IN(
                          SELECT Nominee_CNU_ID FROM `Nomination`
                          WHERE Election_Election_ID = '$election_id')";
        $conn->query($insert_sql);
      }

      # get entered election id
      $submitted_id = $_GET['election'];

      # prepare statement (this is done to prevent sql injection)
      $election = $conn->prepare("SELECT * FROM `Election` WHERE Election_ID=?");
      # bind parameter to int
      $election->bind_param('i', $submitted_id);
      # execute statement
      $election->execute();
      # bind results to variables
      $election->bind_result($election_id, $committee_id, $status, $num_seats);
      # fetch row and close connection
      $election->fetch();
      $election->close();

      # get committee info
      $committee_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$committee_id'";
      $committee = $conn->query($committee_sql)->fetch_assoc();

      # functions

      # pulls a user's details based on a given ID. returns the user assoc
      function pull_user(int $user_id)
      {
          global $conn;

          $user_sql = "SELECT * FROM `User` WHERE CNU_ID='$user_id'";
          $user = $conn->query($user_sql)->fetch_assoc();
          return $user;
      }

      # prints nominee tiles or an error message if there are none.
      function print_nominees()
      {
          global $conn;
          global $election_id;

          # pull nomination information for given election
          $noms_sql = "SELECT * FROM `Nomination` WHERE Election_Election_ID = $election_id";
          $noms = $conn->query($noms_sql);

          # error message if there are no nominees
          if ($noms->num_rows < 1) {
              echo "<div class='center'>No nominations have been submitted for this election.</div>";
          } else {
              # for each nominee ...
              while ($row = $noms->fetch_assoc()) {
                  # get nominee information ...
                  $nominee_id = $row['Nominee_CNU_ID'];
                  $user = pull_user($nominee_id);

                  $user_name = $user['Fname']." ".$user['Lname'];

                  $user_info = array(
                              'Department'=>$user['Department'],
                              'Position'=>$user['Position'],
                              'Race'=>$user['Race'],
                              'Gender'=>$user['Gender']);

                  # ... and print tiles
                  echo "<div class='tile'>";
                  echo "<span class='heading sub'>$user_name</span>";
                  foreach ($user_info as $label => $detail) {
                      echo "<span><i>$label</i>: $detail</span>";
                  }
                  echo "</div>";
              }
          }
      }

    ?>

  <title>CNU — Election Details</title>
</head>

<body>

  <div class="wrapper">
    <header>
      <h2>Election Details</h2>
    </header>

    <div class="body">
      <?php
        if (is_null($election_id)) {
            # No election found error message.

            echo "<div class='center'>No election found with entered ID.</div></div>";
            goto no_election;
        }
      ?>
      <div class="column">
        <!-- Details -->
        <span class='major heading'><?php echo $committee['Name']; ?> Election</span>
        <hr>
        <div class="block">
          <span class="emphasis"><?php echo $num_seats." seat".($num_seats==1?'':'s'); ?> being elected</span>
          Election Status: <?php echo $status; ?>
        </div>
      </div>
      <div class="column">
        <!-- Standard User Options -->
        <?php

            switch ($status) {
              case 'Nomination':
                echo "<form action='election_nominate_user.php' method='get'><button name='election' value='$election_id'>Nominate User</button></form>";
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
