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

// INCLUDE JQUERY IN EVERY HEADER
echo "<script type='text/javascript' src='js/jquery-3.6.0.min.js'></script>";

$no_session_pages = array(
  "index.php",
);

$current_file_name = basename($_SERVER['SCRIPT_FILENAME']);

if (isset($_SESSION['user'])) {
    if (in_array($current_file_name, $no_session_pages)) {
        header("Location: homepage.php");
        exit();
    } else {
        $current_user_id = $_SESSION['user'];
        $current_user_permissions = $_SESSION['permissions'];
    }
} else {
    if (!in_array($current_file_name, $no_session_pages)) {
        header("Location: index.php");
        exit();
    } else {
        goto skip_hamburger;
    }
}

echo "<script type='text/javascript' src='js/hamburger_menu.js'></script>";

skip_hamburger:

// INPUT VALIDATION
function validate_inputs($input, $expected, $location)
{
    if (!($input == $expected)) {
        header("Location: $location");
        exit();
    }
}

//  PERMISSIONS REDIRECTS
function admin_redirect($user_permissions, $location) {
    validate_inputs($user_permissions=='Admin', false, $location);
}

function super_redirect($user_permissions, $location) {
    validate_inputs($user_permissions=='Super', false, $location);
}


//  SELECT USER DETAILS
# pulls a user's details based on a given ID -> user assoc
function query_user(int $user_id)
{
    global $conn;

    $user_sql = "SELECT * FROM `User` WHERE CNU_ID='$user_id'";
    $user = $conn->query($user_sql)->fetch_assoc();
    return $user;
}

// Boolean to track if already in database
// $found = FALSE:
