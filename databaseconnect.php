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

$no_session_pages = array(
  "index.php",
  "user_registration.php"
);

if (!in_array(basename($_SERVER['SCRIPT_FILENAME']), $no_session_pages) and is_null($_SESSION['user'])) {
    header("Location: index.php");
}

function validate_inputs($input, $expected, $location)
{
    if (!($input == $expected)) {
        header("Location: $location");
        exit();
    }
}

function admin_redirect($user_permissions, $location) {
    validate_inputs($user_permissions=='Admin', false, $location);
}

function super_redirect($user_permissions, $location) {
    validate_inputs($user_permissions=='Super', false, $location);
}

// Boolean to track if already in database
// $found = FALSE:
