<?php 
$db_hostname = 'localhost';
$db_database='CODERS';
$db_usrname = 'root';
$db_passwd = 'iam@nkur';
$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
?>
