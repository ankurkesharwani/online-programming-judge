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
$redirection="../home/";

//=============================================================================================================================================

//=============================================================================================================================================
//[ IF ALREDY LOGGED IN ]
if(strcmp($username,"Guest")!=0)
{
	header("Location: ../home/");
}

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
		function validate_login_form()
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
		function hasOnlyNumbers(text)
		{
			for(var i=0;i<text.length;i++)
			{
				if(!(text.charCodeAt(i)>=48&&text.charCodeAt(i)<=57))
					return false;
			}
			return true;
		}
		function isValidEmailId(text)
		{
			var atpos=text.indexOf("@");
			var dotpos=text.lastIndexOf(".");
			if (atpos<1 || dotpos<atpos+2 || dotpos+2>=text.length)
				return false;
			else 
				return true;
		}
		function verify_register_form()
		{
			
			if(document.getElementById('User_Name').value=="")
			{
				alert('Did you forget to mention your user name?');
				return false;
			}
			else if(!hasOnlyLetters(document.getElementById('User_Name').value))
			{
				alert('Only letters are allowed for Name.');
				return false;
			}
			else if(document.getElementById('Lib_Id').value=="")
			{
				alert('Did you forget to mention your Library ID?');
				return false;
			}
			else if(!hasOnlyNumbers(document.getElementById('Lib_Id').value))
			{
				alert('Only Numbers are allowed for Library Ids');
				return false;
			}
			else if(document.getElementById('Univ_Roll_No').value=="")
			{
				alert('Did you forget to mention your University Roll No.?');
				return false;
			}
			else if((document.getElementById('Univ_Roll_No').value).length>10)
			{
				alert('Incorrect University Roll No!');
				return false;
			}
			else if(!hasOnlyNumbers(document.getElementById('Univ_Roll_No').value))
			{
				alert('Only Numbers are allowed for University Roll No!');
				return false;
			}
			else if(!hasOnlyNumbers(document.getElementById('Contact_No').value))
			{

				alert('Only Numbers are allowed for Contact No!');
				return false;
			}
			else if(document.getElementById('Contact_No').value==0)
			{
				alert('Did you forget to mention your contact no?');
				return false;
			}
			else if(!isValidEmailId(document.getElementById('Email_Id').value))
			{
				alert('Please provide a valid Email ID?');
				return false;
			}
			else if(document.getElementById('Email_Id').value=="")
			{
				alert('Did you forget to mention your Email ID?');
				return false;
			}
			else if(document.getElementById('Hostel').value=="NA"&&document.getElementById('Postal_Address').value=="")
			{
				alert('Please let us know your postal address.');
				return false;
			}
			else if(document.getElementById('Hostel').value!="NA"&&document.getElementById('Room_No').value=="")
			{
				alert('Please let us know your room number.');
				return false;
			}
			else if(document.getElementById('Hostel').value!="NA"&&(!hasOnlyNumbers(document.getElementById('Room_No').value)))
			{
				alert('Invalid room number.');
				return false;
			}
			else if(document.getElementById('Password').value==""||document.getElementById('Confirm_Password').value=="")
			{
				alert('Please enter a password and confirm it.');
				return false;
			}
			else if(document.getElementById('Password').value!=document.getElementById('Confirm_Password').value)
			{
				alert('You might want to reconsider your password');
				document.getElementById('Confirm_Password').value=""
				return false;
			}

			return true;
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
//[ PREPARE PAGE CONTENT ]

$page_content=<<<CONTENT
   	<h1><strong><orange>Register</orange></strong></h1>
	<br />
	<div id="message">
		<img src="../resources/images/register.jpg" class="floatleft" alt="signboard" />
		<h3><grey>Join </grey><orange>cOdE</orange><grey>rS</grey></h3>
		<p>
			Come and join cOdErS. Fill in the following form to get yourself registered to this website. Registration is not mandatory. In any case, if you register or not, you are free to browse any content on this website. However, By registering yourself you will be able to participate in Challenges, Contests and Tech Talks.
		</p>	
	</div>
CONTENT;

if(strcmp($status,"user_name_in_use")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						The user name is already in use. Please register using a different username.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"lib_id_in_use")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						The library id is already in use. Please register using a genuine information.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"univ_roll_no_use")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_down.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Oops!</orange></h3>
					<p>
						The university roll number is already in use. Please register using a genuine information.
					</p>
				</div>
			</div>
CONTENT;
}
else if(strcmp($status,"account_created")==0)
{
	$page_content.=<<<CONTENT
			<br /><br /><br />
			<div id="message">
				<img src="../resources/images/thumbs_up.jpg" class="floatleft" alt="signboard" />
				<div id="bubble">
					<h3><orange>Congrats!</orange></h3>
					<p>
						Your account was created successfully.
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
						It seems that the information provided contains non-permited characters.
					</p>
				</div>
			</div>
CONTENT;
}

$page_content.=<<<FORM
			<fieldset>

				<form id="register" name ="register" method="post" action="create_account.php" onSubmit="return verify_register_form();">
										
					<p>
  						<label>Name</label>
  						<input name="User_Name" id="User_Name" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>Library Id.</label>
  						<input name="Lib_Id" id="Lib_Id" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>University Roll No.</label>
  						<input name="Univ_Roll_No" id="Univ_Roll_No" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>Contact No.</label>
  						<input name="Contact_No" id="Contact_No" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>Email Id</label>
  						<input name="Email_Id" id="Email_Id" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>Hostel*</label>
  						<select name="Hostel" id="Hostel"> 
							<option value="NA">Not A Hosteller</option>
  							<option value="Aryabhatta">Aryabhatta Hall Of Residence</option>
  							<option value="Vivekanand">Vivekanand Hall Of Residence</option>
  							<option value="Taigore">Taigore Hall Of Residence</option>
  							<option value="Sarojni">Sarojni Hall Of Residence</option>
							<option value="Saraswati">Saraswati Hall Of Residence</option>
						</select>
  					</p>
  					<p>
  						<label>Room No*</label>
  						<input name="Room_No" id="Room_No" value="" type="text" size="30" />
  					</p>
  					<p>
  						<label>Postal Address**</label>
  						<textarea name="Postal_Address" id="Postal_Address" rows="5" cols="0" ></textarea>
  					</p>

  					<p>
  						<label>Password</label>
  						<input name="Password" id="Password" value="" type="password" size="30" />
  					</p>
  					<p>
  						<label>Confirm Password</label>
  						<input name="Confirm_Password" id="Confirm_Password" value="" type="password" size="30" />
  					</p>
  					<p>
  						<input class="resetbutton" type="reset" value="Reset"/>
  						<input class="submitbutton" type="submit" value="Register"/>
  					</p>
  				</form>	
				<p><i>All fields are mandatory.</i></p>
				<p><i>Fields marked with * are mandatory for hostellers and fields marked with ** are mandatory for day scholars.</i></p> 
  			</fieldset>	
FORM;
//=============================================================================================================================================


//=============================================================================================================================================
//[ RENDER HTML CONTENT ]

$template=file_get_contents("../template");
$template=str_replace("{scripts}",$validation_script,$template);
$template=str_replace("{User-State}",$user_status,$template);
$template=isset($_COOKIE['username'])?str_replace("{Register}","",$template):str_replace("{Register}",$register,$template);
$template=isset($_COOKIE['username'])?str_replace("{Login}","",$template):str_replace("{Login}",$login,$template);
$template=str_replace("{Page-Content}",$page_content,$template);
echo $template;

//=============================================================================================================================================
?>
