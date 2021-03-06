<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  
  <?php include 'databaseconnect.php';
  
  // ADMIN PERMISSIONS CHECK
  validate_inputs($current_user_permissions, "Admin", "election_selection.php");

  ?>

  <title>CNU Committees - Elections</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <?php print_back_button("Homepage", "homepage.php"); ?>
      <h2>Elections</h2>
    </header>
    <div class="selection">
      <div class="results">
        <?php

            # Pull user details to generate checklist
            $sql = "SELECT * FROM Election
                    ORDER BY `Status` DESC, `Election_ID` DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                # Iterate through all users
                while ($row = $result->fetch_assoc()) {
                    # pull committee details for each election
                    $com_id = $row['Committee_Committee_ID'];
                    $com_sql = "SELECT * FROM `Committee` WHERE Committee_ID='$com_id'";
                    $com = $conn->query($com_sql)->fetch_assoc();
                    // store name
                    $name = $com['Name'];

                    # store other details about election
                    $id = $row['Election_ID'];
                    $seats = $row['Number_Seats'];
                    $status = $row['Status'];

                    // Create div populated with relevant information
                    // and details options.

                    // Checkbox belongs to options form, placed here for visuals
                    echo "<div class='data $status'>";
                    echo "<div><b>$name Election</b><br>Electing $seats seat".($seats==1?'':'s')."<br><b>Status: </b>$status</div>";
                    echo "<div class='result_choices'><a href='election_details.php?election=$id'><button>Details >></button></a></div>";
                    echo "</div>";
                }
            } else {
                // Display blank result if no search results
                echo "<div class='center'>No results found for current search settings.</div>";
            }

            ?>
      </div>
      <div class="options">
        <h4>Options</h4>
        
        <!-- Selection Options -->

        <hr>
        <!-- Administrative Options -->
        <div class="choices">
          <a href="election_setup.php"><button class="admin" type="button" name="add_user">Start Election</button></a>
          <!--TODO: implement this-->
        </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
