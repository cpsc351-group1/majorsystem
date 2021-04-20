<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';

    # pull posted committee variable
    $entered_id = $_GET['committee'];

    # pull committee information using $_GET
    $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID=?";
    # prepare statement (to prevent mysql injection)
    $com_stmt = $conn->prepare($com_sql);
    # bind inputs
    $com_stmt->bind_param('i', $entered_id);
    # execute statement
    $com_stmt->execute();
    # bind results to variables
    $com_stmt->bind_result($committee_id, $committee_name, $committee_description);
    # fetch row and close
    $com_stmt->fetch();
    $com_stmt->close();

    # return to selection page if invalid id thrown
    validate_inputs(is_null($committee_id), 0, 'committee_selection_admin.php');

    # pull all nominee details
    $members_sql = "SELECT * FROM `User` WHERE CNU_ID NOT IN(
                      SELECT User_CNU_ID FROM `Committee Seat`
                      WHERE Committee_Committee_ID=$committee_id)";
    $members = $conn->query($members_sql);

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
