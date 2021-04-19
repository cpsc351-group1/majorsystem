<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php include 'databaseconnect.php'

      /* TODO: Add sql query to update database when info is posted */

    ?>

  <title>CNU Committees — Registration</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Account Registration</h2>
    </header>
    <form action="account_registration.php" method="post">
      <div class="body">
        <div class="column">
              <span class="sub heading">Personal Info</span>
              <div class="list">
                <label for='fname'>First Name</label>
                <input id='fname' type="text" name="fname" placeholder="First Name" rquired>

                <label for='lname'>Last Name</label>
                <input id='lname' type="text" name="lname" placeholder="Last Name" required>

                <label for='bday'>Birthday</label>
                <input id='bday' type="date" name="bday" required>
              </div>
              <span class="sub heading">Password</span>
              <div class="list">
                <label for="pass">Password</label>
                <input id='pass' type="password" name="pass" placeholder="Password" required>

                <label for='cpass'>Confirm</label>
                <input id='cpass' type="password" name="cpass" placeholder="Confirm Password" required>
              </div>
              <span class="sub heading">Employment</span>
              <!-- TODO: turn into dropdown menus where appropriate-->
              <div class="list">
                <label for='college'>College</label>
                <input id='college' type="text" name="college" placeholder="College" required>

                <label for='position'>Position</label>
                <input id='position' type="text" name="position" placeholder="Position" required>

                <label for='term_hiring'>Hiring Term</label>
                <input id='term_hiring' type="date" name="term_hiring" required>
              </div>
              <span class="sub heading">Other</span>
              <!-- TODO: turn these into dropdown menus -->
              <div class="list">
                <label for='race'>Race</label>
                <input id='race' type="text" name="race" placeholder="Race" required>

                <label for='gender'>Gender</label>
                <input id='gender' type="text" name="gender" placeholder="Gender" required>
              </div>
            </div>
            <div class="column tiles">
              <div class="tile submit center">
                <!-- TODO: add photo uploading capability -->
                <form action="/action_page.php">
                  <label for="img">Select user account image:</label>
                  <div class="image"></div>
                  <input type="file" id="img" name="img" accept="image/*">
                </form>
              </div>
              <div class="tile">
                <input id='submit' type="submit" value="Create Account">
              </div>
          </div>
        </div>

      </div>
    </form>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
