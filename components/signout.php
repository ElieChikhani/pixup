<?php

if(!isset($_SESSION)) session_start(); 
unset($_SESSION['user_id']);
unset($_SESSION['username']);

session_unset();
session_destroy();


header('Location: ../index.php');
exit(); 
?>