<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  # pull user id from session
  $user_id = intval($_SESSION['user']);

  # connect to database
  include 'databaseconnect.php';

  # run query
  $sql = "SELECT * FROM `User` WHERE CNU_ID=$user_id";
  $result = $conn->query($sql);

  $user = $result->fetch_assoc();

?>
  <title>CNU Committee Database</title>
  <link href="css/main.css" rel="stylesheet">
</head>

<body>
  <div id="homebox">
    Logged in as <?php echo $user['Fname']." ".$user['Lname']; ?> ------------------------------
    <button><a href="index.php" style="color: white">Sign out</a></button>
  </div>
  <div id="outerbox"><br>
    <div id="innerbox">
      Welcome to the<br>
      UFOC Comittee Database
    </div>
    <div id="innerbox">
      What are you looking for, [User Name]?
    </div><br>
    <button><a href="MajorInterfaceProfile.html" style="color: white">View Profile</a></button>
    <button><a href="MajorInterfaceCommittees.html" style="color: white">Committees</a></button>
    <button><a href="MajorInterfaceElections.html" style="color: white">Elections</a></button>
    <button><a href="MajorInterfaceNotifications.html" style="color: white">Notifications</a></button>
  </div>
</body>


</html>
