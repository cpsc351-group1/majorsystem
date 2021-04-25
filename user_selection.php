<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">

  <?php include 'databaseconnect.php';

    // ADMIN PERMISSIONS CHECK
    validate_inputs($current_user_permissions, "Admin", "homepage.php");

    # Pull user details to generate checklist
    $sql = "SELECT CNU_ID, Fname, Lname, Department, Position FROM User ORDER BY Fname";
    $result = $conn->query($sql);
    ?>

    <script type="text/javascript" src="js/force_checkboxes.js"></script>

  <title>CNU Committees - System Users</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>
  <div class="wrapper">
    <header>
      <h2>System Users</h2>
    </header>
    <div class="selection">
        <div class="results">
        <?php
            if ($result->num_rows > 0) {
                # Iterate through all users
                while ($row = $result->fetch_assoc()) {
                    $id = $row['CNU_ID'];
                    $name = $row['Fname'].' '.$row['Lname'];
                    $dept = $row['Department'];
                    $pos = $row['Position'];

                    // Create div populated with relevant information
                    // and checkbox/details options.

                    // Checkbox belongs to options form, placed here for visuals
                    echo "<div class='data' id='$id'> <label for='$id'><b>$name</b><br>$dept<br>$pos</label>
                        <div class='result_choices'>
                          <input class='checkbox' type='checkbox' name='$id' form='options'></input>
                          <form id='details' action='user_details.php' method='get'><button name='user' value='$id'>Details >></button></form>
                        </div>
                      </div>";
                }
            } else {
                # Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }
            ?>
      
        </div>
      <div class="options">
          <!--TODO: add report generation href-->

          <h4>Options</h4>
          <!-- Search Bar -->
          <!--TODO: add functionality to search options-->
          <div class='searchbar'>
            <input type="text" name="search" placeholder="Search...">
          </div>

          <hr>
          <!-- Selection Options -->
          <!-- TODO: implement these -->
          <div>
            <button type="button" name="select_all">Select All</button>
            <button type="button" name="deselect_all">Deselect All</button>
          </div>

          <hr>
          <!-- Administrative Options -->
          <form action="user_report.php" method="post" id='options'></form>
          <div class="choices">
            <a href="account_registration.php"><button class="admin" type="button" name="add_user" form='options'>Add User</button></a>
            <input id="report" type="submit" name="report" value="Generate Report on Selected" form='options'>
            <!--TODO: Implement this -->
          </div>
      </div>
    </div>
  </div>


  <?php $conn->close(); ?>
</body>

</html>
