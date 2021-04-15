<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="css/profile.css" type="text/css">
  <?php include 'databaseconnect.php';

      $user_id = intval($_GET['user']);
      $sql = "SELECT * FROM `User` WHERE CNU_ID='$user_id';";

      $user = $conn->query($sql)->fetch_assoc();
    ?>

    <title>CNU — <?php echo $user['Fname']." ".$user['Lname']; ?></title>
  </head>
  <body>

  <!-- TODO: Create PHP script to generate this page for all
               users in a report    -->

  <div class="wrapper">
    <h2>User Details</h2>
    <div class="profile">

      <!-- TODO: Add user photo and committee memberships -->

      <div class="body">
        <div class="column">
          <div class="block">
            <span class="sub heading">Personal Info</span>
            <span class="label">First Name:</span>
            <span><?php echo $user['Fname'];?></span>

            <span class="label">Last Name:</span>
            <span><?php echo $user['Lname'];?></span>

            <span class="label">Email:</span>
            <span><?php echo $user['Email'];?></span>

            <span class="label">Birthday:</span>
            <span><?php echo $user['Birthday'];?></span>
          </div>
        </div>
        <div class="column">
          <div class="block">
            <span class="sub heading">Employment</span>
            <span class="label">College:</span>
            <span><?php echo $user['Department'];?></span>

            <span class="label">Position:</span>
            <span><?php echo $user['Position'];?></span>

            <span class="label">Date of Hiring:</span>
            <span><?php echo $user['Date_of_Hiring'];?></span>
          </div>
        </div>
        <div class="column">
          <div class="block">
            <span class="sub heading">Other</span>
            <span class="label">Race:</span>
            <span><?php echo $user['Race'];?></span>

            <span class="label">Gender:</span>
            <span><?php echo $user['Gender'];?></span>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php $conn->close(); ?>
</body>

</html>
