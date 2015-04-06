<?php
//===============================================================================================================================================
//[VARIABLE DECLARATIONS]
$lib_id=isset($_POST['lib_id'])?$_POST['lib_id']:"";
$passwd=isset($_POST['password'])?$_POST['password']:"";
$redirect=isset($_GET['page'])?$_GET['page']:"";
$validation_ok=true;
//===============================================================================================================================================

//===============================================================================================================================================
//[VALIDATE FORM DATA]]
if(!ctype_digit($lib_id))
	$validation_ok=false;
if(strspn($password,"-=`~!@#$%^&*()_+[]{};':,.<>/")!=0)
	$validation_ok=false;
//===============================================================================================================================================

//===============================================================================================================================================
//[ PROVIDE LOGIN ]
	if($validation_ok==false)
		header("Location: $redirect?status=wrong_login_info");	
	else if (isset($_COOKIE['username'])&&isset($_COOKIE['lib_id']))
	{
	 	header("Location: " . $redirect);
	}
	else
	{

		//$usrname=md5($usrname);
		//$passwd=md5($passwd);
	
		require_once '../dbinfo/dbconnect.php';
	
		$query = "SELECT User_Name, Hash_Password FROM Users WHERE Lib_ID= '" . $lib_id . "'";
		$result = mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());

		$rows = mysql_num_rows($result);


		if($rows==1)
		{
			$row = mysql_fetch_row($result);


			if(strcmp(md5($passwd),$row[1])==0)
			{
 				//echo "$row[0] . $lib_id";die;
				setcookie('username', $row[0], time() + 60 * 60 * 24 * 7, '/');
				setcookie('lib_id', $lib_id, time() + 60 * 60 * 24 * 7, '/');
				setcookie('key', md5(md5(md5($row[0] . $lib_id))),time() + 60 * 60 * 24 * 7, '/');
				$query = "SELECT Lib_ID FROM Admins WHERE Lib_ID= '" . $lib_id . "'";
				$result = mysql_query($query);
				if (!$result) die ("Database access failed: " . mysql_error());
				$rows = mysql_num_rows($result);

				if($rows==1)
					setcookie('isadmin', md5(md5("true")), time() + 60 * 60 * 24 * 7, '/');
				header("Location: $redirect");
			}
			else
			{
				header("Location: " . $redirect . "?status=wrong_login_info");
			}


		}
		else if($rows==0)
		{
			header("Location: " . $redirect . "?status=wrong_login_info");
		}


	}

?>
