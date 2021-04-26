<?php session_start(); ini_set('display_errors', 1)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php

      include 'databaseconnect.php';
      $sql = "SELECT CNU_ID FROM User";
      $id_list = $conn->query($sql);
    ?>

  <title>CNU — <?php echo "null"." "."null"; ?></title>
</head>

<body>

  <div class="wrapper">
    <header>
     <?php print_back_button("User Selection", "user_selection.php"); ?>
      <h2>User Details</h2>
    </header>
    <?php
      while ($row = $id_list->fetch_assoc()) {
        $id = $row['CNU_ID'];
        $id_data_request = "SELECT * FROM User WHERE CNU_ID = $id";
        $user = $conn->query($id_data_request)->fetch_assoc();     

        if (isset($_POST[$id])) {
?>
          <div class="body">
            <div class="column">
            <span class="major heading"><?php echo $user['Fname']." ".$user['Lname'] ?></span> 
            <hr>
          <div class="tiles">

            <div class="tile">
              <span class="sub heading">Personal Info</span>
              <hr>
              <div class="list">
                <span class="label">Email:</span>
                <span><?php echo $user['Email'];?></span>

                <span class="label">Birthday:</span>
                <span><?php echo $user['Birthday'];?></span>
              </div>
            </div>

            <div class="tile">
              <span class="sub heading">Other</span>
              <hr>
              <div class="list">
                <span class="label">Race:</span>
                <span><?php echo $user['Race'];?></span>

                <span class="label">Gender:</span>
                <span><?php echo $user['Gender'];?></span>
              </div>
            </div>

            <div class="tile">
              <span class="sub heading">Employment</span>
              <hr>
              <div class="list">
                <span class="label">Department:</span>
                <span><?php echo $user['Department'];?></span>

                <span class="label">Position:</span>
                <span><?php echo $user['Position'];?></span>

                <span class="label">Year of Hiring:</span>
                <span><?php echo $user['Hiring_Year'];?></span>
              </div>
            </div>
          </div>
            </div></div>
<?php
        }
      }
    ?>
  </div>

  <?php $conn->close(); ?>
</body>
</html>