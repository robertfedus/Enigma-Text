<?php

session_start();

unset($_SESSION['username']);
$_SESSION['message'] = "You are now logged out.";
session_destroy();
header("location: login.php");

?>