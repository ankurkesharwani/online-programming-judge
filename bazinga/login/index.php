<?php
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$u_id=isset($_COOKIE['u_id'])?$_COOKIE['u_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";	
$status=isset($_GET['status'])?$_GET['status']:"";	
if(strcmp($username,"Guest")!=0)
{
	if(strcmp(md5(md5(md5($username . $u_id))),$key)!=0)
	{
		setcookie('username', '', time() - 2592000, '/');
		setcookie('u_id', '', time() - 2592000, '/');
		setcookie('key', '', time() - 2592000, '/');
	}
	else
	{
		header("Location: ../news_feed");
	}
}
$template=file_get_contents("./template");
$template=str_replace("{ACTION_REGISTER}","./register.php",$template);
if(strcmp($status,"")==0)
	$template=str_replace("{MESSAGE}","",$template);
else if(strcmp($status,"tampered_credentials")==0)
	$template=str_replace("{MESSAGE}","onLoad=\"alert('It looks like someone has been trying to illegally access your account. You need to re-login to proceed.');\"",$template);
else if(strcmp($status,"wrong_registration_info")==0)
	$template=str_replace("{MESSAGE}","onLoad=\"alert('Please fill the registration form using valid characters only!');\"",$template);
else if(strcmp($status,"email_id_in_use")==0)
	$template=str_replace("{MESSAGE}","onLoad=\"alert('This email id is already in use!');\"",$template);
else if(strcmp($status,"account_created")==0)
	$template=str_replace("{MESSAGE}","onLoad=\"alert('Account created successfully. You can now login to your account.');\"",$template);
echo $template;
?>
