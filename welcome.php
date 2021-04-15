<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    
        body{ font: 14px sans-serif; text-align: center; 
        width: 70%;
        margin: 1em auto;
        text-align: center;
        color: #333333;}
    </style>
</head>
<body>
    <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the UFOC Committee Database</h1>
    <?php
  
	echo "<html><body>
	<form action=\"HomeScreen.php\" method=\"post\">
	Click the submit button to view the Master List! 
	<br><br><input type=\"submit\" name=\"submit\"><br><br>";
	
	// only display bottom half if submit button clicked
      if (isset($_POST['submit'])) {

  	
	// Creating connection
		$mysqli = new mysqli("localhost:8889", "root", "root", "ODIS");

	$result = $mysqli->query("SELECT * FROM Master_List");

		}
    	
?>

    <p>
        <a href="logout.php" class="btn btn-danger ml-3">Sign Out of Your Account</a>
    </p>
</body>
</html>