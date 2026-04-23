<?php
session_start(); 


$_SESSION = array();

session_destroy();

// Redirect to the landing page
header("Location: LandingPage.html");
exit;
?>