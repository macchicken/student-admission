#!/usr/bin/php
<?php
	/*---------------------------Update the research admin information (Rersearch subject, admin comment)-----*/
	$user=$_SERVER["REMOTE_USER"];
	$_admission_id = $_GET['id'];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_research_subject = $_POST['research_subject'];
	$_dis_rs_subject = ucfirst($_research_subject);
	$_tutor_comment = $_POST['comment'];
	header("Location: spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename");
	// Check administrator status
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access";
		return;
	}
	// Update the database
	if (!$link){$link=connectdb();}
	$query = "UPDATE phd_admissions SET research_subject='$_dis_rs_subject', admin_tutor_comment='$_tutor_comment' 
				WHERE admission_id=$_admission_id ;";
	$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	
?>
