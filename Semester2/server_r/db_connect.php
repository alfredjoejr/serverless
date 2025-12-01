<?php
$servername = " sql100.infinityfree.com";
$username = "if0_40468079"; // New username
$password = "AndJL35oTZA"; // New password
$dbname = " if0_40468079_glassos_db ";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>