<?php
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
$redirection="../messages";
$a=isset($_GET['a'])?$_GET['a']:0;
$b=isset($_GET['b'])?$_GET['b']:20;
$hot_topics="";
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
	function verify()
	{
		if(document.getElementById('lib_id').value==""||document.getElementById('password').value=="")
		{
			alert('Please provide a valid Library Id and Password.');
			return false;
		}
	}
	function validate_message_form()
	{
		if(document.getElementById('name').value=="")
		{
			alert('How is that you want to send us a message and don\'t want to tell your name?');
			return false;
		}
		else if(document.getElementById('message').value=="")
		{
			alert('Do you really want to send us a blank message?');
			return false;
		}
	}
SCRIPT;

//=============================================================================================================================================


//=============================================================================================================================================
//[ CHECK USER ]
if (isset($_COOKIE['username']))
{
	//$user_status="<div id=""<h1><colored>You have logged in as: </colored>" . $username . " | <a href=../account_settings/>Account Settings</a> | <a href=\"../logout\">Signout</a></h1>";
	$user_status='<div id="status"><h1>Welcome<colored> ' . $username . ' | <a href=../account_settings/>Account Settings</a> | <a href="../logout/">Signout</a></colored></h1></div>';
}
else
{
	$user_status='<div id="status"><h1>Welcome<colored> Guest</colored></h1></div>';
}
//=============================================================================================================================================


//=============================================================================================================================================
//[ HANDLE LOGIN ]
$login=<<<CONTENTS
		<div id="login" >
			<round-heading><a href="#" onclick="javascript:animate();">[ Login Here ]</a></round-heading>
			<div id="bordered-panel">
CONTENTS;
if(strcmp($status,"wrong_login_info")==0)
{
	$login.="<p>The Username or Password is incorrect</p>"; 
}
else if(strcmp($status,"wrong_login_info")==0)
{
	$login.="<p>It seems that the information provided contains non-permited characters</p>"; 
}
$login.=<<<CONTENTS
				<fieldset>
					<form method="post" action="../login/?page=$redirection" onSubmit="return verify();">
						<p>
							<label for="lib_id">Lib Id</label>
							<input id="lib_id" name="lib_id" value=""  type="text" tabindex="2" />
						</p>
						<p>
							<label for="password">Password</label>
							<input id="password" name="password" value="" type="password" tabindex="2" />
						</p>
						<p>
							<input class="formbutton" type="submit" value="Login" tabindex="5" />
         					<input class="formbutton" type="reset" value="Reset" tabindex="6" />
						</p>			
					</form>
				</fieldset>
			</div>
		</div>
CONTENTS;

$register=<<<CONTENTS
			<div id="sidebar-div">
				<h4>Register <strong>Here</strong></h4>
				<p>
					Come and join the cOdErS community, get to know fellow programmers, participate in Challenges, Contests & Tech Talks. We have so much to offer and you are just one step away.<Br><br>Click <a href="../register">here</a> to register.   
				</p>
			</div>
CONTENTS;
//=============================================================================================================================================

//=============================================================================================================================================
//[ HOT TOPICS ]
require_once '../dbinfo/dbconnect.php';
$query="SELECT Topics.T_ID,Topics.Topic,Topics.Description FROM Topics ORDER BY Topics.T_ID DESC LIMIT 5";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
for($i=0;$i<$rows;$i++)
{
	$row = mysql_fetch_row($result);
	$hot_topics.=<<<CONTENTS
				<p>
					<a href="../techtalks/view_thread.php?id=$row[0]">$row[1]</a> - $row[2]
				</p>
CONTENTS;
}
//=============================================================================================================================================

//=============================================================================================================================================
//[ PREPARE PAGE CONTENT ]
require_once '../dbinfo/dbconnect.php';
$page_content=<<<CONTENT
   	<h1><strong><orange>Contact Us</orange></strong></h1>
	<br />
	<div id="message">
		<img src="../resources/images/message.jpg" class="floatleft" alt="signboard" />
		<h3><grey>Send us a message</grey></h3>
		<p>
			You can contact us by sending a message here. Please use this section to give us your feedback, suggesstions and criticism.
			Help us develop cOdErS a better place to learn.   
		</p>
	</div>
    <br />
