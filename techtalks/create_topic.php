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
$topic=isset($_POST['topic'])?$_POST['topic']:"";
$description=isset($_POST['description'])?$_POST['description']:"";
if(strcspn($topic,"1234567890-=`~!@#$%^&*()_+[]{};':,.<>/")!=strlen($topic))
	$validation_ok=false;
if($validation_ok==false)
	header("Location: ../techtalks?status=wrong_info") and die;
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
$query = "INSERT INTO Topics(Topic,Lib_ID,Description) VALUES" . "('$topic','$lib_id','$description')";
$result=mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
header("Location: ../techtalks?status=topic_created");
//=============================================================================================================================================

?>
