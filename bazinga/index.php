<?php

$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$u_id=isset($_COOKIE['u_id'])?$_COOKIE['u_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";	


if(strcmp($username,"Guest")!=0)
{
	if(strcmp(md5(md5(md5($username . $u_id))),$key)!=0)
	{
		setcookie('username', '', time() - 2592000, '/');
		setcookie('u_id', '', time() - 2592000, '/');
		setcookie('key', '', time() - 2592000, '/');
		header("Location: ./login/?status=tampered_credentials");
	}
	else
		header("Location: ./news_feed");
}
else
	header("Location: ./login");

?>
