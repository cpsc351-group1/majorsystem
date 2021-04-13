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

// Boolean to track if already in database
// $found = FALSE:

// Do something!
echo "This code currently has no function, but it works!<br>";

$conn->close();

?>
