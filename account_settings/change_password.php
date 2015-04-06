<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
//=============================================================================================================================================

//=============================================================================================================================================
//[ IF NOT A LOGGED IN ]
if(strcmp($username,"Guest")==0)
{
	header("Location: ../home/");
}
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
$new_password=isset($_POST['New_Password'])?$_POST['New_Password']:"";
require_once '../dbinfo/dbconnect.php';
$query = "UPDATE Users SET Hash_Password='" . md5($new_password) . "' WHERE Lib_ID=$lib_id";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());	
header("Location: ../account_settings/?status=pwd_changed_successfully");
?>
