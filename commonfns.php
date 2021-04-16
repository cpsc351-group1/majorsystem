<?php
function check_null($var) {
  if (is_null($var)) {
    header('Location: 404.php');
  }
}
?>
