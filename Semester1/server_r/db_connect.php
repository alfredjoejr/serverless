<?php
$servername = "localhost";
$username = "alfredjoejr"; // New username
$password = "00000"; // New password
$dbname = "glassos_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>