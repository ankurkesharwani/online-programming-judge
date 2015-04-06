<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
																											
$rating=isset($_GET['rating'])?$_GET['rating']:"";
$status=isset($_GET['status'])?$_GET['status']:"";;
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$key=isset($_COOKIE['key'])?$_COOKIE['key']:"";
$search=isset($_REQUEST['search_challenge'])?$_REQUEST['search_challenge']:"";
$validation_script="";
$user_status="";
$login_status="";
$page_content="";
$login_and_register="";
$page_name="../challenges/";
$page_request=isset($_GET['page_request'])?$_GET['page_request']:"";
$validation_ok=true;
$a=isset($_GET['a'])?$_GET['a']:0;
$b=isset($_GET['b'])?$_GET['b']:20;

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
		echo "<center><h1>Please login to continue.</h1><br><h3>Click <a href='index.php'>here</a> to go to index page.<h3></center>";
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
	$user_status="<h1><colored>You have logged in as: </colored>" . $username . " | <a href=account_settings.php>Account Settings</a> | <a href=\"logout.php\">Signout</a></h1>";
}
else
{
	$user_status="<h1><colored>You have logged in as: </colored>" . $username . "</h1>";
}
//=============================================================================================================================================

//=============================================================================================================================================
//[ HANDLE LOGIN ]
$login_and_register.=<<<CONTENTS
				<li>
					<h4>Login <strong>Here</strong></h4>
CONTENTS;

if(strcmp($status,"wrong_login_info")==0)
{
	$login_and_register.="<p><i>The Username or Password is incorrect.</i></p>"; 
}
else if(strcmp($status,"wrong_login_info")==0)
{
	$login_and_register.="<p><i>It seems that the information provided contains non-permited characters.</h3>'.</i></p>"; 
}
					
$login_and_register.=<<<CONTENTS
					<form id="login" name ="login" method="post" action="login.php?page=$page_name" onSubmit="return verify();">
  						<p>
  							<label>Library_ID</label>
  							<input name="lib_id" id="lib_id" value="" type="text" size="30" />
  						</p>

  						<p>
  							<label>Password</label>
  							<input name="password" id="password" value="" type="password" size="30" />
  						</p>

  						<p>
  						<input class="formbutton" type="submit" value="Login"/>
  						</p>
  						<p>
  						<input class="formbutton" type="reset" value="Reset"/>
  						</p>
  					</form>					
				
				
				</li>					
				
					
                <li>
					<h4>Register <strong>Yourself</strong></h4>
					<p>
						Click <a href="register.php">here</a> to register yourself in this website. Registration is not mandatory. However, By registering yourself in this site you will be able to create forums and comment on it.
					</p>
                </li>

CONTENTS;
//=============================================================================================================================================



//=============================================================================================================================================
//[ PREPARE PAGE CONTENT ]

$page_content=<<<CONTENT
   	<h1>Challenges</h1><img src="../resources/images/coder1.png" class=float-right />
	<h3>This is the place to improve your skills</h3>
	<p>
		
		
		Try your hand at one of the challenge problems, and submit your 
		solution in the language of your choice. Receive points, and move up
		through the cOdErZ ranks. Get better, and better prepare yourself for 
		the competitions.
		<br>

		<h3>Before solving any of the challenges please go through the <a href="/contents/Instructions.php">instructions</a>.</h3>
		<br>
				<br>
	</p>
CONTENT;

if(strcmp($page_request,"Challenges")==0||strcmp($page_request,"")==0)
{
	if (isset($_COOKIE['username']))
	{
		$page_content.=<<<CONTENT
		<div id="button-left">
		<a href="challenges.php?page_request=submit_solution">Submit Solutions</a>
		</div>
CONTENT;
	}
	$page_content.=<<<CONTENT
	<div id="button-right">
		<a href="challenges.php?page_request=Solutions">View Solutions</a>
	</div>
	<br><br><br>
	<h3>Programming Challenges</h3>	
	<hr>
	<br>
	<form id="search" name ="search" method="post" action="challenges.php?page_request=Challenges&status=search" onSubmit="return validate_search_form();">

  		<p>
  			<label>Search Challenges By Name</label>
  			<input name="search_challenge" id="search_challenge" value="" type="text" size="30" />
  			<input class="formbutton" type="submit" value="Search"/>
  			<input class="formbutton" type="reset" value="Reset"/>
  		</p>

  	</form>	

CONTENT;

	require_once 'dbinfo.php';
	$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
	$query="";
	if(strcmp($status,"search")!=0)
	{
		$query = "SELECT * FROM Challenges ORDER BY Challenge_ID DESC LIMIT $a,$b";
	}
	else
	{
		$query = "SELECT * FROM Challenges WHERE Challenge_Name LIKE '%$search%' ORDER BY Challenge_ID DESC LIMIT $a,$b";
	}
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);

	$page_content.=<<<CONTENT
	<table>
	<tr><th>Challenge ID</th><th>Problem</th><th>Difficulty Level</th></tr>
	
