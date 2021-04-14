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
      <h2>Account Registration</h2>
      <form class="profile" action="account_registration" method="post">
        <div class="body">
          <div class="column">
            <div class="block">
              <span class="sub heading">Personal Info</span>
              <label for='fname'>First Name</label>
              <input id='fname' type="text" name="fname" placeholder="First Name" rquired>

              <label for='lname'>Last Name</label>
              <input id='lname' type="text" name="lname" placeholder="Last Name" required>

              <label for='email'>Email</label>
              <input id='email' type="email" name="email" placeholder="Email" required>

              <label for='bday'>Birthday</label>
              <input id='bday' type="date" name="bday" required>

            </div>
            <div class="block">
              <span class="sub heading">Password</span>
              <!-- TODO: turn this into an actual password field lol -->
              <label for="pass">Password</label>
              <input id='pass' type="text" name="pass" placeholder="Password" required>

              <label for='cpass'>Confirm</label>
              <input id='cpass' type="text" name="cpass" placeholder="Confirm Password" required>
            </div>
          </div>
          <div class="column">
            <div class="block">
              <span class="sub heading">Employment</span>
              <!-- TODO: turn into dropdown menus where appropriate-->
              <label for='college'>College</label>
              <input id='college' type="text" name="college" placeholder="College" required>

              <label for='position'>Position</label>
              <input id='position' type="text" name="position" placeholder="Position" required>

              <label for='term_hiring'>Hiring Term</label>
              <input id='term_hiring' type="date" name="term_hiring" required>
            </div>
            <div class="block">
              <span class="sub heading">Other</span>
              <!-- TODO: turn these into dropdown menus -->
              <label for='race'>Race</label>
              <input id='race' type="text" name="race" placeholder="Race" required>

              <label for='gender'>Gender</label>
              <input id='gender' type="text" name="gender" placeholder="Gender" required>
            </div>
          </div>
          <div class="column">
            <div class="block submit">
              <!-- TODO: add photo uploading capability -->
              <input id='submit' type="submit" value="Create Account">
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php $conn->close(); ?>
  </body>
</html>
