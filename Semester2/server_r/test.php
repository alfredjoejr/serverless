<?php
require_once 'db_connect.php';

// 1. The password you want to use
$new_password = 'password123'; 

// 2. Generate a secure hash
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

// 3. Update the database
$username = 'student';
$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $new_hash, $username);

if ($stmt->execute()) {
    echo "<h1>Success!</h1>";
    echo "<p>Password for user <b>'$username'</b> has been reset.</p>";
    echo "<p>New Password: <b>$new_password</b></p>";
    echo "<p>New Hash: $new_hash</p>";
    echo "<br><a href='login.php'>Go to Login Page</a>";
} else {
    echo "Error updating record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>