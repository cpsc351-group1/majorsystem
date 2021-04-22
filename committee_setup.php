<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';

    //  SELECT ALL COMMITTEES
      $committee_sql = "SELECT * FROM `Committee`";
      $committees = $conn->query($committee_sql);

    //  SELECT ALL USERS
      $user_sql = "SELECT * FROM `User`";
      $users = $conn->query($user_sql);

    //  CREATE COMMITTEE INSERT
    if (isset($_POST['create'])) {
      $name = $_POST['name'];
      $description = $_POST['description'];

      $selected_users = $_POST['users'];

      $insert_sql = "INSERT INTO `Committee` (Name, Description) SELECT $name, $description";
      $conn->query($insert_sql);

      $new_committee_id = $conn->insert_id;

      header("Location: committee_details_admin.php?election=".$new_committee_id);
      exit;
    }

    // PERMISSIONS CHECK - RETURN TO ELECTION_SELECTION IF NOT ADMIN
    # defined in databaseconnect.php
    validate_inputs($_SESSION['permissions'], 'Admin', 'election_selection.php');

    ?>
  <title>CNU Committees - Committee Setup</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Setup Committee</h2>
    </header>
    <div class="selection">
      <div class="results">
        <form id="committee" action="election_setup.php" method="post">
          <div class='center emphasis'>Appoint Users</div>
          <hr>
          <?php

          // PRINT SELECTED COMMITTEES

          if ($users->num_rows > 0) {
              # Iterate through all selected committees
              while ($row = $users->fetch_assoc()) {
                  // Store user data
                  $id = $row['CNU_ID'];
                  $name = $row['Fname'].' '.$row['Lname'];
                  $dept = $row['Department'];
                  $pos = $row['Position'];

                  // Create div populated with relevant information
                  // and checkbox/details options.

                  // Checkbox belongs to options form, placed here for visuals
                  echo "<div class='data' id='$id'> <label for='$id'><b>$name</b><br>$dept<br>$pos</label>
                      <div class='result_choices'>
                        <input type='checkbox' name='selected_users' value='$id'></input>
                      </div>
                    </div>";
              }
          } else {
              # Display blank result if no search results
              echo "<div class='center'>No results found for current search settings.</div>";
          }
          ?>
        </form>
      </div>
      <div class='options'>
        <!--TODO: add functionality to search options-->
        <h4>Options</h4>
        <!-- Search Bar -->
        <div class='searchbar'>
          <input type="text" name="search" placeholder="Search...">
        </div>
        <hr>
        <div class='details'>
            <label for="name" class="required">Committee Name</label>
            <input type="text" id="name" name="name" form="committee" required>
            <label for="description" class="required">Committee Description</label>
            <textarea id="description" name="description" form="committee" required></textarea>
        </div>
        <hr>
        <!-- Administrative Options -->
        <div class="choices">
          <input type="submit" name="create" value="Create Committee" form="committee">
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
