<?php
function escape($str){
	global $con;
	if (get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	$str = mysqli_real_escape_string($con,$str);
	return $str;
}

?>