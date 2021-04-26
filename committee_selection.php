<?php session_start();?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <?php
  
  require 'databaseconnect.php';
  require 'committee_functions.php';
  
  //  PERMISSIONS REDIRECTS
  # pulled from databaseconnect.php
  
  admin_redirect($current_user_permissions, "committee_selection_admin.php");

  super_redirect($current_user_permissions, "committee_selection_super.php");

  ?>

  <title>CNU Committees - Committees</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <?php print_back_button("Homepage", "homepage.php"); ?>
      <h2>Committees</h2>
    </header>
    <div class="selection">
      <div class="results">
        <?php

            // Pull user details to generate checklist
            $sql = "SELECT * FROM Committee";
            $result = $conn->query($sql);

            // Store user details to array
            for ($a_result = array(); $row = $result->fetch_assoc(); $a_result[] = $row);

            if ($result->num_rows > 0) {
                // Iterate through all users
                foreach ($a_result as $row) {
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
      <div class="options">
        <!--TODO: add report generation href-->
        
        <span class="sub heading">Options</span>

        <hr>

        <div>
            <button id="select_all" type="button" name="select_all">Select All</button>
            <button id="deselect_all" type="button" name="deselect_all">Deselect All</button>
        </div>

      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
