#!/usr/bin/php
<?php 
	/*--------------------------------Script for actually uploading a document --------------------------*/
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
	echo "unauthorised access";
	return;
	}
	// Folder the uploaded file gonna be stored in. Change this variable to change uploaded file's folder.
	$_TARGET_PATH_FOLD = "uploads/";
	$_admission_id=$_POST['admission_id'];
	$_query= $_GET['query'];
    $_type= $_GET['type'];
	$curr_time=date("d/m/y : H:i:s",time());
	$_error_code = 0;
	
	
	
	for($i=0; $i<3; $i++){
		//Get the document type
		$_doc_type_name = 'doc_type'.$i;
		$_doc_label_name = 'label'.$i;
		$_uploaded_file_name = 'uploadedfile'.$i;
		$_doc_type= $_POST[$_doc_type_name];
		$_label= $_POST[$_doc_label_name];
		// Pick the label display for the document. The type in box has higher priority
		if($_label != NULL){
			$_dis_label = $_label;
		} else {
			$_dis_label = $_doc_type;
		}
		// Get the unique document_id and add it to the uploaded file's name
		if($_FILES[$_uploaded_file_name]['tmp_name']){
			if (!$link){$link=connectdb();}
			$query_nextval = "SELECT nextval('document_id_sq')";
			$nextval_result = pg_query($query_nextval);
			$nextval=pg_fetch_array($nextval_result,0);
			
			$_target_path = $_TARGET_PATH_FOLD ."$nextval[0]".basename( $_FILES[$_uploaded_file_name]['name']);
			if(move_uploaded_file($_FILES[$_uploaded_file_name]['tmp_name'], $_target_path)) {
				$query="INSERT INTO uploaded_documents (document_id, document_type, admission_id, time_added, hidden, reference)
						VALUES('$nextval[0]','$_dis_label',$_admission_id,'$curr_time', 'f', '$_target_path');";
				$result=pg_query($query) or die('Query failed: ' . pg_last_error());;
				$_error_code += 2*pow(10,$i);
			} else {
				$_error_code += pow(10,$i);
			}
		}
	}
	header("Location: ./viewapplication.php?id=$_admission_id&type=$_type&query=$_query&ec=$_error_code");
?>