<?php session_start(); ini_set('display_errors', 1)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      require 'databaseconnect.php';
      require 'committee_functions.php';
      $sql = "SELECT Committee_ID FROM Committee";
      $id_list = $conn->query($sql);
    ?>

  <title>CNU — <?php echo "null"." "."null"; ?></title>
</head>

<body>

  <div class="wrapper">
    <header>
     <?php print_back_button("Committee Selection", "committee_selection_admin.php"); ?>
      <h2>User Details</h2>
    </header>
    <?php
      while ($row = $id_list->fetch_assoc()) {
        $committee_id = $row['Committee_ID'];
        query_committee($conn, $committee_id);
        $chair_id = query_committee_chair($conn, $committee_id);
        $committee_seats = query_committee_seats($conn, $committee_id);
        $election = query_committee_election($conn, $committee_id);    
        if (isset($_POST[$committee_id])) {
?>
          <div class="body">
            <div class="column">
            <span class="major heading"><?php echo $committee_name ?></span> 
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
        </div></div></div>
<?php
        }
      }
    ?>
  </div>

  <?php $conn->close(); ?>
</body>
</html>