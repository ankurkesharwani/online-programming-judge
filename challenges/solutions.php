<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";;
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$search=isset($_REQUEST['search_solution'])?$_REQUEST['search_solution']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login_and_register="";
$redirection="../challenges/";
$page_request=isset($_GET['page_request'])?$_GET['page_request']:"";
$validation_ok=true;
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

//===============================================================================================================================================
//[VALIDATE SEARCH FORM DATA]]
if(strcmp($search,"")==0)
	$validation_ok=false;
if(strspn($search,"1234567890-=`~!@#$%^&*()_+[]{};':,.<>/")!=0)
	$validation_ok=false;
if($validation_ok==false)
	$search="";
//===============================================================================================================================================


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
	function verify()
	{
		if(document.getElementById('lib_id').value==""||document.getElementById('password').value=="")
		{
			alert('Please provide a valid Library Id and Password.');
			return false;
		}
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
CONTENT;
if (isset($_COOKIE['username']))
{
	$page_content.=<<<CONTENT
	<div id="button-left">
	<a href="submit.php">Submit Solutions</a>
	</div>
CONTENT;
}
$page_content.=<<<CONTENT
<div id="button-right">
	<a href="../challenges/">View Challenges</a>
</div>
<br /><br /><br /><br />
<h3><grey>Solutions</grey></h3>	
<form id="search" name ="search" method="post" action="solutions.php/?status=search" onSubmit="return validate_search_form();">
  		<p>
 			<label>Search Challenges By Name</label>
 			<input name="search_solution" id="search_solution" value="" type="text" size="30" />
 			<input class="submitbutton" type="submit" value="Search"/>
 		</p>
 	</form>	
CONTENT;
require_once '../dbinfo/dbconnect.php';
$query="";
if(strcmp($status,"search")!=0)
{
	$query = "SELECT Solutions.Challenge_ID,Challenges.Challenge_Name,Users.User_Name,Solutions.File_Name FROM Solutions,Challenges,Users WHERE Solutions.Lib_ID=Users.Lib_ID AND Challenges.Challenge_ID=Solutions.Challenge_ID ORDER BY Solutions.Challenge_ID DESC LIMIT $a,$b";
}
else
{
	$query = "SELECT Solutions.Challenge_ID,Challenges.Challenge_Name,Users.User_Name,Solutions.File_Name FROM Solutions,Challenges,Users WHERE Solutions.Lib_ID=Users.Lib_ID AND Challenges.Challenge_ID=Solutions.Challenge_ID AND Challenges.Challenge_Name LIKE '%$search%' ORDER BY Challenge_ID DESC LIMIT $a,$b";
}
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);

$page_content.=<<<CONTENT
<table>
<tr><th>Challenge ID</th><th>Problem</th><th>Submitted By</th></tr>
CONTENT;
for( $j=$rows-1;$j>=0;$j--)
{
	$row = mysql_fetch_row($result);
	
	$page_content.='<tr><td>' .  $row[0] . '</td><td><a href="Solutions/' . $row[3] . '">' . $row[1] . '</a></td><td>' . $row[2] . '</td></tr>';
}
$page_content.="</table><br><br>";
if($rows==20)
{
	$a1=$a+20;
	$page_content.="<div id='button-right'>";
	if(strcmp($status,"search")!=0)
		$page_content.="<a href='solutions.php?a=$a1&b=$b'>Next</a>";
	else
		$page_content.="<a href='solutions.php?status=search&search_solution=$search&a=$a1&b=$b'>Next</a>";
	$page_content.="</div>";
}
if($a!=0)
{
	$a1=$a-20;
	$page_content.="<div id='button-left'>";
	if(strcmp($status,"search")!=0)
		$page_content.="<a href='solutions.php?a=$a1&b=$b'>Previous</a>";
	else
		$page_content.="<a href='solutions.php?status=search&search_solution=$search&a=$a1&b=$b'>Previous</a>";
	$page_content.="</div>";
}				
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
