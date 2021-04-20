<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <script type="text/javascript" src="js/jquery-3.6.0.min.js"></script>

  </script>
  <?php include 'databaseconnect.php';

      # if session variable already set, redirect to homepage
      if (isset($_SESSION['user'])) {
        header('Location: homepage.php');
      }

    ?>

  <title>CNU Committees — Registration</title>
</head>

<body>
  <div class="wrapper">
    <header>
      <h2>Account Registration</h2>
    </header>
    <form action="index.php" method="post">
      <div class="body">
        <div class="column">
          <span class="sub heading">Personal Info</span>
          <div class="list">
            <label for='fname' class='required'>First Name</label>
            <input id='fname' type="text" name="fname" placeholder="First Name" required>

            <label for='lname' class='required'>Last Name</label>
            <input id='lname' type="text" name="lname" placeholder="Last Name" required>

            <label for='cnu_id' class='required'>CNU ID</label>
            <input id='cnu_id' type="number" name="cnu_id" placeholder="CNU ID" max=999999 required>

            <label for='email' class='required'>Email</label>
            <input id='email' type="email" name="email" placeholder="Email" required>

            <label for='bday' class='required'>Date of Birth</label>
            <input id='bday' type="date" name="bday" required>
          </div>
          <span class="sub heading">Employment</span>
          <!-- TODO: turn into dropdown menus where appropriate-->
          <div class="list">
            <label for='department'>Department</label>
            <select id="department" name="department">
              <option value="" selected disabled hidden>Please select...</option>
              <option value="Communication">Communication</option>
              <option value="Economics">Economics</option>
              <option value="Preparation/Teacher Education">Preparation/Teacher Education</option>
              <option value="English">English</option>
              <option value="Fine Art and Art History">Fine Art and Art History</option>
              <option value="History">History</option>
              <option value="Leadership and American Studies">Leadership and American Studies</option>
              <option value="Luter School of Business">Luter School of Business</option>
              <option value="Modern and Classical Languages and Literatures">Modern and Classical Languages and Literatures</option>
              <option value="Molecular Biology and Chemistry">Molecular Biology and Chemistry</option>
              <option value="Music">Music</option>
              <option value="Neuroscience">Neuroscience</option>
              <option value="Organismal and Environmental Biology">Organismal and Environmental Biology</option>
              <option value="Philosophy and Religion">Philosophy and Religion</option>
              <option value="Political Science">Political Science</option>
              <option value="Physics, Computer Science and Engineering">Physics, Computer Science and Engineering</option>
              <option value="Psychology">Psychology</option>
              <option value="Sociology, Social Work and Anthropology">Sociology, Social Work and Anthropology</option>
              <option value="Theater and Dance">Theater and Dance</option>
            </select>

            <label for='position'>Position</label>
            <input id='position' type="text" name="position" placeholder="Position">

            <label for='hiring_year' class='required'>Hiring Year</label>
            <input id='hiring_year' type="number" name="hiring_year" max=<?php echo intval(date('Y'));?> required>
          </div>
          <span class="sub heading">Other</span>
          <!-- TODO: turn these into dropdown menus -->
          <div class="list">
            <label for='race' class='required'>Race/Ethnicity</label>
            <select id="race" name="race" required>
              <option value="" selected disabled hidden>Please select...</option>
              <option value="American Indian or Alaska Native">American Indian or Alaska Native</option>
              <option value="Asian">Asian</option>
              <option value="Black or African American">Black or African American</option>
              <option value="Hispanic or Latino">Hispanic or Latino</option>
              <option value="Native Hawaiian or Other Pacific Islander">Native Hawaiian or Other Pacific Islander</option>
              <option value="White">White</option>
            </select>

            <label for='gender' class='required'>Gender</label>
            <select id="gender" name="gender" required>
              <option value="" selected disabled hidden>Please select...</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Transgender Male">Transgender Male</option>
              <option value="Transgender Female">Transgender Female</option>
              <option value="Non-binary">Non-binary</option>
              <option value="None">None</option>
              <option value="Prefer not to say">Prefer not to say</option>
            </select>
          </div>
          <span class="sub heading">Password</span>
          <div class="list">
            <label for="pass" class="required">Password</label>
            <input id='pass' type="password" name="pass" placeholder="Password" maxlength="24" required>

            <label for='cpass' class="required">Confirm</label>
            <input id='cpass' type="password" name="cpass" placeholder="Confirm Password" maxlength="24" required>

            <span id="pass_validation">
              <!-- For password validation javascript -->
            </span>
          </div>
        </div>
        <div class="column tiles">
          <div class="tile center">
            <!-- TODO: add photo uploading capability -->
            <label for="img">Select user account image:</label>
            <div class="image"></div>
            <input type="file" id="img" name="img" value=NULL accept="image/*">
          </div>
          <div class="tile">
            <input id='create' name='create' type="submit" value="Create Account" disabled>
          </div>
        </div>
      </div>
  </div>
  </form>
  </div>
  <?php $conn->close(); ?>
  <script type="text/javascript">
    function confirmPassword() {
      var pass = $('#pass').val();
      var cpass = $('#cpass').val();
      var pointer = $('#pass_validation');

      var blank_style = "border: 0; background-color: #DEDEDE;";

      var valid_style = "border: 1px solid #007D22; background-color: #2CBD0f;";

      var invalid_style = "border: 1px solid #780A00; background-color: #DE5021;";

      $('#create').prop('disabled', true);

      if (pass.length == 0 || cpass.length == 0) {

        pointer.html('');
        pointer.prop('style', blank_style);

      } else if (pass == cpass) {

        if (pass.length >= 8) {

          pointer.html('Password valid');
          pointer.prop('style', valid_style);
          $('#create').prop('disabled', false);

        } else {

          pointer.html('Password shorter than 8 digits');
          pointer.prop('style', invalid_style);

        }

      } else {

        pointer.html('Passwords do not match');
        pointer.prop('style', invalid_style);

      }
    }

    $(document).ready(function() {
      $('#cpass').keyup(confirmPassword);
      $('#pass').keyup(confirmPassword);
    });
  </script>
</body>

</html>