CONTENT;

	for( $j=$rows-1;$j>=0;$j--)
	{
		$row = mysql_fetch_row($result);
		$page_content.='<tr><td>' .  $row[0] . '</td><td><a href="challenges/' . $row[0] . '.pdf">' . $row[1] . '</a></td><td>' . $row[2]=($row[2]==1?'Easy':($row[2]==2?'Medium':'Hard')) . '</td></tr>';
	}
	
	$page_content.="</table><br><br>";
	if($rows==20)
	{
		$a1=$a+20;
		$page_content.="<div id='button-right'>";
		if(strcmp($status,"search")!=0)
			$page_content.="<a href='challenges.php?page_request=Challenges&a=$a1&b=$b'>Next</a>";
		else
			$page_content.="<a href='challenges.php?page_request=Challenges&status=search&search_challenge=$search&a=$a1&b=$b'>Next</a>";
		$page_content.="</div>";

	}
	if($a!=0)
	{
		$a1=$a-20;
		$page_content.="<div id='button-left'>";
		if(strcmp($status,"search")!=0)
			$page_content.="<a href='challenges.php?page_request=Challenges&a=$a1&b=$b'>Previous</a>";
		else
			$page_content.="<a href='challenges.php?page_request=Challenges&status=search&search_challenge=$search&a=$a1&b=$b'>Previous</a>";
		$page_content.="</div>";
CONTENT;
	}
	
	if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
	{
		$page_content.=<<<CONTENT
		<br><br>
		<h3>Delete Challenge</h3>
		<fieldset>
			<form id="delete_challenge_form" name ="delete_challenge_form" method="post" action="challenges_action.php?todo=delete">
				<p>
					<label><b>Challenge ID:</b></label>
					<input name="challenge_id" id="challenge_id" value="" type="text" size="30" />
				</p>
				<p>
					<input type="submit" class="formbutton" value="Delete Challenge"/>
				</p>
				<p>
					<input type="reset" class="formbutton" value="Reset"/>
				</p>
			</form>
		</fieldset>														
				
		<h3>Add Challenge</h3>
		<fieldset>
CONTENT;
		if(strcmp($status,"Challenge_Already_Exists")==0)
		{
			$page_content.="<h4>A challenge with the same ID alredy exists!</h4>";
		}		
		$page_content.=<<<CONTENT
			<form id="add_challenge_form" name ="add_challenge_form" method="post" action="challenges_action.php?todo=add" enctype="multipart/form-data">
				<p>
					<label><b>Challenge ID:</b></label>
					<input name="challenge_id" id="challenge_id" value="" type="text" size="30" />
				</p>
				<p>
					<label><b>Challenge Name:</b></label>
					<input name="challenge_name" id="challenge_name" value="" type="text" size="30" />
				</p>
				<p>
					<label><b>Difficulty:</b></label>
					<input name="challenge_difficulty" id="challenge_difficulty" value="" type="text" size="30" />
				</p>
				<p>
					<label><b>File:</b></label>
					<input type="file" name="file" id="file" />
				</p>
				<p>
					<label><b>Sample Input 1:</b></label>
					<input type="file" name="sample1" id="sample1" />
				</p>
				<p>
					<label><b>Sample Input 2:</b></label>
					<input type="file" name="sample2" id="sample2" />
				</p>
				<p>
					<label><b>Sample Input 3:</b></label>
					<input type="file" name="sample3" id="sample3" />
				</p>
				<p>
					<label><b>Sample Output 1:</b></label>
					<input type="file" name="output1" id="output1" />
				</p>
				<p>
					<label><b>Sample Output 2:</b></label>
					<input type="file" name="output2" id="output2" />
				</p>
				<p>
					<label><b>Sample Output 3:</b></label>
					<input type="file" name="output3" id="output3" />
				</p>
				<p>
					<input type="submit" class="formbutton" value="Add Challenge"/>
				</p>
				<p>
					<input type="reset" class="formbutton" value="Reset"/>
				</p>
			</form>
		</fieldset>
		<br>
CONTENT;
	}					
}
else if(strcmp($page_request,"Solutions")==0)
{
	if (isset($_COOKIE['username']))
	{
		$page_content.=<<<CONTENT
		<div id="button-left">
		<a href="challenges.php?page_request=submit_solution">Submit Solutions</a>
		</div>
CONTENT;
	}
	$page_content.=<<<CONTENT
	<div id="button-right">
		<a href="challenges.php?page_request=Challenges">View Challenges</a>
	</div>
	<br><br><br>
	<h3>Solutions To Challenges</h3>	
	<hr>
	<br>
	<form id="search" name ="search" method="post" action="challenges.php?page_request=Solutions&status=search" onSubmit="return validate_search_form();">

  		<p>
  			<label>Search Solutions By Name</label>
  			<input name="search_challenge" id="search_challenge" value="" type="text" size="30" />
  			<input class="formbutton" type="submit" value="Search"/>
  			<input class="formbutton" type="reset" value="Reset"/>
  		</p>

  	</form>	
CONTENT;

	require_once 'dbinfo.php';
	$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
	$query="";
	if(strcmp($status,"search")!=0)
	{
		$query = "SELECT * FROM Solutions ORDER BY Challenge_ID DESC LIMIT $a,$b";
	}
	else
	{
		$query = "SELECT * FROM Solutions WHERE Challenge_Name LIKE '%$search%' ORDER BY Challenge_ID DESC LIMIT $a,$b";
	}
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);
	
	$page_content.=<<<CONTENT
	<table>
	<tr><th>Challenge ID</th><th>Challenge</th><th>Submitted By</th></tr>
	
