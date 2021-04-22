<?php session_start(); ini_set('display_errors',true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/selection.css">
  <link rel="stylesheet" href="css/common.css">
  <?php

    require 'databaseconnect.php';
    include 'election_functions.php';

    # get entered election id
    $entered_id = $_GET['election'];

    //  SELECT ELECTION INFO
    # defined in election_functions.php
    query_election($entered_id);

    //  ELECTION ID VALIDATION
    # defined in databaseconnect.php
    validate_inputs(is_null($election_id), false, 'election_selection.php');

    //  STATUS VALIDATION
    #   Ensure that the election selected
    #   is actually in the voting status (and not null)
    # defined in databaseconnect.php
    validate_inputs($status, 'Voting', 'election_selection.php');

    //  SELECT COMMITTEE INFO
    # defined in election_functions.php
    query_committee($committee_id);
    $committee_name = $committee['Name'];

    //  SELECT VOTE INFO
    #defined in election_functions.php
    query_election_votes($election_id);

    //  SELECT NOMINEE INFO
    # pull all nominee details as users
    $nom_sql = "SELECT * FROM `User` WHERE CNU_ID IN(
                          SELECT Nominee_CNU_ID FROM `Nomination` WHERE
                          Election_Election_ID='$election_id')";
    $noms = $conn->query($nom_sql);

    ?>
  <title>CNU Committees - Vote for User</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Vote for User</h2>
      <?php
          echo "<div class='sub heading'>&nbsp;—&nbsp;Election for the $committee_name</div>";
        ?>

    </header>
    <div class="selection">
      <div class="results">
        <form id="vote" action="election_details.php?election=<?php echo $election_id; ?>" method="post">
          <input type="hidden" name="election_id" value="<?php echo $election_id; ?>">
          <?php

              if ($noms->num_rows > 0) {
                  # Iterate through all users
                  while ($row = $noms->fetch_assoc()) {
                      $id = $row['CNU_ID'];
                      $name = $row['Fname'].' '.$row['Lname'];
                      $dept = $row['Department'];
                      $pos = $row['Position'];

                      # Detect if user is same as previously voted                      
                      if (NULL != $previous) {
                        $checked = $id == $previous;
                      } else {
                        $checked = False;
                      }

                      # Render all nominees for current election
                      # Radio buttons belong to options form, placed here for visuals
                      echo "<div class='data'> <label for='$id'><b>$name</b><br>$dept<br>$pos</label>
                              <div class='result_choices'>
                                <input type='radio' name='vote' id='$id' value='$id' required".($checked?' checked':'').">
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
          <!-- Submission Options -->
          <div class="emphasis">
            <input type="submit" name="submit_vote" value="Vote for User" form="vote">
          </div>
      </div>
    </div>
  </div>

  <?php $conn->close(); ?>
</body>

</html>
