<?php

include "connect.php";
function check_admin(){
//return true;
	$_user=$_SERVER['REMOTE_USER'];
	if ($_user=="kb"||$_user=="aoeaoe"||$_user=="ashok") return true;
	else return false; 
	
}
?>