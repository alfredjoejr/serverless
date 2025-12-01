<?php
$servername = "localhost";
$username = "glassos_admin"; // New username
$password = "securepass123"; // New password
$dbname = "glassos_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>