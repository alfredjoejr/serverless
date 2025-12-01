<?php
    session_start();
    // Unset ONLY the Semester 1 session
    unset($_SESSION['sem1_user_id']);
    header("Location: ../index.php");
    exit;
?>