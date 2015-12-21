<?php
// start the session before any output.
session_start();
/***************
 ****************/
//try {
//    $conn = new PDO('mysql:host=localhost;dbname=crm;charset=utf8', 'root', '');
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//} catch(PDOException $e) {
//    echo 'ERROR: ' . $e->getMessage();
//}
mysql_connect('localhost', 'root', '') or trigger_error("Unable to connect to the database: " . mysql_error());
mysql_select_db('crm') or trigger_error("Unable to switch to the database: " . mysql_error());
/***************
password salts are used to ensure a secure password
hash and make your passwords much harder to be broken into
Change these to be whatever you want, just try and limit them to
10-20 characters each to avoid collisions.
 ****************/
define('SALT1', '24859f@#$#@$');
define('SALT2', '^&@#_-=+Afda$#%');
// require the function file
require_once('functions.php');
// default the error variable to empty.
$_SESSION['error'] = "";
// declare $sOutput so we do not have to do this on each page.
$output="";
?>