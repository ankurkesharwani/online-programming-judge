<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																									
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$id=isset($_GET['id'])?$_GET['id']:"";
$c_id=isset($_GET['c_id'])?$_GET['c_id']:"";
//=============================================================================================================================================






//=============================================================================================================================================
//[ IF NOT A VALID USER ]
if(strcmp($username,"Guest")!=0)
{
	if(strcmp(md5(md5(md5($username . $lib_id))),$key)!=0)
	{
		setcookie('username', '', time() - 2592000, '/');
		setcookie('lib_id', '', time() - 2592000, '/');
		setcookie('isadmin', '', time() - 2592000, '/');
		setcookie('key', '', time() - 2592000, '/');
		echo "<center><h1>Please login to continue.</h1><br><h3>Click <a href='../home/'>here</a> to go to index page.<h3></center>";
		die;
	}
}
//=============================================================================================================================================


//=============================================================================================================================================
//[ RECORD MESSAGE ]
require_once '../dbinfo/dbconnect.php';
if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{	
	$query = "DELETE FROM Comments WHERE C_ID=$c_id";
	$result=mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	header("Location: ../techtalks/view_thread.php?id=$id");
}
else if(strcmp($username,"Guest")!=0)
{
	$query = "SELECT Lib_ID FROM Comments WHERE C_ID=$c_id";
	$result=mysql_query($query);
	if (!$result) die ("Database access failed: 2" . mysql_error());
	$row = mysql_fetch_row($result);
	if(strcmp($row[0],$lib_id)==0)
	{
		$query = "DELETE FROM Comments WHERE C_ID=$c_id";
		$result=mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());
		header("Location: ../techtalks/view_thread.php?id=$id");
	}
}


//=============================================================================================================================================

?>
