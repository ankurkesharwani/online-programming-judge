<?php
	function exec_enabled(){
		$disabled=explode(',',ini_get('disable_functions'));
		echo $disabled;
		return !in_array('exec',$disabled);
	}

	echo exec_enabled();
?>
