<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <?php
  
  include 'databaseconnect.php';
  
  //  Send non-admin users back to election selection page
  # Defined in databaseconnect.php
  validate_inputs($_SESSION['permissions'], 'Admin', 'election_selection.php');
  
  ?>

  <title>CNU Committees - Election Setup</title>
</head>

<body>
  <div class="wrapper">
    <header>
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

                  // Create div populated with relevant information
                  // and checkbox/details options.

                  // Checkbox belongs to options form, placed here for visuals
                  echo "<div class='data'> <label for='$id'><b>$name</b><br>$description<br>$pos</label>
                      <div class='result_choices'>
                        <input type='checkbox' name='committee' value='$id' form='create'></input>
                        <a href='committee_details.php?committee=$id'><button>Details</button></a>
                      </div>
                    </div>";
                }
            } else {
                // Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }

            ?>
      </div>
      <form class="options" action="    'x'    " method="post">
        <!--TODO: add report generation href-->
        
        <h4>Options</h4>
        <!-- Search Bar -->
        <!--TODO: add functionality to search options-->
        <div class='searchbar'>
          <input type="text" name="search" placeholder="Search...">
        </div>

        <hr>
        <!-- Selection Options -->
        <div>
          <!-- TODO: implement these -->
          <button type="button" name="select_all">Select All</button>
          <button type="button" name="deselect_all">Deselect All</button>
        </div>

        <hr>
        <!-- Administrative Options -->

        <div class="emphasis">
          <a href="#"><button type="button" name="add_user">Add Committee</button></a>
          <!--TODO: implement this-->
          <input type="submit" name="report" value="Generate Report on Selected">
          <!--TODO: implement this -->
        </div>
      </form>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
