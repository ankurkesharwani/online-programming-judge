<?php
//===============================================================================================================================================
//[VARIABLE DECLARATIONS]
$usrname=isset($_POST['User_Name'])?$_POST['User_Name']:"";
$password=isset($_POST['Password'])?$_POST['Password']:"";
$password=md5($password);
$lib_id=isset($_POST['Lib_Id'])?$_POST['Lib_Id']:"";
$univ_roll_no=isset($_POST['Univ_Roll_No'])?$_POST['Univ_Roll_No']:"";
$contact_no=isset($_POST['Contact_No'])?$_POST['Contact_No']:"";
$email_id=isset($_POST['Email_Id'])?$_POST['Email_Id']:"";
$hostel=isset($_POST['Hostel'])?$_POST['Hostel']:"";
$room_no=isset($_POST['Room_No'])?$_POST['Room_No']:"";
$postal_address=isset($_POST['Postal_Address'])?$_POST['Postal_Address']:"";
$validation_ok=true;
//===============================================================================================================================================

//===============================================================================================================================================
//[VALIDATE FORM DATA]]
if(strcmp($usrname,"")==0||strcmp($password,"")==0||strcmp($lib_id,"")==0||strcmp($univ_roll_no,"")==0||strcmp($contact_no,"")==0||strcmp($email_id,"")==0)
	$validation_ok=false;
if(!ctype_digit($lib_id))
	$validation_ok=false;
if(!(ctype_digit($univ_roll_no)&&strlen($univ_roll_no)==10))
	$validation_ok=false;
if(strspn($usrname,"1234567890-=`~!@#$%^&*()_+[]{};':,.<>/")!=0)
	$validation_ok=false;
if(strspn($password,"-=`~!@#$%^&*()_+[]{};':,.<>/")!=0)
	$validation_ok=false;
if(!ctype_digit($contact_no))
	$validation_ok=false;
if(strspn($email_id,"-=`~!#$%^&*()_+[]{};':,<>/")!=0)
	$validation_ok=false;
if(!(strcmp($hostel,"NA")==0||strcmp($hostel,"Aryabhatta")==0||strcmp($hostel,"Vivekanand")==0||strcmp($hostel,"Taigore")==0||strcmp($hostel,"Sarojni")==0||strcmp($hostel,"Saraswati")==0))	
$validation_ok=false;
if(strcmp($hostel,"NA")==0)
{
	if(strcmp($postal_address,"")==0)
	{
		$validation_ok=false;
	}
}
else
{
	if(!ctype_digit($room_no)||strcmp($room_no,"")==0)
		$validation_ok=false;
}
if($validation_ok==false)
	header("Location: ../register?status=wrong_info") and die;


//===============================================================================================================================================


//===============================================================================================================================================
//[CREATE NEW ACCOUNT]
	require_once '../dbinfo/dbconnect.php';
			
	$query1 = "SELECT * FROM Users WHERE Lib_ID= " . "'$lib_id'";
	$query2 = "SELECT * FROM Users WHERE Univ_Roll_No= " . "'$univ_roll_no'";
		
	$result1 = mysql_query($query1);
	$result2 = mysql_query($query2);

	
	if (!$result1) die ("Database access failed: " . mysql_error());
	if (!$result2) die ("Database access failed: " . mysql_error());

	
	$rows1 = mysql_num_rows($result1);
	$rows2 = mysql_num_rows($result2);

	
	if($rows1>0)
	{
		header("Location: ../register?status=lib_id_in_use");
	}
	else if($rows2>0)
	{
		header("Location: ../register?status=univ_roll_no_use");
	}
	else
	{
		$query="INSERT INTO Users VALUES" . "('$lib_id','$usrname','$password','$contact_no','$univ_roll_no','$email_id','$hostel','$room_no','$postal_address',0)";
		$result=mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());
		header("Location: ../register?status=account_created");
	}
//===============================================================================================================================================

?>
