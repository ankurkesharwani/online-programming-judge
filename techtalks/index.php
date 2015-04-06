<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";
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
	function validate_new_topic_form()
	{
		if(document.getElementById('topic').value=="")
		{
			alert('Please tell us the topic for discussion.');
			return false;
		}
		else if(!hasOnlyLetters(document.getElementById('topic').value))
		{
			alert('Only letters are allowed in the Topic field.');
			return false;
		}
		else if(document.getElementById('description').value=="")
		{
			alert('Please tell us something about the topic.');
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
$page_content=<<<CONTENT
   	<h1><strong><orange>TechTalks</orange></strong></h1>
	<br />
	<div id="message">
		<img src="../resources/images/talk2.jpg" class="floatleft" alt="signboard" />
		<h3><grey>Technical Forum</grey></h3>
		<p>
			You can create a topic for disscussion here. Use this section to communicate with fellow programmers and share your problems and solutions.
		</p>

	</div>
    <br />
	<br />
CONTENT;


$page_content.=<<<CONTENT
   	<h3><grey>Topics</grey></h3>
	<form id="search" name ="search" method="post" action="../techtalks/?status=search" onSubmit="return validate_search_form();">
  		<p>
 			<label>Search Topic</label>
 			<input name="search_topics" id="search_topics" value="" type="text" size="30" />
 			<input class="submitbutton" type="submit" value="Search"/>
 		</p>
 	</form>	

CONTENT;

$query="";
if(strcmp($status,"search")!=0)
{
	$query = "select Topics.T_ID,Topics.Topic, Users.User_Name from Topics,Users Where Topics.Lib_ID=Users.Lib_ID ORDER BY Topics.T_ID DESC LIMIT $a,$b";
}
else
{
	$query = "select Topics.T_ID,Topics.Topic, Users.User_Name from Topics,Users Where Topics.Lib_ID=Users.Lib_ID AND Topics.Topic LIKE '%$search%' ORDER BY Topics.T_ID DESC LIMIT $a,$b";
}

$result = mysql_query($query);
if (!$result) die ("Database access failed: " . mysql_error());
$rows = mysql_num_rows($result);
if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{
	$page_content.=<<<CONTENT
	<table>
	<tr><th>Topics</th><th>Created By</th><th>Action</th></tr>
CONTENT;
	for( $j=$rows-1;$j>=0;$j--)
	{
		$row = mysql_fetch_row($result);
	
		$page_content.='<tr><td><a href="view_thread.php?id=' . $row[0] . '">' .  $row[1] . '</a></td><td>' .  $row[2] . '</td><td>' .' <a href="delete_topic.php?id=' . $row[0] . '">delete</a></td></tr>';
	}
	$page_content.="</table>";

}
else
{
	$page_content.=<<<CONTENT
	<table>
	<tr><th>Topics</th><th>Created By</th></tr>
CONTENT;
	for( $j=$rows-1;$j>=0;$j--)
	{
		$row = mysql_fetch_row($result);
	
		$page_content.='<tr><td><a href="view_thread.php?id=' . $row[0] . '">' .  $row[1] . '</a></td><td>' .  $row[2] . '</td></tr>';
	}
	$page_content.="</table>";
}
if($rows==20)
{
	$a1=$a+20;
	$page_content.="<div id='button-right'>";
	if(strcmp($status,"search")!=0)
		$page_content.="<a href='../techtalks?a=$a1&b=$b'>Next</a>";
	else
		$page_content.="<a href='../techtalks?status=search&search_topics=$search&a=$a1&b=$b'>Next</a>";
	$page_content.="</div>";
}
if($a!=0)
{
	$a1=$a-20;
	$page_content.="<div id='button-left'>";
	if(strcmp($status,"search")!=0)
		$page_content.="<a href='../techtalks?a=$a1&b=$b'>Previous</a>";
	else
		$page_content.="<a href='../techtalks?status=search&search_topics=$search&a=$a1&b=$b'>Previous</a>";
	$page_content.="</div>";
}

$page_content.="<br /><br />";
if(strcmp($username,"Guest")!=0)
{
	$page_content.="<h3><grey>Create a new topic for discussion</grey></h3>";
	if(strcmp($status,"topic_created")==0)
	{
		$page_content.=<<<CONTENT
			<div id="message">
				<img src="../resources/images/thumbs_up.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Your topic was created successfully.
					</p>
				</div>
			</div>
CONTENT;
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
						The topic could not be craeted due to invalid information submitted.
					</p>
				</div>
			</div>
CONTENT;
	}

	$page_content.=<<<CONTENT
			
			<fieldset>
				<form id="newtopic" name ="newtopic" method="post" action="create_topic.php" onSubmit="return validate_new_topic_form();">
					<!--<img src="../resources/images/1477thumbnail.jpg" class=info />	-->				
					<p>
						<label>Topic Title</label>
						<input name="topic" id="topic" value="" type="text" size="30" />
					</p>
  					<p>
  						<label>Description</label>
  						<textarea name="description" id="description" rows="5" cols="0" ></textarea>
  					</p>
					<p>
						<input type="reset" class="resetbutton" value="Reset"/>
						<input type="submit" class="submitbutton" value="Create Topic"/>
					</p>

				</form>
			</fieldset>

CONTENT;
}
$page_content.="<br /><br />";

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

