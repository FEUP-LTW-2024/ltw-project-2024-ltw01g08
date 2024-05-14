<?php
session_start();

// Destroy all session data.
session_destroy();

// Redirect to home page or login page.
header('Location: ../index.php');
exit;
?>