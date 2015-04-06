<?php
require_once '../dbinfo/dbconnect.php';


//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login="";
$register="";
$redirection="../account_settings/";

//=============================================================================================================================================


//=============================================================================================================================================
//[ IF ALREDY LOGGED IN ]
if(strcmp($username,"Guest")==0)
{
	header("Location: ../home/");
}

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
		echo "<center><h1>Please login to continue.</h1><br><h3>Click <a href='index.php'>here</a> to go to index page.<h3></center>";
		die;
	}
}
//=============================================================================================================================================


//=============================================================================================================================================
//[ SET RATINGS ]
	
if(strcmp($rating,"")!=0)
{
	$text;
	$handle=fopen("contents/ratings.txt","a");
	if(flock($handle, LOCK_EX))
	{
		if(strcmp($rating,"1")==0)
		{
		$text="Yes, very much usefull.\r\n";
		}
		else if(strcmp($rating,"2")==0)
		{
			$text="Yes,but still there's somthng missing!.\r\n";
		}
		else if(strcmp($rating,"3")==0)
		{
			$text="No, but website is ok.\r\n";
		}
			else if(strcmp($rating,"4")==0)
		{	
			$text="No, not at all usefull.\r\n";
		}
		fwrite($handle, $username . " : " . $text);	
		flock($handle, LOCK_UN);
	}
	fclose($handle);
}
//=============================================================================================================================================



//=============================================================================================================================================
//[ VALIDATION SCRIPTS HERE ]

$validation_script=<<<SCRIPT
		function hasOnlyLetters(text)
		{
			for(var i=0;i<text.length;i++)
			{
				if(!((text.charCodeAt(i)>=97&&text.charCodeAt(i)<=122)||(text.charCodeAt(i)>=65&&text.charCodeAt(i)<=90)||(text.charCodeAt(i)==32)))
					return false;
			}
			return true;
		}
		function hasOnlyNumbers(text)
		{
			for(var i=0;i<text.length;i++)
			{
				if(!(text.charCodeAt(i)>=48&&text.charCodeAt(i)<=57))
					return false;
			}
			return true;
		}
		function delete_account()
		{
			var x=confirm("Are you sure you want to delete your account?");
			if(x)
				return true;
			else
				return false;
		}
		function verify_basic_settings_form()
		{
			
			if(document.getElementById('New_Contact_No').value=="")
			{
				alert('Did you forget to mention your Contact No?');
				return false;
			}
			else if(!hasOnlyNumbers(document.getElementById('New_Contact_No').value))
			{
				alert('Please provide a valid  Contact No');
				return false;
			}
			else if(document.getElementById('New_Email_Id').value=="")
			{
				alert('Did you forget to mention your Email ID?');
				return false;
			}
			return true;
		}
		function verify_new_password_form()
		{
			if(document.getElementById('New_Password').value==""||document.getElementById('Confirm_Password').value=="")
			{
				alert('Please enter a password and confirm it.');
				return false;
			}
			else if(document.getElementById('New_Password').value!=document.getElementById('Confirm_Password').value)
			{
				alert('You might want to reconsider your password');
				document.getElementById('Confirm_Password').value=""
				return false;
			}
		}
SCRIPT;

//=============================================================================================================================================


//=============================================================================================================================================
//[ CHECK USER ]

	//$user_status="<div id=""<h1><colored>You have logged in as: </colored>" . $username . " | <a href=../account_settings/>Account Settings</a> | <a href=\"../logout\">Signout</a></h1>";
	$user_status='<div id="status"><h1>Welcome<colored> ' . $username . ' | <a href=../account_settings/>Account Settings</a> | <a href="../logout/">Signout</a></colored></h1></div>';

//=============================================================================================================================================

//=============================================================================================================================================
//[ PREPARE PAGE CONTENT ]


	$page_content.=<<<CONTENT
   			<h1><strong><orange>Account Settings</orange></strong></h1>
    		<br />
			<h3><grey>Basic Account Settings</grey></h3>
			<p>
				Change the values in the respective fields and click Save Changes to make any changes.
			</p>
			<div id="button-right">
				<a href="delete_account.php" onclick="return delete_account();">Delete My Account</a>
			</div>
			<br /><br /><br />
CONTENT;
if(strcmp($status,"changes_saved")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Changes saved successfully.						
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"pwd_changed_successfully")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Password changed successfully.						
					</p>
				</div>
			</div>
CONTENT;
}


//=============================================================================================================================================


//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("/var/www/html/template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=isset($_COOKIE['username'])?str_replace("{Register}","",$template):str_replace("{Register}",$register,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login}","",$template):str_replace("{Login}",$login,$template);
$template=str_replace("{Page-Content}",$page_content,$template);
echo $template;

//=============================================================================================================================================
?>
