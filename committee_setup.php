<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  
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

      $insert_sql = "INSERT INTO `Committee` (`Name`, `Description`) VALUES(?, ?)";
      $stmt = $conn->prepare($insert_sql);
      $stmt->bind_param('ss', $name, $description);
      $stmt->execute();

      // GET NEW COMMITTEE ID
      $new_committee_id = $conn->insert_id;

      // APPOINT SELECTED MEMBERS TO COMMITTEE
      if (isset($_POST['selected_users'])) {

        $appointed_users = $_POST['selected_users'];

        $insert_sql = "";
        foreach ($appointed_users as $user) {
          echo $new_committee_id."   ".$user." / ";
          $insert_sql = "INSERT INTO `Committee Seat` (`Committee_Committee_ID`, `Starting_Term`, `User_CNU_ID`) VALUES('$new_committee_id', now(), '$user')";
          $conn->query($insert_sql) or die($conn->error);
        }
      }
      header("Location: committee_details_admin.php?committee=".$new_committee_id);
      exit();
    }

    // PERMISSIONS CHECK - RETURN TO ELECTION_SELECTION IF NOT ADMIN
    # defined in databaseconnect.php
    validate_inputs($current_user_permissions, 'Admin', 'election_selection.php');

    ?>
  <title>CNU Committees - Committee Setup</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <h2>Setup Committee</h2>
    </header>
    <div class="selection">
      <div class="results">
        <form id="committee" action="committee_setup.php" method="post">
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
                        <input type='checkbox' name='selected_users[]' value='$id'></input>
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
            <input type="text" id="name" name="name" form="committee" maxlength=45 required>
            <label for="description" class="required">Committee Description</label>
            <textarea id="description" name="description" form="committee" maxlength=100 required></textarea>
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
