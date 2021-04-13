<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/profile.css">
    <?php include 'databaseconnect.php'?>

    <title>CNU Committees — Registration</title>
  </head>
  <body>
    <div class="wrapper">
      <h3>Account Registration</h3>
      <form id="profile" action="account_registration" method="post">
        <h4>Account Information</h4>
        <div id="info">
          <div class="column">
            <div class="block">
              <h5>Personal Info</h5>
              <label for='fname'>First Name</label>
              <input id='fname' type="text" name="fname" value="First Name"></input>

              <label for='lname'>Last Name</label>
              <input id='lname' type="text" name="lname" value="Last Name"></input>

              <label for='email'>Email</label>
              <input id='email' type="email" name="email" value="Email"></input>

              <label for='bday'>Birthday</label>
              <input id='bday' type="date" name="bday" value="Date of Birth"></input>

            </div>
            <div class="block">
              <h5>Password</h5>
              <!-- TODO: turn this into an actual password field lol -->
              <label for="pass">Password</label>
              <input id='pass' type="text" name="pass" value="Password"></input>

              <label for='cpass'>Confirm</label>
              <input id='cpass' type="text" name="cpass" value="Confirm Password"></input>
            </div>
          </div>
          <div class="column">
            <div class="block">
              <h5>Employment</h5>
              <!-- TODO: turn into dropdown menus where appropriate-->
              <label for='college'>College</label>
              <input id='college' type="text" name="college" value="College"></input>

              <label for='position'>Position</label>
              <input id='position' type="text" name="position" value="Position"></input>

              <label for='term_hiring'>Hiring Term</label>
              <input id='term_hiring' type="date" name="term_hiring" value="Term of Hiring"></input>
            </div>
            <div class="block">
              <h5>Other</h5>
              <!-- TODO: turn these into dropdown menus -->
              <label for='race'>Race</label>
              <input id='race' type="text" name="race" value="Race"></input>

              <label for='gender'>Gender</label>
              <input id='gender' type="text" name="gender" value="Gender"></input>
            </div>
          </div>
          <div class="column">
            <div class="block submit">
              <!-- TODO: add photo uploading capability -->
              <input id='submit' type="submit" value="Create Account"></input>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php $conn->close(); ?>
  </body>
</html>
