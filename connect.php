<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cis";

// Create connection
$db = new mysqli($servername, $username, $password, $database);

// Check connection
if (!$db) {
  die("Connection failed: " . mysqli_connect_error());
}

?>
