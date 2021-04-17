<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/selection.css">
    <?php include 'databaseconnect.php'; ?>

    <title>CNU Committees - System Users</title>
  </head>
  <body>
    <div class="wrapper">
      <header>
        <h2>System Users</h2>
      </header>
      <div class="selection">
        <div class="results">
            <?php

            // Pull user details to generate checklist
            $sql = "SELECT CNU_ID, Fname, Lname, Department, Position FROM User";
            $result = $conn->query($sql);

            // Store user details to array
            for ($a_result = array(); $row = $result->fetch_assoc(); $a_result[] = $row);

            if ($result->num_rows > 0) {
                // Iterate through all users
                foreach ($a_result as $row) {
                    $id = $row['CNU_ID'];
                    $name = $row['Fname'].' '.$row['Lname'];
                    $dept = $row['Department'];
                    $pos = $row['Position'];

                    // Create div populated with relevant information
                    // and checkbox/details options.

                    // Checkbox belongs to options form, placed here for visuals
                    echo "<div class='data' id='$id'> <label name='$id'><b>$name</b><br>$dept<br>$pos</label>
                        <div class='result_choices'>
                          <input type='checkbox' name='$id' form='options'></input>
                          <a href='user_details.php?user=$id'><button>Details</button></a>
                        </div>
                      </div>";
                }
            } else {
                // Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }

            ?>
        </div>
        <form class="options" action="    'x'    " method="post"> <!--TODO: add report generation href-->
        <!--TODO: add functionality to search options-->
          <h4>Options</h4>
          <!-- Search Bar -->
          <div class='searchbar'>
            <input type="text" name="search" placeholder="Search...">
          </div>

          <hr>
          <!-- Selection Options -->
          <div>
            <button type="button" name="select_all">Select All</button>
            <button type="button" name="deselect_all">Deselect All</button>
          </div>

          <hr>
          <!-- Administrative Options
               TODO: convert reporting into an input submit, create hyperlink for add user button-->
          <div class="emphasis">
            <a href="account_registration.php"><button type="button" name="add_user">Add User</button></a>
            <input type="submit" name="report" value="Generate Report on Selected">
          </div>
        </form>
      </div>
    </div>

    <?php $conn->close(); ?>
  </body>
</html>
