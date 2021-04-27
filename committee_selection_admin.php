<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">

  <?php
  
  require 'databaseconnect.php';
  require 'committee_functions.php';
  
  //  PERMISSIONS CHECK (ADMIN ONLY)
  # Defined in databaseconnect.php
  validate_inputs($current_user_permissions, 'Admin', 'election_selection.php');
  
  ?>

  <script type="text/javascript" src="js/selection_menu.js"></script>

  <title>CNU Committees - Committee Selection</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <?php print_back_button("Homepage", "homepage_super.php"); ?>
      <h2>Committees</h2>
    </header>
    <div class="selection">
      <div class="results">
        <?php

            // Pull user details to generate checklist
            $sql = "SELECT * FROM Committee";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  // Iterate through all committees
                  $id = $row['Committee_ID'];
                  $name = $row['Name'];
                  $description = $row['Description'];

                  $chair_id = query_committee_chair($conn, $id);
                  if ($chair_id != NULL) {
                    $chair = query_user($chair_id);
                    $chair_name = $chair['Fname']." ".$chair['Lname'];
                  } else {
                    $chair_name = "Not assigned";
                  }
                  
                  // Create div populated with relevant information
                  // and checkbox/details options.

                  // Checkbox belongs to options form, placed here for visuals
                  echo "<div class='data'> <label for='$id'><b>$name</b><br>$description<br>Chair: <i>$chair_name</i></label>
                      <div class='result_choices'>
                        <input class='checkbox' type='checkbox' name='$id' form='options'></input>
                        <a href='committee_details.php?committee=$id'><button>Details >></button></a>
                      </div>
                    </div>";
                }
            } else {
                // Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }

            ?>
      </div>
        <!--TODO: add report generation href-->
        <h4>Options</h4>
        <hr>
        <!-- Selection Options -->
        <div>
          <!-- TODO: implement these -->
          <button id="select_all" type="button" name="select_all">Select All</button>
          <button id="deselect_all" type="button" name="deselect_all">Deselect All</button>
        </div>
        <hr>
       
        <!-- Administrative Options -->
        <form action="committee_report.php" method="post" id='options'></form>
        <div class="choices">
        <a href="committee_setup.php"><button class="admin" type="button" name="add_user">Add Committee</button></a>
        <input id="report" type="submit" name="report" value="Generate Report on Selected" form='options'>
          <!--TODO: implement this -->
        </div>
      </div>
      
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
