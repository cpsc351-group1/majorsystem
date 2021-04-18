<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';
      include 'commonfns.php';

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
      <div class="tiles">
        <?php
            function pull_users()
            {
                $users_sql = "SELECT * FROM `Users`";
                $users = $conn->query($users_sql);
                return $users;
            }

            function print_nominees($election_id)
            {
                global $conn;

                $noms_sql = "SELECT * FROM `Nomination` WHERE Election_Election_ID = $election_id";
                $noms = $conn->query($noms_sql);

                if ($noms->num_rows == 0) {
                    echo "<div class='center'>No nominations have been submitted for this election.</div>";
                } else {
                    $users = pull_users();


                    while ($row = $noms->fetch_assoc()) {
                        $user = $users[$row['Nominee_CNU_ID']];

                        $user_info = array(
                                    'Name'=>$user['Name'],
                                    'Department'=>$user['Department'],
                                    'Position'=>$user['Position'],
                                    'Race'=>$user['Race'],
                                    'Gender'=>$user['Gender']);

                        echo "<div class='tile'>";
                        foreach ($user_info as $label => $detail) {
                            echo "<span>$label: $detail</span>";
                        }
                        echo "</div>";
                    }
                }
            }

            switch ($status) {
              case 'Nomination':
                print_nominees($election_id);
                break;
            }
        ?>
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
