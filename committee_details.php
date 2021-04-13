<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php include 'databaseconnect.php'?>

    <title>CNU Committees — </title>
  </head>
  <body>
    <?php
      echo "Committee details page for ".$_GET['committee'];
    ?>
  </body>

  <?php $conn->close(); ?>
</html>
