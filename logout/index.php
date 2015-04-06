<?php
	setcookie('username', '', time() - 2592000, '/');
	setcookie('lib_id', '', time() - 2592000, '/');
	setcookie('isadmin', '', time() - 2592000, '/');
	setcookie('key', '', time() - 2592000, '/');
	header("Location: ../home/");
?>
