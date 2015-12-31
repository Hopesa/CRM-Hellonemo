<?php
// start the session before any output.
session_start();
/***************
  This File is to Fetch All Necessary Classes and Functions
 ****************/
require($_SERVER['DOCUMENT_ROOT'].'/crm-native/Mailer-Master/PHPMailerAutoload.php');
$mail = new PHPMailer;
/***********
  Change to PDO after Project Finished
 ************/
mysql_connect('localhost', 'root', '') or trigger_error("Unable to connect to the database: " . mysql_error());
mysql_select_db('crm') or trigger_error("Unable to switch to the database: " . mysql_error());
/***************
Encryption Hashes
 ****************/
define('SALT1', '24859f@#$#@$');
define('SALT2', '^&@#_-=+Afda$#%');
// require the function file
require_once('functions.php');
// default the error variable to empty.
$_SESSION['error'] = "";
// declare $Output so we do not have to do this on each page.
$sOutput="";
$output="";

?>