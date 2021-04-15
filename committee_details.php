<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php include 'databaseconnect.php';

      $com_id = intval($_GET['committee']);
      $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$com_id'";

      $com = $conn->query($com_sql)->fetch_assoc();

      $chair_sql = "SELECT User_CNU_ID FROM `Chairman` WHERE Committee_Committee_ID='$com_id'";
      $chair_id = $conn->query($chair_sql)->fetch_assoc()['User_CNU_ID'];


      $com_seats_sql = "SELECT * FROM `Committee Seat` WHERE Committee_Committee_ID='$com_id'";

      $com_seats = $conn->query($com_seats_sql);
    ?>

  <title>CNU — <?php echo $com['Name'];?></title>
</head>

<body>

  <!-- TODO: Create PHP script to generate this page for all
               committees in a report    -->

  <div class="wrapper">
    <h2>Committee Details</h2>
    <div class="profile">
      <div class="body">
        <div class="column">
          <span class='major heading'><?php echo $com['Name'];?></span>
          <div class="block"><?php echo $com['Description'];?></div>
        </div>
        <div class="column">

          <!-- TODO: implement these -->

          <button type="button" name="election">Start Election for New Seat</button>
          <button type="button" name="appoint">Appoint User to New Seat</button>
        </div>
      </div>
    </div>
    <div class="profile">
      <div class="body block">
        <span class='major heading'>Committee Seats</span>
      </div>
      <div class="body">
        <div class="tiles">
          <?php
              while ($seat = $com_seats->fetch_assoc()) {

                $user_id = intval($seat['User_CNU_ID']);

                $user_sql = "SELECT CNU_ID, Fname, Lname, Department, Position, Photo FROM `User` WHERE CNU_ID='$user_id'";
                $user = $conn->query($user_sql)->fetch_assoc();

                $is_chair = $user['CNU_ID'] == $chair_id;

                $ending_term = $seat['Ending_Term'] ?? 'Present';

                // TODO: add photos, if desired :)

                echo "<div class='block'>";
                echo $is_chair ? "<span class='heading'>Committee Chair</span><br>" : "";
                echo "<span class='sub heading'>".$user['Fname']." ".$user['Lname']."</span><br>"
                      .$user['Department']     .", "   .$user['Position']                ."<br>"
                      .$seat['Starting_Term']  ." - "  .$ending_term;

                echo "</div>";
              }
            ?>
        </div>
      </div>
    </div>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
