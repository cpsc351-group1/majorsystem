<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';

    // ACTIVE ELECTIONS SQL
    $active_elections_sql = "SELECT `Committee_Committee_ID` FROM `Election`
                         WHERE `Status` = 'Nomination' OR `Status` = 'Voting'";

    if (isset($_POST['create'])) {
      $committee = $_POST['committee'];
      $number_seats = $_POST['number_seats'];

      $insert_sql = "INSERT INTO `Election` (`Committee_Committee_ID`, `Number_Seats`, `Status`)
                     SELECT $committee, $number_seats, 'Nomination'
                     WHERE $committee NOT IN($active_elections_sql)";
      $conn->query($insert_sql) or die($conn->error);
      $new_election_id = $conn->insert_id;

      header("Location: election_details.php?election=".$new_election_id);
      exit();
    }

    // GET
    # if committee variable in GET, pull
    if (!is_null($_GET['committee'])) {
      $entered_committee_id = $_GET['committee'];
    }

    // PERMISSIONS CHECK - RETURN TO ELECTION_SELECTION IF NOT ADMIN
    # defined in databaseconnect.php
    validate_inputs($_SESSION['permissions'], 'Admin', 'election_selection.php');

    // SELECT ELIGIBLE COMMITTEES

    $committee_sql = "SELECT * FROM `Committee`
                      WHERE `Committee_ID` NOT IN($active_elections_sql)";
    $committees = $conn->query($committee_sql);

    ?>
  <title>CNU Committees - Setup Election</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Setup Election</h2>
    </header>
    <div class="selection">
      <div class="results">
        <form id="election" action="election_setup.php" method="post">
          <?php

          // PRINT SELECTED COMMITTEES

          if ($committees->num_rows > 0) {
              # Iterate through all selected committees
              while ($row = $committees->fetch_assoc()) {
                  // Store user data
                  $id = $row['Committee_ID'];
                  $name = $row['Name'];
                  $description = $row['Description'];

                  # Generate tiles for each available user
                  # Radio belongs to options form, placed here for visuals
                  echo "<div class='data'> <label for='$id'><b>$name</b><br>$description</label>
                        <div class='result_choices'><input type='radio' name='committee' id='$id' value='$id'";
                  # Setup preselected committee
                  if (isset($entered_committee_id)) {
                    echo ($id == $entered_committee_id ? "checked='checked'" : '');
                  }
                  echo "required></div></div>";
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
            <label for="number_seats" class="required">Number of Seats to Elect</label>
            <input type="number" id="number_seats" name="number_seats" min=1 max=100 form="election" required>
        </div>
        <hr>
        <!-- Administrative Options -->
        <div class="choices">
          <input type="submit" name="create" value="Create Election" form="election">
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
