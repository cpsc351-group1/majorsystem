<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <!-- install JQuery -->
    <script type="text/javascript" src="jquery3.6.0.min.js"></script>
    <?php

    /*    CREDENTIALS   */

    $SRVR = "localhost:3308";
    $USER = "root";
    $PASS = "root";
    $TABL = "mydb";

    // Creating connection
    $conn = new mysqli($SRVR, $USER, $PASS, $TABL);

    // Checking connection
    if ($conn->connect_error) {
        die("Oh no! Connection failed: " . $conn->connect_error);
    }
    ?>
    <link rel="stylesheet" href="css/selection.css">

    <title>CNU Committees - System Users</title>
  </head>
  <body>
    <div class="wrapper">
      <h3>System Users</h3>
      <div id="users">
        <div class="results">
            <?php

            // Pull user details to generate checklist
            $sql = "SELECT CNU_ID, Name, Department, Position FROM User";
            $result = $conn->query($sql);
            // Store user details to array
            for ($a_result = array(); $row = $result->fetch_assoc(); $a_result[] = $row);

            if ($result->num_rows > 0) {
              // Iterate through all users
              foreach ($a_result as $row) {
                $id = $row['CNU_ID'];
                $name = $row['Name'];
                $dept = $row['Department'];
                $pos = $row['Position'];

                // Create div populated with relevant information
                // and checkbox/details options.

                // Checkbox belongs to options form, placed here for visuals
                echo "<div> <label name='$id'><b>$name</b><br>$dept<br>$pos</label>
                        <div class='resultChoices'>
                          <input type='checkbox' name='$id' form='options'></input><br>
                          <a href='/src/majorsystem/user_details.php?user=$id'><button>Details</button></a>
                        </div>
                      </div>";
              }
            } else {
              // Display blank result if no search results
              echo "<h5>No results found for current search settings.</h5>";
            }

            ?>
        </div>
        <form id="options" action="    'x'    " method="post"> <!--TODO: add report generation href-->
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
            <button class="emphasis" type="button" name="add_user">Add User</button>
            <button class="emphasis" type="button" name="generate_user_report">Generate Report on Selected</button>
          </div>
        </form>
      </div>
    </div>

    <?php $conn->close(); ?>
  </body>
</html>
