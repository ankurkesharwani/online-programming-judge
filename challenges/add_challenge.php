<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]

$challenge_id=isset($_POST['challenge_id'])?$_POST['challenge_id']:"";
$challenge_name=isset($_POST['challenge_name'])?$_POST['challenge_name']:"";
$challenge_difficulty=isset($_POST['challenge_difficulty'])?$_POST['challenge_difficulty']:"";	
$file_type=isset($_POST['file_type'])?$_POST['file_type']:"";	
$username=isset($_COOKIE['username'])?$_COOKIE['username']:"Guest";
$lib_id=isset($_COOKIE['lib_id'])?$_COOKIE['lib_id']:0;			
$target_path1 = "Problem_Statement/";
$target_path2 = "Sample_Inputs/";
$target_path3 = "Sample_Outputs/";
$target_path4 = "Submitted_Solutions/";
$target_path5 = "Solutions/";
//=============================================================================================================================================
if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{
	require_once '../dbinfo/dbconnect.php';
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
			//echo $target_path;
			//die;
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
		
		header("Location: ../challenges/");	
	}
	else
	{
		header("Location: ../challenges/?status=challenge_already_exists");
	}
}
else
	header("Location: ../challenges/");	

?>
