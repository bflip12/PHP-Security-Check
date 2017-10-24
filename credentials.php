<?php
//StAuth10065: I Bobby Filippopoulos, 000338236 certify that this material is my original work. No other person's work has been used without due acknowledgement. I have not made my work available to anyone else.

//error messaging
ini_set('display_errors',0); //show no error messages to screen
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));  //default

$username = "enterusernamehere";
$password = "enterpasswordhere";
$dsn = "mysql:host=localhost;dbname=enterDBnamehere;";

$pdo = new PDO($dsn, $username, $password);

?>
