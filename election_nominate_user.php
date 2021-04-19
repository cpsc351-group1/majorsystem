<?php session_start(); ini_set('display_errors', 1)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/selection.css">
    <link rel="stylesheet" href="css/common.css">
    <?php include 'databaseconnect.php';

    # pull posted election variable
    $entered_id = $_GET['election'];

    # pull election information using $_GET
    $elec_sql = "SELECT Election_ID, Committee_Committee_ID FROM `Election` WHERE Election_ID=?";
    # prepare statement (to prevent mysql injection)
    $elec_stmt = $conn->prepare($elec_sql);
    # bind inputs
    $elec_stmt->bind_param('i', $entered_id);
    # bind results, fetch and close
    $elec_stmt->execute();
    $elec_stmt->bind_result($election_id, $committee_id);
    $elec_stmt->fetch();
    $elec_stmt->close();

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
          # show election subheader if an election is selected
          if (!is_null($election_id)) {
              echo "<div class='sub heading'>&nbsp;—&nbsp;Election for the $com_name</div>";
          }
        ?>

      </header>
      <div class="selection">
        <div class="results">
            <?php
            # error message for no election selected
            if (is_null($election_id)) {
                echo "<div class='center'>No election selected.</div></div>";
                goto options;
            }

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
              <input type="hidden" name="election_id" value="<?php echo $election_id?>">
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
        <?php
        options:
        ?>
        <form class="options" action="    'x'    " method="post"> <!--TODO: add report generation href-->
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
        </form>
      </div>
    </div>

    <?php $conn->close(); ?>
  </body>
</html>
