<?php

/*    CREDENTIALS   */

$SRVR = "localhost:3308";
$USER = "root";
$PASS = "root";
$TABL = "mydb";

// Creating connection
$conn = new mysqli($SRVR, $USER, $PASS, $TABL);

// Checking connection
if ($conn->connect_error) {
    die("Oh no! Connection failed: " . $conn->connect_error);
}

if (basename($_SERVER['SCRIPT_FILENAME']) != "index.php" and is_null($_SESSION['user'])) {
  header("Location: index.php");
}

// Boolean to track if already in database
// $found = FALSE:

?>
