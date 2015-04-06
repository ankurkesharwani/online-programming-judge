<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]

$todo=isset($_GET['todo'])?$_GET['todo']:"";
$challenge_id=isset($_POST['challenge_id'])?$_POST['challenge_id']:"";
$challenge_name=isset($_POST['challenge_name'])?$_POST['challenge_name']:"";
$challenge_difficulty=isset($_POST['challenge_difficulty'])?$_POST['challenge_difficulty']:"";	
$file_type=isset($_POST['file_type'])?$_POST['file_type']:"";	
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:0;			
$target_path1 = "/var/www/html/challenges/";
$target_path2 = "/var/www/html/challenges/sample_inputs/";
$target_path3 = "/var/www/html/challenges/sample_outputs/";
$target_path4 = "/var/www/html/submitted_solutions/";
$target_path5 = "/var/www/html/solutions/";
//=============================================================================================================================================

//=============================================================================================================================================
//[ ACTIONS ]

if((strcmp($todo,"add")==0)&&isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{
	require_once 'dbinfo.php';
	$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
	$query = "SELECT * FROM Challenges WHERE Challenge_ID=$challenge_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());
	$rows = mysql_num_rows($result);
	if($rows==0)
	{
		$query = "INSERT INTO Challenges VALUES" . "('$challenge_id','$challenge_name','$challenge_difficulty')";
		$result = mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());
		
		if ($_FILES["file"]["error"] > 0)
		{
   			echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
   		}
   		else
   		{
			$target_path = $target_path1 . $challenge_id . ".pdf"; 
			if(!move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) die ('<br>Problem Uploading Challenge File');
			
			$target_path = $target_path2 . $challenge_id . ".input1"; 
			if(!move_uploaded_file($_FILES['sample1']['tmp_name'], $target_path)) die ('<br>Problem Uploading Sample1 File');
			$target_path = $target_path2 . $challenge_id . ".input2";
			if(!move_uploaded_file($_FILES['sample2']['tmp_name'], $target_path)) die ('<br>Problem Uploading Sample2 File');
			$target_path = $target_path2 . $challenge_id . ".input3";
			if(!move_uploaded_file($_FILES['sample3']['tmp_name'], $target_path)) die ('<br>Problem Uploading Sample3 File');
			
			$target_path = $target_path3 . $challenge_id . ".output1";
			if(!move_uploaded_file($_FILES['output1']['tmp_name'], $target_path)) die ('<br>Problem Uploading Output1 File');
			$target_path = $target_path3 . $challenge_id . ".output2";
			if(!move_uploaded_file($_FILES['output2']['tmp_name'], $target_path)) die ('<br>Problem Uploading Output2 File');
			$target_path = $target_path3 . $challenge_id . ".output3";
			if(!move_uploaded_file($_FILES['output3']['tmp_name'], $target_path)) die ('<br>Problem Uploading Output3 File');				
		}
		
		header("Location: challenges.php");	
	}
	else
	{
		header("Location: challenges.php?status=challenge_already_exists");
	}
}
else if((strcmp($todo,"delete")==0)&&isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{
	require_once 'dbinfo.php';
	$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
	$query = "DELETE FROM Challenges WHERE Challenge_ID=$challenge_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());		
	
	
		
	if(file_exists($target_path1 . $challenge_id . ".pdf")) 
		unlink($target_path1 . "$challenge_id" . "pdf");
	if(file_exists($target_path2 . $challenge_id . ".input1")) 
		unlink($target_path2 . $challenge_id . ".input1");
	if(file_exists($target_path2 . $challenge_id . ".input2")) 
		unlink($target_path2 . $challenge_id . ".input2");
	if(file_exists($target_path3 . $challenge_id . ".input3")) 
		unlink($target_path3 . $challenge_id . ".input3");
		
	if(file_exists($target_path3 . $challenge_id . ".output1")) 
		unlink($target_path3 . $challenge_id . ".output1");
	if(file_exists($target_path3 . $challenge_id . ".output2")) 
		unlink($target_path3 . $challenge_id . ".output2");
	if(file_exists($target_path3 . $challenge_id . ".output3")) 
		unlink($target_path3 . $challenge_id . ".output3");
			
	header("Location: challenges.php");	
}
else if(strcmp($todo,"submit_solution")==0)
{
	if(isset($_COOKIE['username'])&&isset($_COOKIE['lib_id']))
	{
		require_once 'dbinfo.php';
		$db_server = mysql_connect($db_hostname, $db_usrname, $db_passwd);
		if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
		mysql_select_db($db_database)or die("Unable to select database: " . mysql_error());
		$query = "SELECT Challenge_Name FROM Challenges WHERE Challenge_ID=$challenge_id";	
		$result = mysql_query($query);
		if (!$result) die ("Database access failed: " . mysql_error());
		$rows = mysql_num_rows($result);
		if($rows==1)
		{
			$row = mysql_fetch_row($result);
			$challenge_name=$row[0];
			if(!(file_exists($target_path5 . $challenge_id . "_" . $lib_id . ".c")||file_exists($target_path5 . $challenge_id . "_" . $lib_id . ".cpp")))
			{
				if ($_FILES["file"]["error"] > 0)
				{
    				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    			}
    			else
    			{
    				$file_type=strcmp($file_type,"C File")==0?"c":"cpp";
					
					
					if(!move_uploaded_file($_FILES['file']['tmp_name'],$target_path4 . $challenge_id . "_" . $lib_id . "." . $file_type))
						$status="submission_failed";
					else
					{
						if(file_exists($target_path4 . $challenge_id . "_" . $lib_id . "." . $file_type))
						{
							include_once("execute.php");
							$response_code=execute($challenge_id . "_" . $lib_id,$file_type,$challenge_id);
							
							if($response_code==4)
							{
								$status="compile_error";
							}
							else if($response_code==3)
							{
								$status="execution_error";
							}
							else if($response_code==2)
							{
								$status="incorrect_solution";
							}
							else if($response_code==1)
							{
								$status="correct_solution";
								rename($target_path4 . $challenge_id . "_" . $lib_id . "." . $file_type,$target_path5 . $challenge_id . "_" . $lib_id . "." . $file_type);
								
								if(file_exists($target_path5 . $challenge_id . "_" . $lib_id . "." . $file_type))
								{

									$query = "INSERT INTO Solutions VALUES" . "('$challenge_id','$challenge_name','$username','$challenge_id" . "_" . "$lib_id.$file_type','$lib_id')";
									$result = mysql_query($query);
									if (!$result) die ("Database access failed: " . mysql_error());	
									$query = "SELECT Score FROM Users WHERE Lib_ID=$lib_id";
									$result = mysql_query($query);
									if (!$result) die ("Database access failed: " . mysql_error());	
									$rows = mysql_num_rows($result);
									$row=mysql_fetch_row($result);
									$score=$row[0];

									$query = "SELECT Difficulty FROM Challenges WHERE Challenge_ID=$challenge_id";
									$result = mysql_query($query);
									if (!$result) die ("Database access failed: " . mysql_error());	
									$rows = mysql_num_rows($result);
									$row=mysql_fetch_row($result);	
									$diff=$row[0];

									if($diff==3)
										$score=$score+6;
									else if($diff==2)
										$score=$score+4;
									else if($diff==1)
										$score=$score+2;

									$query = "UPDATE Users SET Score='$score' WHERE Lib_ID='$lib_id'";
									$result = mysql_query($query);
									if (!$result) die ("Database access failed: " . mysql_error());								
									$status="submission_successfull";	
								}
								else
								{
									$status="submission_failed";
								}
							}						
						}
					}
				}					
			}	
			else
			{
				$status="alredy_submitted";
								
			}			
		}
		else
		{
			$status="incorrect_challenge_id";
		}
	}
	header("Location: challenges.php?page_request=submit_solution&status=$status");
}
//=============================================================================================================================================
?>
