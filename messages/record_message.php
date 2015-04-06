<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																									
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$validation_ok=true;

//=============================================================================================================================================

//=============================================================================================================================================
//[ VALIDATE FORM ]
$name=isset($_POST['name'])?$_POST['name']:"";
$email=isset($_POST['email'])?$_POST['email']:"";
$message=isset($_POST['message'])?$_POST['message']:"";

if(strcspn($name,"1234567890-=`~!@#$%^&*()_+[]{};':,.<>/")!=strlen($name))
	$validation_ok=false;
if(strcspn($email,"-=`~!#$%^&*()_+[]{};':,<>/")!=strlen($email))
	$validation_ok=false;
if(strcspn($message,"'~!^_<>/")!=strlen($message))
	$validation_ok=false;
if($validation_ok==false)
	header("Location: ../messages?status=wrong_info") and die;
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
$query = "INSERT INTO Messages(Name,Email_Id,Message) VALUES" . "('$name','$email','$message')";
$result=mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
header("Location: ../messages?status=message_sent");
//=============================================================================================================================================

?>
