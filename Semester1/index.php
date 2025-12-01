<?php
session_start();

// --- CONFIGURATION: PASTE YOUR GOOGLE DRIVE LINK HERE ---
$drive_link = "https://drive.google.com/drive/folders/19rsgOE0XpOYyPr20_w9KqAlZqDgMt14X"; 
// --------------------------------------------------------

// Check if user is logged in for Semester 1 specifically
if (!isset($_SESSION['sem1_user_id'])) {
    // Not logged in? Go to login page
    header("Location: server_r/login.php");
    exit;
} else {
    // Logged in? Redirect to Google Drive
    header("Location: " . $drive_link);
    exit;
}
?>