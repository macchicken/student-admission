#!/usr/bin/php
<?php
	/* ---------------------------------Script for updating academic comment -----------------------*/
	$user = $_SERVER["REMOTE_USER"];
 	$_spvs_login = $_GET['sv'];
	$_admission_id = $_GET['id'];
	$_action = $_GET['action'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_recommendation= $_POST['recommendation'];
	$_adc_comment= $_POST['acdcomment'];
	$_accept_cond= $_POST['accept_cond'];
	$_other_spvs= $_POST['other_spvs'];
	if($user != $_spvs_login){
		echo "<div>Unauthorised access</div>";
		echo "<div>You can only edit your own comment</div>";
		return;
	}
	// Force user to choose an option
	if($_recommendation=='no_selection'){
		$_adc_comment = urlencode($_adc_comment);
		$_accept_cond = urlencode($_accept_cond);
		header("Location: ./editacdcomment.php?id=$_admission_id&sv=$_spvs_login&type=$_type&query=$_query&action=$_action&revisit=true&comment=$_adc_comment&cond=$_accept_cond&other=$_other_spvs");
	}
	else{

		header("Location: ./viewacdcomment.php?id=$_admission_id&us=$_spvs_login&action=edit&type=$_type&query=$_query");
		include "func/connect.php";
		if (!$link){$link=connectdb();}
		// If the academic hasn't make a comment, then add a new entry
		if($_action=='addnew'){
			// make the academic an potential supervisor if he choose accept and supervise
			if($_recommendation == 'accept_supervise'){
				$query= "INSERT INTO admission_supervisor 
					VALUES ('$_admission_id','$_spvs_login','potential','$_recommendation','$_adc_comment','$_accept_cond','$_other_spvs');";
			} else {
				$query= "INSERT INTO admission_supervisor 
					VALUES ('$_admission_id','$_spvs_login','academic','$_recommendation','$_adc_comment','$_accept_cond','$_other_spvs');";
			}
		} else {
			// If the academic has made a comment, then edit it
			$query_check_supervisor = "SELECT supervisor_flag FROM admission_supervisor 
										WHERE academic_login = '$_spvs_login' AND admission_id=$_admission_id";
			$result_check_supervisor = pg_query($query_check_supervisor);
			$_supervisor_flag=pg_fetch_array($result_check_supervisor,0);
			$query= "UPDATE admission_supervisor SET recommendation='$_recommendation' ,comment_and_justification='$_adc_comment', 
					acceptance_condition='$_accept_cond ', other_supervisor='$_other_spvs' 
					WHERE academic_login = '$_spvs_login' AND admission_id=$_admission_id;
					";
			if($_recommendation == 'accept_supervise' && $_supervisor_flag[0] != 'supervisor' ){
				$query .= "UPDATE admission_supervisor SET supervisor_flag ='potential' 
							WHERE academic_login = '$_spvs_login' AND admission_id=$_admission_id;";
			}
		}
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());	
	}
	
?>
