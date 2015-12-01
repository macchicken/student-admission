#!/usr/bin/php
<?php
	/*--------------------Script for removing a supervisor --------------------*/
	include "func/access.php";
	// Administrator status check
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access. You don't have permission to access this page.";
		return;
	}
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
	$_supervisor=$_POST['supervisor'];
	if($_query == 'accepted'){
		if (!$link){$link=connectdb();}
		$query = "UPDATE admission_supervisor SET supervisor_flag='academic'
				WHERE admission_id=$_admission_id AND academic_login='$_supervisor';";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	}
	header("Location: spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename");
?>