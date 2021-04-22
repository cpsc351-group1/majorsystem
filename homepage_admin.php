<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  # pull user id from session
  $user_id = intval($_SESSION['user']);
  $permissions = $_SESSION['permissions'];

  # connect to database
  include 'databaseconnect.php';

  # run query
  $sql = "SELECT * FROM `User` WHERE CNU_ID=$user_id";
  $result = $conn->query($sql);

  $user = $result->fetch_assoc();

  $full_name = $user['Fname']." ".$user['Lname'];

?>
  <title>CNU Committee Database</title>
  <link href="css/main.css" rel="stylesheet">
</head>

<body>
  <div class="wrapper">
    <div class="body">
      <div class="session_details">
        Logged in as <b><?php echo $full_name; ?></b>
        <hr>
        <form action="index.php" method="post">
          <input type="hidden" name="logout" value="set">
          <input type="submit" name="logout" value="Sign out">
        </form>
      </div>
      <div class="block">
        Welcome to the<br>
        <b>UFOC Committee Database</b>
        <br><br>
        What are you looking for, <?php echo $full_name; ?>?
      </div>
      <div class="tiles">
        <a href="user_details.php?user=<?php echo $user_id;?>" style="color: white"><button>View Profile</button></a>
        <a href="committee_selection.php" style="color: white"><button>Committees</button></a>
        <a href="election_selection.php" style="color: white"><button>Elections</button></a>
        <a href="user_selection.php" style="color: white"><button>Users</button></a>
        <a href="notifications.php" style="color: white"><button>Notifications</button></a>
      </div>
    </div>
  </div>
</body>
<?php $conn->close(); ?>

</html>
