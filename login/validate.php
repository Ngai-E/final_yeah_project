<?php
// define variables and set to empty values
$username = $pass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = test_input($_POST["username"]);
  $pass = test_input($_POST["pass"]);
  // $website = test_input($_POST["website"]);
  // $comment = test_input($_POST["comment"]);
  // $gender = test_input($_POST["gender"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