CONTENT;

	for( $j=0;$j<$rows;$j++)
	{
		$row = mysql_fetch_row($result);
		$page_content.='<tr><td>' .  $row[0] . '</td><td><a href="solutions/' . $row[3] . '">' . $row[1] . '</a></td><td>' . $row[2] . '</td></tr>';
	}
	
	$page_content.="</table><br><br>";
	if($rows==20)
	{
		$a1=$a+20;
		$page_content.="<div id='button-right'>";
		if(strcmp($status,"search")!=0)
			$page_content.="<a href='challenges.php?page_request=Solutions&a=$a1&b=$b'>Next</a>";
		else
			$page_content.="<a href='challenges.php?page_request=Solutions&status=search&search_challenge=$search&a=$a1&b=$b'>Next</a>";
		$page_content.="</div>";

	}
	if($a!=0)
	{
		$a1=$a-20;
		$page_content.="<div id='button-left'>";
		if(strcmp($status,"search")!=0)
			$page_content.="<a href='challenges.php?page_request=Solutions&a=$a1&b=$b'>Previous</a>";
		else
			$page_content.="<a href='challenges.php?page_request=Solutions&status=search&search_challenge=$search&a=$a1&b=$b'>Previous</a>";
		$page_content.="</div>";
	}
}
else if(strcmp($page_request,"submit_solution")==0&&isset($_COOKIE['username']))
{
	$page_content.=<<<CONTENT
	<div id="button-left">
	<a href="challenges.php?page_request=Solutions">View Solutions</a>
	</div>
	<div id="button-right">
	<a href="challenges.php?page_request=Challenges">View Challenges</a>
	</div>
	<br><br>
	<h3>Submit Solution</h3>
	<hr><br><br>
CONTENT;

	if(strcmp($status,"submission_successfull")==0)

		$page_content.='<fieldset><img src="resources/images/thumbs_up.jpg" class=info /><h3>Congrats!<h3><p>Your solution was correct.</p><p>Your solution was submitted successfully.</p></fieldset><br>';	
	else if(strcmp($status,"submission_failed")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>Your solution seems to be correct however, it was not submitted due to some technical error.<p></fieldset><br>';
	else if(strcmp($status,"alredy_submitted")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>You have alredy submitted your solution to this problem.</p><p>You cannot submit any other solution to this problem.</p></fieldset><br>';
	else if(strcmp($status,"compile_error")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>Your solution was not compiled.</p><p>Please check if your solution contains any syntax errors and, if you have followed all the instructions properly.</p></fieldset><br>';
	else if(strcmp($status,"execution_error")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>Your solution could not be executed properly.</p></fieldset><br>';
	else if(strcmp($status,"incorrect_solution")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>It seems that your solution is incorrect.</p><p>It did not give correct output against our test cases.</p></fieldset><br>';
	else if(strcmp($status,"correct_solution")==0)
		$page_content.= '<fieldset><img src="resources/images/thumbs_down.jpg" class=info /><h3>Oops!<h3><p>Your solution seems to be correct however, it was not submitted due to some technical error.<p></fieldset><br>';
			

	$page_content.=<<<CONTENT
	<fieldset>
		<form id="submit_solution_form" name ="submit_solution_form" method="post" action="challenges_action.php?todo=submit_solution" enctype="multipart/form-data" onSubmit="return validate_submit_form();">
			<p>
				<label><b>Challenge ID:</b></label>
				<input name="challenge_id" id="challenge_id" value="" type="text" size="30" />
			</p>
			<p>
				<label><b>File:</b></label>
				<input type="file" name="file" id="file" />
			</p>
			
			<p>
				<label><b>File Type:</b></label>
				<input type="radio" name="file_type" id="file_type" value="C File" checked="checked" /> C File
				<input type="radio" name="file_type" id="file_type" value="C++ File" /> C++ File
			</p>
			<p>
				<input type="submit" class="formbutton" value="Submit" />
			</p>
			<p>
				<input type="reset" class="formbutton" value="Reset" />
			</p>		
		</form>
	</fieldset>
CONTENT;
	
}

//=============================================================================================================================================


//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("/var/www/html/template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=str_replace("{Page-Content}",$page_content,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login-Register}","",$template):str_replace("{Login-Register}",$login_and_register,$template);
$template=str_replace('<li><a href="challenges.php"><span>Challenges</span></a></li>','<li class="selected"><a href="challenges.php"><span>Challenges</span></a></li>',$template);

echo $template;

//=============================================================================================================================================
?>