CONTENT;
if(strcmp($status,"message_sent")==0)
{
	$page_content.=<<<CONTENTS
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_up.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Your message was sent successfully.
					</p>
				</div>
			</div>
CONTENTS;
}
else if(strcmp($status,"wrong_info")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						You did not submit correct information.
					</p>
				</div>
			</div>
CONTENT;
}
$page_content.=<<<CONTENT
			<fieldset>
				<form id="contactMeForm" name ="contactMeForm" method="post" action="record_message.php" onSubmit="return validate_message_form();">
					<p>
						<label>Your Name</label>
CONTENT;
if(strcmp($username,"Guest")==0)
	$page_content.=					'<input name="name" id="name" value="" type="text" size="30" />';
else
	$page_content.=					'<input name="name" id="name" readonly="readonly" type="text" size="30" value="' . $username . '" />';
$page_content.=<<<CONTENT
					</p>
					<p>
						<label>Your Email Id</label>
CONTENT;
if(strcmp($username,"Guest")==0)
	$page_content.=					'<input name="email" id="email" value="" type="text" size="30" />';
else
{
	$query = "SELECT Email_ID FROM Users WHERE Lib_ID=$lib_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);	
	$row = mysql_fetch_row($result);
	$page_content.=					'<input name="email" id="email" readonly="readonly" type="text" size="30" value="' . $row[0] . '" />';


}
$page_content.=<<<CONTENT
					</p>
					<p>
						<label>Your Message</label>
						<textarea name="message" id="message" rows="5" cols="30"></textarea>
					</p>
					<p>
						<input type="reset" class="resetbutton" value="Reset"/>
						<input type="submit" class="submitbutton" value="Send Message"/>
					</p>
					<p><i>*Give your email id if you are expecting replies from us.</i></p>
				</form>
			</fieldset>
CONTENT;

if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{

	
	$query = "SELECT * FROM Messages ORDER BY M_Id DESC LIMIT $a,$b";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);
	$page_content.=<<<CONTENT
	<h3><grey>Message List</grey></h3>
	<br />
			<div id="message">
				<img src="../resources/images/msgboy.jpg" class="floatleft" alt="signboard" />
				
CONTENT;
	for( $j=$rows-1;$j>=0;$j--)
	{
		$row = mysql_fetch_row($result);
		$page_content.=<<<CONTENT
		<div id="bubble">

			<p>
				<h5><grey>$row[0] [$row[1]] [<a href="delete_message.php?id=$row[3]">Delete</a>]</grey></h5>
			</p>
			<p>
				$row[2]
			</p>

		</div>
		<br />
CONTENT;
	}
	$page_content.="</div>";
	if($rows==20)
	{
		$a1=$a+20;
		$page_content.="<div id='button-right'>";
		$page_content.="<a href='../messages/?a=$a1&b=$b'>Next</a>";
		$page_content.="</div>";
	}
	if($a!=0)
	{
		$a1=$a-20;
		$page_content.="<div id='button-left'>";
		$page_content.="<a href='../messages/?a=$a1&b=$b'>Previous</a>";
		$page_content.="</div>";
	}



}

//=============================================================================================================================================



//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("../template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=isset($_COOKIE['username'])?str_replace("{Register}","",$template):str_replace("{Register}",$register,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login}","",$template):str_replace("{Login}",$login,$template);
$template=str_replace("{Hot-Topics}",$hot_topics,$template);
$template=str_replace('<li><a href="../messages"><span>Contact Us</span></a></li>','<li class="selected"><a href="../messages"><span>Contact Us</span></a></li>',$template);
$template=str_replace("{Page-Content}",$page_content,$template);
echo $template;

//=============================================================================================================================================
?>

