<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <?php include 'databaseconnect.php'?>

    <title>CNU User Details — </title>
  </head>
  <body>
    <title>CNU Committees — User Details</title>
  </head>
  <body>
    <div class="wrapper">
      <h2>User Details</h2>
      <form id="profile" action="user_details" method="post">
        <div id="info">
          <div class="column">
            <div class="block">
              <h3>Personal Info</h3>
              <?php echo "First Name: ".$_GET['fname']; ?>

              <?php echo "Last Name: ".$_GET['lname']; ?>

              <?php echo "Email: ".$_GET['email']; ?>

              <?php echo "Birthday: ".$_GET['birthday']; ?>

            </div>
          <div class="column">
            <div class="block">
              <h3>Employment</h3>
              <?php echo "College: ".$_GET['college']; ?>

              <?php echo "Position: ".$_GET['position']; ?>

              <?php echo "Date of Hiring: ".$_GET['date_of_hiring']; ?>
            </div>
            <div class="block">
              <h3>Other</h3>
              <?php echo "Race: ".$_GET['race']; ?>

              <?php echo "Gender: ".$_GET['gender']; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php $conn->close(); ?>
  </body>
</html>
