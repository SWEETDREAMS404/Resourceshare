<?php
session_start();

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();

// Regenerate session ID for security
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
session_regenerate_id(true);

// Redirect to landing page
header('Location: landing.php');
exit;
?>
