<?php
//=============================================================================================================================================
//[ VARIABLE DECLARATIONS ]
$challenge_id=isset($_POST['challenge_id'])?$_POST['challenge_id']:"";	
$target_path1 = "Problem_Statement/";
$target_path2 = "Sample_Inputs/";
$target_path3 = "Sample_Outputs/";
//=============================================================================================================================================

if(isset($_COOKIE['isadmin'])&&(strcmp($_COOKIE['isadmin'],md5(md5("true")))==0))
{
	require_once '../dbinfo/dbconnect.php';
	$query = "DELETE FROM Challenges WHERE Challenge_ID=$challenge_id";
	$result = mysql_query($query);
	if (!$result) die ("Database access failed: " . mysql_error());		
		
	if(file_exists($target_path1 . $challenge_id . ".pdf")) 
		unlink($target_path1 . "$challenge_id" . "pdf");
	if(file_exists($target_path2 . $challenge_id . ".input1")) 
		unlink($target_path2 . $challenge_id . ".input1");
	if(file_exists($target_path2 . $challenge_id . ".input2")) 
		unlink($target_path2 . $challenge_id . ".input2");
	if(file_exists($target_path2 . $challenge_id . ".input3")) 
		unlink($target_path3 . $challenge_id . ".input3");
		
	if(file_exists($target_path3 . $challenge_id . ".output1")) 
		unlink($target_path3 . $challenge_id . ".output1");
	if(file_exists($target_path3 . $challenge_id . ".output2")) 
		unlink($target_path3 . $challenge_id . ".output2");
	if(file_exists($target_path3 . $challenge_id . ".output3")) 
		unlink($target_path3 . $challenge_id . ".output3");
}
header("Location: ../challenges/");
?>
