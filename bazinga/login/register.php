<?php

//===============================================================================================================================================
//[VARIABLE DECLARATIONS]
$usrname=isset($_POST['name'])?$_POST['name']:"";
$password=isset($_POST['password'])?md5($_POST['password']):"";
$email=isset($_POST['email_id'])?$_POST['email_id']:"";
$loc=isset($_POST['loc'])?$_POST['loc']:"";
$occupation=isset($_POST['occupation'])?$_POST['occupation']:"";
$validation_ok=true;
//===============================================================================================================================================



//===============================================================================================================================================
//[VALIDATE FORM DATA]]
if(strcmp($usrname,"")==0||strcmp($password,"")==0||strcmp($email_id,"")==0||strcmp($loc,"")==0||strcmp($occupation,"")==0)
	$validation_ok=false;
if(strlen($usrname) != strcspn($usrname,"0123456789`-=~@#$%^&*()_+[]{}\|;':,./<>?"))
	$validation_ok=false;
if(strlen($loc) != strcspn($loc,"0123456789`-=~@#$%^&*()_+[]{}\|;':,./<>?"))
	$validation_ok=false;
if(strlen($occupation) != strcspn($occupation,"0123456789`-=~@#$%^&*()_+[]{}\|;':,./<>?"))
	$validation_ok=false;
if(strlen($password) != strcspn($password,"`-=~@#$%^&*()_+[]{}\|;':,./<>?"))
	$validation_ok=false;
if(strlen($email) != strcspn($email,"`-=~#$%^&*()_+[]{}\|;':,/<>?"))
	$validation_ok=false;
if($validation_ok==false)
	header("Location: ../login/?status=wrong_registration_info") and die;
//===============================================================================================================================================


//===============================================================================================================================================
//[CREATE NEW ACCOUNT]
require_once '../dbinfo/dbconnect.php';

$query = "SELECT * FROM Users WHERE Email= " . "'$email'";
$result = mysql_query($query);

if(!$result) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($result);

if($rows>0)
{
	header("Location: ../login/?status=email_id_in_use");
}
else
{
	$query="INSERT INTO Users(Name,Email,Occupation,Location,Pass) VALUES" . "('$usrname','$email','$occupation','$loc','$password')";
	$result=mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	header("Location: ../login?status=account_created");
}

//===============================================================================================================================================

?>
