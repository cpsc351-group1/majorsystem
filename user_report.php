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

  <!-- TODO: Create PHP script to generate this page for all
               users in a report    -->

  <div class="wrapper">
    <header>
      <h2>User Details</h2>
    </header>
    <?php
      while ($row = $id_list->fetch_assoc()) {
        $id = $row['CNU_ID'];
      

        if (isset($_POST[$id])) {
          echo "<div class=\"body\">";
            echo "<div class=\"column\">";
              echo "details for user $id";
            echo "</div></div>";
        }
      }
    ?>
  </div>

  <?php $conn->close(); ?>
</body>
</html>