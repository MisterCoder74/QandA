<?php
// start the session
session_start();

// destroy the session data
session_destroy();

// redirect the user to the login page
header("Location: login.php");
exit();
?>