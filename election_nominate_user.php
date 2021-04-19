<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';

    # get entered election id
    $entered_id = $_GET['election'];

    # pull election information using $_GET
    $election_sql = "SELECT * FROM `Election` WHERE Election_ID=?";
    # prepare statement (this is done to prevent sql injection)
    $election = $conn->prepare($election_sql);
    # bind parameter to int
    $election->bind_param('i', $entered_id);
    # execute statement
    $election->execute();
    # bind results to variables
    $election->bind_result($election_id, $committee_id, $status, $num_seats);
    # fetch row and close
    $election->fetch();
    $election->close();

    #   Ensure that the election selected
    #   is actually in the nomination status (and not null)

    validate_inputs ($status, 'Nomination', 'election_selection.php');

    $com_sql = "SELECT Name FROM `Committee` WHERE Committee_ID='$committee_id'";
    $com = $conn->query($com_sql)->fetch_assoc();
    $com_name = $com['Name'];

    ?>
  <title>CNU Committees - Nominate User</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Nominate User</h2>
      <?php
          echo "<div class='sub heading'>&nbsp;—&nbsp;Election for the $com_name</div>";
        ?>

    </header>
    <div class="selection">
      <div class="results">
        <?php

            # pull nominee details
            $noms_sql = "SELECT * FROM `User` WHERE CNU_ID NOT IN(
                              SELECT Nominee_CNU_ID FROM `Nomination`
                              WHERE Election_Election_ID=$election_id)";
            $noms = $conn->query($noms_sql);

            if ($noms->num_rows > 0) {
                # Iterate through all users
                while ($row = $noms->fetch_assoc()) {
                    $id = $row['CNU_ID'];
                    $name = $row['Fname'].' '.$row['Lname'];
                    $dept = $row['Department'];
                    $pos = $row['Position']; ?>
        <form id="nomination" action="election_details.php?election=<?php echo $election_id; ?>" method="post">
          <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
          <?php
                    # Checkbox belongs to options form, placed here for visuals
                    echo "<div class='data'> <label name='$id'><b>$name</b><br>$dept<br>$pos</label>
                        <div class='result_choices'>
                          <input type='radio' name='nominee' id='$id' value='$id' required>
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

      <div class="options">

          <!--TODO: add functionality to search options-->
          <h4>Options</h4>
          <!-- Search Bar -->
          <div class='searchbar'>
            <input type="text" name="search" placeholder="Search...">
          </div>

          <hr>
          <!-- Administrative Options -->
          <div class="emphasis">
            <input type="submit" name="nominate" value="Nominate User" form="nomination">
          </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
