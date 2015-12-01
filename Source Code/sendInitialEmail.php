#!/usr/bin/php
<?php
	/*---------------Script for sending initial email --------------------------------------*/
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "unauthorised access";
		return;
	}
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
	$_sender = $_POST['sender'];
	$_receiver = $_POST['receiver'];	
	$_to = "$_receiver"."@doc.ic.ac.uk";
	$_subject = "PhD Application";
	$_headers = "From: phdadmissions@doc.ic.ac.uk\r\n" ;
	$_body = "
	Dear ".$_receiver.",\n
  The PhD application of ".$_forename." ".$_surname. " may be of interest to you. 
  Details can be found at https://www.doc.ic.ac.uk/project/2008/362/g0836218/phdadm/Nam
  Please respond by completing the form found at the URL bove. \n
  If you have questions or requests not catered for by that form, please pass them on to phdadmissions@doc.imperial.ac.uk or talk to me in person.\n
	Best wishes,\n
	Krysia Broda\n
	PhD Admissions Tutor
	";
	
	if (mail($_to, $_subject, $_body, $_headers)) {
		header("Location: spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename&st=sucess&to=$_receiver");
		if (!$link){$link=connectdb();}
		$query = "UPDATE admission_supervisor SET initial_email_send='t'
				WHERE admission_id=$_admission_id AND academic_login='$_receiver';";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
 	} else {
		header("Location: spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename&st=fail&to=$_receiver");
 	}
?>