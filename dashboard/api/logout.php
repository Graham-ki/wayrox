<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page or home page
    header("Location: ../login.php");
    exit();
} else {
    // If the user is not logged in, redirect to the login page
    header("Location: ../login.php");
    exit();
}