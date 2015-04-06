<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";
$id=isset($_GET['id'])?$_GET['id']:"";
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$search=isset($_REQUEST['search_topics'])?$_REQUEST['search_topics']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login="";
$register="";
$redirection="../techtalks";
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
	function hasOnlyLetters(text)
	{
		for(var i=0;i<text.length;i++)
		{
			if(!((text.charCodeAt(i)>=97&&text.charCodeAt(i)<=122)||(text.charCodeAt(i)>=65&&text.charCodeAt(i)<=90)||(text.charCodeAt(i)==32)))
				return false;
		}
		return true;
	}
	function validate_comment_form()
	{
		if(document.getElementById('comment').value=="")
		{
			alert('Please tell us the topic for discussion.');
			return false;
		}
		else if((document.getElementById('comment').value).length>200)
		{
			alert('Please shorten your comment upto 200 characters only.');
			return false;
		}
	}
	function validate_search_form()
	{
		
		if((document.getElementById('search_topics').value=="")||(!hasOnlyLetters(document.getElementById('search_topics').value)))
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
$query = "SELECT Topic,Description,Users.User_Name FROM Topics,Users WHERE Topics.Lib_ID=Users.Lib_ID AND Topics.T_ID=$id";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$row = mysql_fetch_row($result);
$page_content=<<<CONTENT
   	<h1><strong><orange>TechTalks</orange></strong></h1>
	<br />
	<div id="message">
		<img src="../resources/images/talk.jpg" class="floatleft" alt="signboard" />
		<h3><grey>$row[0]</grey></h3>
		<h5><orange>($row[2])</orange></h5>
		<p>
			$row[1]
		</p>
	</div>
    <br />
	<br />
CONTENT;
$query = "SELECT Users.User_Name,Comments.Commnt,Comments.Lib_Id,Comments.C_ID FROM Users,Comments WHERE Comments.Lib_ID=Users.Lib_ID AND Comments.T_ID=$id LIMIT $a,$b";
$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);

for($j=0;$j<$rows;$j++)
{
	$row = mysql_fetch_row($result);
	$page_content.='<div id="comment_box">';
	if(($lib_id==$row[2])||(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0)))
	{
		$page_content.="<h5><orange>$row[0]</orange> <grey>Says...    [<a href='delete_comment.php?id=$id&c_id=$row[3]'>Delete</a>]</grey>  </h5>";
	}
	else
	{
		$page_content.="<h5><orange>$row[0]</orange> <grey>Says...</grey></h5>";
	}
	$page_content.="<p>$row[1]</p></div>";
}
if($rows==20)
{
	$a1=$a+20;
	$page_content.="<div id='button-right'>";
	$page_content.="<a href='../techtalks/view_thread.php?a=$a1&b=$b&id=$id'>Next</a>";
	$page_content.="</div>";
}
if($a!=0)
{
	$a1=$a-20;
	$page_content.="<div id='button-left'>";
	$page_content.="<a href='../techtalks/view_thread.php?a=$a1&b=$b&id=$id'>Previous</a>";
	$page_content.="</div>";
}

if(strcmp($username,"Guest")!=0)
{
	$page_content.=<<<CONTENT
	<br /><br /><br /><br />
	<h3><grey>Comment</grey></h3>
	<p>
		Please note that this is not a chat thread. Content of each topic will be monitered closely by the administrators, and any irrelevent topic or comment may be deleted without any prior warning.
	</p>
	<fieldset>
		<form id="commentform" name ="commentform" method="post" action="make_comment.php?id=$id" onSubmit="return validate_comment_form();">			
  			<p>
  				<label>Comment</label>
  				<textarea name="comment" id="comment" rows="5" cols="30" ></textarea>
  			</p>
			<p>
				<input type="reset" class="resetbutton" value="Reset"/>
				<input type="submit" class="submitbutton" value="Comment"/>
			</p>

		</form>
	</fieldset>
CONTENT;
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
$template=str_replace('<li><a href="../techtalks"><span>Tech Talks</span></a></li>','<li class="selected"><a href="../techtalks"><span>Tech Talks</span></a></li>',$template);
$template=str_replace("{Page-Content}",$page_content,$template);
echo $template;

//=============================================================================================================================================
?>

