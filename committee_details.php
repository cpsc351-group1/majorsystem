<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/profile.css" type="text/css">
    <?php include 'databaseconnect.php';

      $com_id = intval($_GET['committee']);
      $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$com_id'";

      $com = $conn->query($com_sql)->fetch_assoc();


      // $com_users_sql = "SELECT * FROM `Committee Seat` WHERE committee_committee_ID='$comm_id'";
      // $com_users = $conn->query($com_users_sql);
    ?>

    <title>CNU — <?php echo $comm['Name'];?></title>
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
    </div>
    <?php $conn->close(); ?>
  </body>
</html>
