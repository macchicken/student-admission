#!/usr/bin/php
<?php 
	/* ---------------- Script for updating the administrator comment -----------------------*/
	include "func/access.php";
	// Checking administrator status
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access";
		return;
	}
	if (!$link){$link=connectdb();}
	$_admission_id=$_GET['id'];
	$user=$_SERVER["REMOTE_USER"];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
	$_query=$_GET['query'];
	$_type=$_GET['type'];
	$_new_comment = $_POST['newcomment'];
	$_new_tick_box = $_POST['new_tick_box'];
	$_box_count = $_POST['box_count'];
	$_current_time = time();
	$_time = date("d/m/y : H:i:s",time());
	$query = '';
	header("Location: allcomment.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename");
	// Check if a dealt with box is ticked
	if (isset($_new_tick_box)){
		$_tick = 't';
	} else {$_tick = 'f';}
	
	for($i = 0; $i<$_box_count;$i++){
		// If a box is ticked, then add an Update operator to the query to update the comment as dealt with
		$_box_name = 'box'.$i;
		if(isset($_POST[$_box_name])){
			$_comment_no_id = ''.$i;
			$_comment_no = $_POST[$_comment_no_id];
			$query .= "UPDATE admin_comment SET dealt_with='t' WHERE admin_comment_id=$_comment_no;";
		}	
	}
	// If a new comment is amde, then add a query to insert it into the database
	if ($_new_comment != NULL){
		$query .= "INSERT INTO admin_comment (sender,comment,date_added,dealt_with,admin_comment_id,admission_id ) 
		VALUES ('$user','$_new_comment',  '$_time', '$_tick', nextval('admin_comment_sq'),$_admission_id);";
	}
	echo $query;
	if ($query != NULL){
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
	}
?>