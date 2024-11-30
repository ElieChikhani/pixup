<?php
session_start();

$timeout = 600; // 600 seconds = 10 minutes
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
    session_unset(); // Unset session variables
    session_destroy(); // Destroy the session
}
$_SESSION['last_activity'] = time(); // Update last activity timestamp
?>