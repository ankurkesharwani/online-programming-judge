<?php
define('READ_LEN', 4096);

function files_identical($fn1, $fn2) {
    //if(filetype($fn1) !== filetype($fn2))
    //    return FALSE;

    //if(filesize($fn1) !== filesize($fn2))
    //    return FALSE;

    if(!$fp1 = fopen($fn1, 'rb'))
        return FALSE;

    if(!$fp2 = fopen($fn2, 'rb')) {
        fclose($fp1);
        return FALSE;
    }

    $same = TRUE;
    while (!feof($fp1) and !feof($fp2))
        if(strcmp(trim(fread($fp1, READ_LEN)),trim(fread($fp2, READ_LEN)))!=0) {
            $same = FALSE;
            break;
        }

    if(feof($fp1) !== feof($fp2))
        $same = FALSE;

    fclose($fp1);
    fclose($fp2);

    return $same;
}
function header_check($path,$fname,$filetype)
{
	$line="";
	$line1="";
	$file = fopen($path . $fname, "r") or exit("Unable to open file for security check!");
	$o_file=fopen($path . $fname . "_sc", "w") or exit("Unable to open output file for security check!");
	if(strcmp($filetype,"c")==0)
	{
		fwrite($o_file,"#include<stdio.h>\n");
		fwrite($o_file,"#include<math.h>\n");
		fwrite($o_file,"#include<string.h>\n");	
	}
	else if(strcmp($filetype,"c++")==0)	
	{
		fwrite($o_file,"#include<cstdio\n");
		fwrite($o_file,"#include<cmath>\n");
		fwrite($o_file,"#include<cstring>\n");	
	}
	while(!feof($file))
	{
		$line=fgets($file);
		$line1=strtolower($line);
		if(strcmp(strstr($line1,"#include"),"#include")<0) //if a line contains #include
		{
			//echo $line . "<br>";
			fwrite($o_file,$line);
		}
	}
	fclose($file);
	fclose($o_file);
	unlink($path . $fname);
	rename($path . $fname . "_sc", $path . $fname);
	return 0;
}


function execute($file_name,$file_type,$challenge_id)
{
	$response=null;
	$returnvalue=0;
	$status=0;
	if(strcmp($file_type,"")!=0&&strcmp($file_name,"")!=0)
	{
		if(strcmp($file_type,"c")==0)
		{
			exec("gcc Submitted_Solutions/$file_name.c -o Compiled_Solutions/$file_name.out",$response,$returnvalue);
		}
		else if(strcmp($file_type,"cpp")==0)
		{
			exec("g++ Submitted_Solutions/$file_name.cpp -o Comipled_Solutions/$file_name.out",$response,$returnvalue);
		}
		
	}
	if($returnvalue==0)
	{
		exec("./execute.sh $file_name $challenge_id",$response,$returnvalue);
		if($returnvalue==1)
		{
			$flag=false;
			
			$flag=files_identical("Sample_Outputs/$challenge_id.output1", "Temp/$file_name.output1");
			$flag=$flag==true?files_identical("Sample_Outputs/$challenge_id.output2", "Temp/$file_name.output2"):false;
			$flag=$flag==true?files_identical("Sample_Outputs/$challenge_id.output3", "Temp/$file_name.output3"):false;
			

			if($flag==true)
				$status=1;
			else
				$status=2;
		}
		else
		{
			$status=3;
		}
	}
	else
	{
		$status=4;
	}	
		
	if(file_exists("Temp/$file_name.output1")) 
		unlink("Temp/$file_name.output1");
	if(file_exists("Temp/$file_name.output2")) 
		unlink("Temp/$file_name.output2");
	if(file_exists("Temp/$file_name.output3")) 
		unlink("Temp/$file_name.output3");
	if(file_exists("Compiled_Solutions/$file_name.out")) 
		unlink("Compiled_Solutions/$file_name.out");
		
	return $status;	
}	
?>
