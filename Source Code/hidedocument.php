#!/usr/bin/php
<?php 
	/*-------------------------PHP script for hide/show a document ----------------------------*/
	// Check access Permission
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "unauthorised access";
		return;
	}
	$_admission_id = $_GET['id'];
	$_document_id = $_POST['document_id'];
	$_action = $_POST['action'];
	$_query=$_GET['query'];
	$_type=$_GET['type'];
	if (!$link){$link=connectdb();}
	if($_action == 'hide'){
		$query = "UPDATE uploaded_documents SET hidden='t' WHERE document_id = $_document_id";
	} else if($_action == 'show'){
		$query = "UPDATE uploaded_documents SET hidden='f' WHERE document_id = $_document_id";
	}
	$result= pg_query($query)or die ('Error:'. pg_last_error());
	header("Location: ./viewapplication.php?id=$_admission_id&type=$_type&query=$_query");
?> 