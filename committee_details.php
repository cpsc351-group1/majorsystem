<!DOCTYPE html>
<html lang="en" dir="ltr">
  <meta charset="utf-8">

  <head>
    <?php

    /*    CREDENTIALS   */

    $SRVR = "localhost:8889";
    $USER = "root";
    $PASS = "root";
    $TABL = "mydb";

    // Creating connection
    $conn = new mysqli($SRVR, $USER, $PASS, $TABL);

    // Checking connection
    if ($conn->connect_error) {
        die("Oh no! Connection failed: " . $conn->connect_error);
    }
    ?>

    <title>CNU Committees — </title>
  </head>
  <body>
    <?php
      echo "Committee details page for ".$_GET['committee'];
    ?>
  </body>

  <?php $conn->close(); ?>
</html>