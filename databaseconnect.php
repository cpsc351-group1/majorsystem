<?php

// Creating connection
$conn = new mysqli("localhost:3307", "root", "root", "mydb");

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