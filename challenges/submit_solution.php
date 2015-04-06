<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
$challenge_id=isset($_POST['challenge_id'])?$_POST['challenge_id']:"";	
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:"";
$file_type=isset($_POST['file_type'])?$_POST['file_type']:"";
$target_path1 = "Problem_Statement/";
$target_path2 = "Sample_Inputs/";
$target_path3 = "Sample_Outputs/";
$target_path4 = "Submitted_Solutions/";
$target_path5 = "Solutions/";

//=============================================================================================================================================
if(isset($_COOKIE['username'])&&isset($_COOKIE['lib_id']))
{
	require_once '../dbinfo/dbconnect.php';
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
						header_check($target_path4 , $challenge_id . "_" . $lib_id . "." . $file_type,$file_type);
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
								
								$query = "INSERT INTO Solutions VALUES" . "('$challenge_id','$lib_id','$challenge_id" . "_" . "$lib_id.$file_type')";
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
header("Location: submit.php?status=$status");
?>
