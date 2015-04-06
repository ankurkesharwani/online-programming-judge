<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";;
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login_and_register="";
$redirection="../challenges/";
$validation_ok=true;
$hot_topics="";

//=============================================================================================================================================

//=============================================================================================================================================
//[ IF NOT LOGGED IN ]
if(strcmp($username,"Guest")==0)
	header("Location: ../challenges/");	
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
	$handle=fopen("../contents/ratings.txt","a");
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

	function hasOnlyNumbers(text)
	{
		for(var i=0;i<text.length;i++)
		{
			if(!(text.charCodeAt(i)>=48&&text.charCodeAt(i)<=57))
				return false;
		}
		return true;
	}
	function hasOnlyLetters(text)
	{
		for(var i=0;i<text.length;i++)
		{
			if(!((text.charCodeAt(i)>=97&&text.charCodeAt(i)<=122)||(text.charCodeAt(i)>=65&&text.charCodeAt(i)<=90)||(text.charCodeAt(i)==32)))
				return false;
		}
		return true;
	}
	function validate_submit_form()
	{
		//alert(if(document.getElementById('file').value==""));
		if(document.getElementById('challenge_id').value==""||(!hasOnlyNumbers(document.getElementById('challenge_id').value))||document.getElementById('file').value=="")
		{
			alert('Please provide a valid Challenge Id and File.');
			return false;
		}
	}
	function validate_search_form()
	{
		
		if((document.getElementById('search_challenge').value=="")||(!hasOnlyLetters(document.getElementById('search_challenge').value)))
		{
			alert('Please provide a valid information to search.');
			return false;
		}		
	}
SCRIPT;

//=============================================================================================================================================

//=============================================================================================================================================
//[ CHECK USER ]
	$user_status='<div id="status"><h1>Welcome<colored> ' . $username . ' | <a href=../account_settings/>Account Settings</a> | <a href="../logout/">Signout</a></colored></h1></div>';
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
$page_content=<<<CONTENT
   	<h1><strong><orange>Challenges</orange></strong></h1>
	<div id="message">
		<img src="../resources/images/coder1.png" class="floatright" alt="signboard" />
		<h3><grey>This is the place to improve your skills<grey></h3>
		<p>
			Try your hand at one of the challenge problems, and submit your 
			solution in the language of your choice. Receive points, and move up
			through the cOdErZ ranks. Get better, and better prepare yourself for 
			the competitions.
		</p>
	</div>
	<h4><grey>Before solving any of the challenges please go through the <a href="../contents/Instructions.pdf">instructions</a>.</grey></h4>
	<br />
	<div id="button-left">
	<a href="solutions.php">View Solutions</a>
	</div>
	<div id="button-right">
	<a href="../challenges/">View Challenges</a>
	</div>
	<br /><br /><br /><br />
	<h3><grey>Submit Solution</grey></h3>
	<hr>
CONTENT;
if(strcmp($status,"submission_successfull")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_up.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Your solution was correct.<br />
						Your solution was submitted successfully.
					</p>
				</div>
			</div>
CONTENT;

}
else if(strcmp($status,"submission_failed")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						Your solution seems to be correct however, it was not submitted due to some technical error.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"alredy_submitted")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						You have alredy submitted your solution to this problem.<br />
						You cannot submit any other solution to this problem.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"compile_error")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						Your solution was not compiled.<br />
						Please check if your solution contains any syntax errors and, if you have followed all the instructions properly.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"execution_error")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						Your solution could not be executed properly.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"incorrect_solution")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						It seems that your solution is incorrect.<br />
						It did not give correct output against our test cases.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"correct_solution")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						Your solution seems to be correct however, it was not submitted due to some technical error.
					</p>
				</div>
			</div>
CONTENT;
}
$page_content.=<<<CONTENT
	<fieldset>
		<form id="submit_solution_form" name ="submit_solution_form" method="post" action="submit_solution.php" enctype="multipart/form-data" onSubmit="return validate_submit_form();">
			<p>
				<label>Challenge ID</label>
				<input name="challenge_id" id="challenge_id" value="" type="text" size="30" />
			</p>
			<p>
				<label>File</label>
				<input type="file" name="file" id="file" />
			</p>
			
			<p>
				<label>File Type</label>
				<input type="radio" name="file_type" id="file_type" value="C File" checked="checked" /> C File
				<input type="radio" name="file_type" id="file_type" value="C++ File" /> C++ File
			</p>
			<p>
				<input type="reset" class="resetbutton" value="Reset" />
				<input type="submit" class="submitbutton" value="Submit" />
			</p>		
		</form>
	</fieldset>
CONTENT;
	
//=============================================================================================================================================

//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("../template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=str_replace("{Page-Content}",$page_content,$template);
$template=isset($_COOKIE['username'])?str_replace("{Register}","",$template):str_replace("{Register}",$register,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login}","",$template):str_replace("{Login}",$login,$template);
$template=str_replace("{Hot-Topics}",$hot_topics,$template);
$template=str_replace('<li><a href="../challenges"><span>Challenges</span></a></li>','<li class="selected"><a href="../challenges"><span>Challenges</span></a></li>',$template);

echo $template;

//=============================================================================================================================================
?>
