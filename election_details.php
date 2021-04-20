<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';

      # INSERT SQL FOR SUBMITTING NOMINATIONS
      if (isset($_POST['submit_nomination'])) {
        # pull posted information
        $election_id = $_POST['election_id'];
        $nominator_id = $_SESSION['user'];
        $nominee_id = $_POST['nominee'];

        # insert nomination if not exists
        $insert_sql = "INSERT INTO `Nomination`
                      SELECT $election_id, $nominator_id, $nominee_id
                      WHERE $nominee_id NOT IN(
                          SELECT Nominee_CNU_ID FROM `Nomination`
                          WHERE Election_Election_ID = '$election_id')";
        $conn->query($insert_sql);
      }

      # INSERT SQL FOR SUBMITTING VOTES
      if (isset($_POST['submit_vote'])) {
        # pull posted information
        $election_id = $_POST['election_id'];
        $voter_id = $_SESSION['user'];
        $vote_id = $_POST['vote'];

        # delete current user vote in current election
        $delete_sql = "DELETE FROM `Vote` WHERE
                        Voter_CNU_ID = $voter_id AND
                        Election_Election_ID = $election_id";
        $conn->query($delete_sql);

        # insert updated vote if not exists
        $insert_sql = "INSERT INTO `Vote`
                      SELECT $election_id, $voter_id, $vote_id
                      WHERE $vote_id NOT IN(
                          SELECT Votee_CNU_ID FROM `Vote`
                          WHERE Election_Election_ID = '$election_id')";
        $conn->query($insert_sql);
      }

      # get entered election id
      $entered_id = $_GET['election'];

      # pull election information using $_GET
      $election_sql = "SELECT * FROM `Election` WHERE Election_ID=?";
      # prepare statement (this is done to prevent sql injection)
      $election = $conn->prepare($election_sql);
      # bind parameter to int
      $election->bind_param('i', $entered_id);
      # execute statement
      $election->execute();
      # bind results to variables
      $election->bind_result($election_id, $committee_id, $status, $num_seats);
      # fetch row and close
      $election->fetch();
      $election->close();

      # return to selection page if invalid id thrown
      validate_inputs(is_null($election_id), 0, 'election_selection.php');

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
          global $status;

          # pull nomination information for given election
          $noms_sql = "SELECT * FROM `Nomination` WHERE Election_Election_ID = $election_id";
          $noms = $conn->query($noms_sql);

          # username
          $voter_id = $_SESSION['user'];

          # pull previous vote in this election
          $votes_sql = "SELECT Votee_CNU_ID FROM `Vote`
                          WHERE Election_Election_ID=$election_id
                          AND Voter_CNU_ID=$voter_id";
          $previous = $conn->query($votes_sql)->fetch_assoc()['Votee_CNU_ID'];

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

                  // voter status, if applicable (in voting status)
                  // detect if user is same as previously voted
                  $checked = $nominee_id == $previous;
                  // render disabled radio badges to show vote
                  if ($status=='Voting') {
                      echo "<input type='radio' disabled='disabled' ".($checked?'checked':'').">";
                  }

                  echo "<span class='heading sub'>$user_name</span>";
                  echo "<div class='list'>";
                  // user information
                  foreach ($user_info as $label => $detail) {
                      echo "<div>$label</div> <div>$detail</div>";
                  }
                  echo "</div></div>";
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
