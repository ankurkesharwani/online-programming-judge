<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																									
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$id=isset($_GET['id'])?$_GET['id']:"";
$c_id=isset($_GET['c_id'])?$_GET['c_id']:"";
$comment=isset($_POST['comment'])?$_POST['comment']:"";
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
	echo "ksdjklfd";
	$query = "DELETE FROM Comments WHERE T_ID=$id";
	$result=mysql_query($query);
	if (!$result) die ("Database access failed:" . mysql_error());
	$query = "DELETE FROM Topics WHERE T_ID=$id";
	$result=mysql_query($query);
	if (!$result) die ("Database access failed:" . mysql_error());
	header("Location: ../techtalks");

}
//=============================================================================================================================================

?>
