<?php
	
function security_check($path,$fname,$filetype)
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
	$return_code=security_check("Solutions/","2_10853.c","c");
?> 
