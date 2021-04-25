<?php session_start(); ini_set('display_errors', true)?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">

  <?php 
    require "databaseconnect.php";

    // UPDATE USER INFORMATION
    if (isset($_POST['update'])) {

      # array of user info
      $posted_data = array(
        "Fname" => $_POST['fname'],
        "Lname" => $_POST['lname'],
        "Email" => $_POST['email'],
        "Department" => $_POST['department'],
        "Position" => $_POST['position'],
        "Birthday" => $_POST['bday'],
        "Hiring_Year" => $_POST['hiring_year'],
        "Gender" => $_POST['gender'],
        "Race" => $_POST['race'],
        "Photo" => $_POST['img']
      );

      $update_id = $_POST['cnu_id'];

      # input explicit data types
      $types='ssssssissb';

      # check if password updated
      if ($_POST['pass'] != '') {
        $posted_data["Password"] = $_POST['pass'];
        $types .= 's';
      }
      
      # prepare insert sql string
      $insert_sql =     "UPDATE `User`
                        SET ".implode( '=? , ', array_keys($posted_data)).
                        "=? WHERE CNU_ID = $update_id";

      # prepare statement
      $stmt = $conn->prepare($insert_sql);
      # bind statement inputs from array
      $posted_values = array_values($posted_data);
      $stmt->bind_param($types, ...$posted_values);
      # execute statement
      $stmt->execute() or die($conn->error);
      $stmt->close();
      header("Location: user_details.php?user=$update_id");
      exit();

    }

    // GET
    $entered_id = $_GET['user'];

    //  PULL USER INFO
    # select all info from relevant user
    $user_sql = "SELECT * FROM `User` WHERE CNU_ID = ?";
    $stmt = $conn->prepare($user_sql);
    # bind inputs
    $stmt->bind_param('i', $entered_id);
    $stmt->execute();
    # CNU_ID, Password, Fname, Lname, Email, Department, Position, Birthday, Hiring_Year, Gender, Race, Permissions, Photo
    $stmt->bind_result(
      $CNU_ID, $Password, $Fname, $Lname, $Email, $Department, $Position, $Birthday, $Hiring_Year, $Gender, $Race, $Permissions, $Photo
    );
    $stmt->fetch();
    $stmt->close();

    validate_inputs(is_null($CNU_ID), false, 'user_selection.php');

    function print_value($desired_value) {
      echo "value='$desired_value'";
    }

    function print_options(array $options, string $default_option = "Please select...") {
      array_unshift($options, "Please select...");
      foreach ($options as $option) {
        echo "<option value='$option'";
        echo ($default_option == $option ? " selected" : "");
        echo ($option == "Please select..." ? " disabled hidden" : "");
        echo ">$option</option>";
      }
     }
    
  ?>

  <title>CNU Committees — Update Account</title>
</head>

<body>

<!-- INCLUDE HAMBURGER MENU -->
<?php include 'hamburger_menu.php'; ?>

  <div class="wrapper">
    <header>
      <h2>Modify Account</h2>
    </header>
    <form action="user_modify.php" method="post">
      <div class="body">
        <div class="column">
          <span class="sub heading">Personal Info</span>
          <div class="list">
            <label for='fname' class='required'>First Name</label>
            <input id='fname' type="text" name="fname" placeholder="First Name" <?php print_value($Fname); ?>required>

            <label for='lname' class='required'>Last Name</label>
            <input id='lname' type="text" name="lname" placeholder="Last Name" <?php print_value($Lname); ?> required>

            <label for='cnu_id' class='required'>CNU ID</label>
            <input id='cnu_id' type="number" name="cnu_id" placeholder="CNU ID" max=999999 <?php print_value($CNU_ID); ?> readonly="readonly">

            <label for='email' class='required'>Email</label>
            <input id='email' type="email" name="email" placeholder="Email" <?php print_value($Email); ?> required>

            <label for='bday' class='required'>Date of Birth</label>
            <input id='bday' type="date" name="bday" <?php print_value($Birthday); ?> required>
          </div>
          <span class="sub heading">Employment</span>
          <!-- TODO: turn into dropdown menus where appropriate-->
          <div class="list">
            <label for='department'>Department</label>
            <select id="department" name="department">
              <?php
              $dept_options = array(
                "Communication",
                "Economics",
                "Preparation/Teacher Education",
                "English",
                "Fine Art and Art History",
                "History",
                "Leadership and American Studies",
                "Luter School of Business",
                "Modern and Classical Languages and Literatures",
                "Molecular Biology and Chemistry",
                "Music",
                "Neuroscience",
                "Organismal and Environmental Biology",
                "Philosophy and Religion",
                "Political Science",
                "Physics, Computer Science and Engineering",
                "Pschology",
                "Sociology, Social Work and Anthropology",
                "Theater and Dance"
              );

              print_options($dept_options, $Department);

              ?>
            </select>

            <label for='position'>Position</label>
            <input id='position' type="text" name="position" placeholder="Position" <?php print_value($Position); ?> >

            <label for='hiring_year' class='required'>Hiring Year</label>
            <input id='hiring_year' type="number" name="hiring_year" max="<?php echo date('Y').'"'; print_value($Hiring_Year); ?> required>
          </div>
          <span class="sub heading">Other</span>
          <!-- TODO: turn these into dropdown menus -->
          <div class="list">
            <label for='race' class='required'>Race/Ethnicity</label>
            <select id="race" name="race" required>
              <?php
                $race_options = array(
                  "American Indian or Alaska Native",
                  "Asian",
                  "Black or African American",
                  "Hispanic or Latino",
                  "Native Hawaiian or Other Pacific Islander",
                  "White"
                );

                print_options($race_options, $Race);
              ?>
            </select>

            <label for='gender' class='required'>Gender</label>
            <select id="gender" name="gender" required>
              <?php
                $gender_options = array(
                  "Male",
                  "Female",
                  "Transgender Male",
                  "Transgender Female",
                  "Non-binary",
                  "None",
                  "Prefer not to say"
                );

                print_options($gender_options, $Gender);
              ?>
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
            <input id='pass' type="password" name="pass" placeholder="Password" maxlength="24">

            <label for='cpass' class="required">Confirm</label>
            <input id='cpass' type="password" name="cpass" placeholder="Confirm Password" maxlength="24">

            <span id="pass_validation">
              <!-- For password validation javascript -->
            </span>
          </div>
          <div class="tile">
            <input id='update' name='update' type="submit" value="Update Account">
          </div>
        </div>
      </div>
  </div>
  </form>
  </div>

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

    $('#update').prop('disabled', true);

    if (pass.length == 0 || cpass.length == 0) {
      validator.html("Password must be 8 digits or longer");
      if (pass == cpass) {
          $('#update').prop('disabled', false);
      }
    } else if (pass == cpass) {
        if (pass.length >= 8) {
          $('#update').prop('disabled', false);
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

  <?php $conn->close(); ?>
  
</body>

</html>
