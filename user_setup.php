<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  
  <?php
  
    require 'databaseconnect.php';

      # USER ACCOUNT CREATION

      if (isset($_POST['create'])) {
        $posted_data = array(
          $_POST['cnu_id'],
          $_POST['pass'],
          $_POST['fname'],
          $_POST['lname'],
          $_POST['email'],
          $_POST['department'],
          $_POST['position'],
          $_POST['bday'],
          $_POST['hiring_year'],
          $_POST['gender'],
          $_POST['race'],
          $_POST['img']
        );

        $entered_user = $_POST['cnu_id'];

        $insert_sql = "INSERT INTO `User` (`CNU_ID`, `Password`, `Fname`, `Lname`, `Email`, `Department`, `Position`, `Birthday`, `Hiring_Year`, `Gender`, `Race`, `Photo`)
                      SELECT ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                      WHERE $entered_user NOT IN(SELECT CNU_ID FROM `User`)";
        # input explicit data types
        $types='isssssssissb';
        # prepare statement
        $stmt = $conn->prepare($insert_sql);
        
        # bind statement inputs from array
        $stmt->bind_param($types, ...$posted_data);
        
        # execute statement
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
        exit();
      }

    ?>

  <title>CNU Committees — Registration</title>
</head>

<body>

  <div class="wrapper">
    <header>
      <?php print_back_button("User Selection", "user_selection_super.php"); ?>
      <h2>Account Registration</h2>
    </header>
    <form action="user_registration.php" method="post">
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
        </div>
        <div class="column tiles">
          <div class="tile center">
            <!-- TODO: add photo uploading capability -->
            <label for="img" class="heading">Account Image</label>
            <div class="image"></div>
            <input type="file" id="img" name="img" value=NULL accept="image/png">
          </div>
          <div class="tile list">
            <span class="sub heading">Password</span>
            <label for="pass" class="required">Password</label>
            <input id='pass' type="password" name="pass" placeholder="Password" maxlength="24" required>

            <label for='cpass' class="required">Confirm</label>
            <input id='cpass' type="password" name="cpass" placeholder="Confirm Password" maxlength="24" required>

            <span id="pass_validation">
              <!-- For password validation javascript -->
            </span>
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
  
  $(function() {

$('#pass_validation').html('Password must be 8 digits or longer');

$(document).on("input", function() {
  validate_pass($('#pass_validation'));
});

function validate_pass(validator) {

  var pass = $('#pass').val();
  var cpass = $('#cpass').val();

  validator.removeClass('invalid');
  validator.removeClass('valid');

  $('#create').prop('disabled', true);

  if (pass.length == 0 || cpass.length == 0) {
    validator.html("Password must be 8 digits or longer");
  } else if (pass == cpass) {
      if (pass.length >= 8) {
        $('#create').prop('disabled', false);
        validator.addClass('valid');
        validator.html('Valid Password');
      } else {
        validator.addClass('invalid');
        validator.html('Password shorter than 8 digits');
      }
  } else {
    validator.addClass('invalid');
    validator.html('Passwords do not match');
  }

}
});

  </script>
</body>

</html>
