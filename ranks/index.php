<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";
$todo=isset($_GET['todo'])?$_GET['todo']:"";
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login="";
$register="";
$redirection="../ranks";
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

$page_content.=<<<CONTENT
   	<h1><strong><orange>Ranks</orange></strong></h1>
    <br />
	<div id="message">
		<img src="../resources/images/trophy.jpg" class="floatleft" alt="signboard" />
		<h3><grey>Know your <orange>cOdE</orange>rS rank here</grey></h3>
		<p> 
			Here we post the names of Top-Twentey coders. To come under the top twentey participate solve challenge problems, submit your solution in the language of your choice and Receive points.
		</p>
	</div>
	<br>
	
CONTENT;

require_once '../dbinfo/dbconnect.php';


if(isset($_COOKIE['username'])&&isset($_COOKIE['lib_id']))
{

	$query = "SELECT Score FROM Users WHERE Lib_ID=$lib_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);
	$row=mysql_fetch_row($result);
	$total_score=$row[0];

	$query = "SELECT COUNT(*) FROM Solutions WHERE Lib_ID=$lib_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$row=mysql_fetch_row($result);
	$total_no_of_solutions=$row[0];
	
	$query = "SELECT COUNT(*) FROM Solutions,Challenges WHERE Solutions.Lib_ID=$lib_id AND Challenges.Challenge_ID=Solutions.Challenge_ID AND Challenges.Difficulty=3";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$row=mysql_fetch_row($result);
	$total_no_of_d_solutions=$row[0];

	$query = "SELECT COUNT(*) FROM Solutions,Challenges WHERE Solutions.Lib_ID=$lib_id AND Challenges.Challenge_ID=Solutions.Challenge_ID AND Challenges.Difficulty=2";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$row=mysql_fetch_row($result);
	$total_no_of_m_solutions=$row[0];

	$query = "SELECT COUNT(*) FROM Solutions,Challenges WHERE Solutions.Lib_ID=$lib_id AND Challenges.Challenge_ID=Solutions.Challenge_ID AND Challenges.Difficulty=1";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$row=mysql_fetch_row($result);
	$total_no_of_e_solutions=$row[0];
	$page_content.=<<<CONTENT


	<br /><br /><br />
	<div id="message">
		<img src="../resources/images/cool.jpg" class="floatleft" alt="signboard" />
		<div id="bubble">
			<h3><orange>Your Status</orange></h3>
			<p>
				Your Total Score: <b>$total_score</b>.<br />
				Total Successfull Submissions: <b>$total_no_of_solutions</b>.<br />
				Challenges Solved With Hard Difficulty Level: <b>$total_no_of_d_solutions</b>.<br />
				Challenges Solved With Medium Difficulty Level: <b>$total_no_of_m_solutions</b>.<br />
				Challenges Solved With Easy Difficulty Level: <b>$total_no_of_e_solutions</b>.
			</p>
		</div>
	</div>
	<br /><br />




CONTENT;
}
$query = "SELECT User_Name,Score FROM Users ORDER BY Score DESC Limit 20";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
$page_content.=<<<CONTENT
	<br />
	<h3><grey>Top Twenty Coderz</grey></h3>
	<table>
	<tr><th>Username</th><th>Score</th></tr>
	
CONTENT;

for( $j=0;$j<$rows;$j++)
{
	$row = mysql_fetch_row($result);
	$page_content.="<tr><td>$row[0]</td><td>$row[1]</td></tr>";
}

$page_content.="</table><br /><br /><br />";



//=============================================================================================================================================



//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("../template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=isset($_COOKIE['username'])?str_replace("{Register}","",$template):str_replace("{Register}",$register,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login}","",$template):str_replace("{Login}",$login,$template);
$template=str_replace("{Hot-Topics}",$hot_topics,$template);
$template=str_replace('<li><a href="../ranks"><span>Ranks</span></a></li>','<li class="selected"><a href="../ranks"><span>Ranks</span></a></li>',$template);
$template=str_replace("{Page-Content}",$page_content,$template);
echo $template;

//=============================================================================================================================================
?>
