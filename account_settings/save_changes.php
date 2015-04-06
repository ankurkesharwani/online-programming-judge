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
$new_contact_no=isset($_POST['New_Contact_No'])?$_POST['New_Contact_No']:"";
$new_email_id=isset($_POST['New_Email_Id'])?$_POST['New_Email_Id']:"";
$new_hostel=isset($_POST['New_Hostel'])?$_POST['New_Hostel']:"";
$new_room_no=isset($_POST['New_Room_No'])?$_POST['New_Room_No']:"";
$new_postal_address=isset($_POST['New_Postal_Address'])?$_POST['New_Postal_Address']:"";
require_once '../dbinfo/dbconnect.php';
$query = "UPDATE Users SET Contact_Number='$new_contact_no',Email_Id='$new_email_id',Hostel='$new_hostel',Room_No='$new_room_no',Postal_Address='$new_postal_address' WHERE Lib_ID='$lib_id'";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
header("Location: ../account_settings/?status=changes_saved");
?>
