<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';
    require 'committee_functions.php';

    //  GET
    $entered_id = $_GET['committee'];

    //  SELECT COMMITTEE INFO
    $committee = query_committee($conn, $entered_id);

    //  INVALID ID REDIRECT
    validate_inputs(is_null($committee_id), 0, 'committee_selection_admin.php');

    //  SELECT NOMINEE DETAILS
    $members = query_election_members($conn, $entered_id);

    ?>
  <title>CNU Committees - Appoint User</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Appoint User</h2>
      <?php
          echo "<div class='sub heading'>&nbsp;—&nbsp; to $committee_name</div>";
        ?>

    </header>
    <div class="selection">
      <div class="results">
        <form id="appointment" action="committee_details_admin.php?committee=<?php echo $committee_id; ?>" method="post">
          <input type="hidden" name="committee_id" value="<?php echo $committee_id;?>">
          <?php
          if ($members->num_rows > 0) {
              # Iterate through all selected users
              while ($row = $members->fetch_assoc()) {
                  // Store user data
                  $id = $row['CNU_ID'];
                  $name = $row['Fname'].' '.$row['Lname'];
                  $dept = $row['Department'];
                  $pos = $row['Position'];

                  # Generate tiles for each available user
                  # Radio belongs to options form, placed here for visuals
                  echo "<div class='data'> <label for='$id'><b>$name</b><br>$dept<br>$pos</label>
                          <div class='result_choices'>
                            <input type='radio' name='user' id='$id' value='$id' required>
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
        <!-- Administrative Options -->
        <div class="emphasis">
          <input type="submit" name="appoint" value="Appoint User" form="appointment">
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
