#!/usr/bin/php
<?php
	/* Script for saving a student details into the database */
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access";
		return;
	}
	
	$_admission_id= $_POST['admission_id']; 
	$_query = $_GET['query'];
	$_type = $_GET['type'];
	$_regno = $_POST['registry'];
	$_sur_name = $_POST['surname'];
	$_fore_name = $_POST['forename'];
	$_funding = $_POST['funding'];
	$_origin = $_POST['origin'];
	$_status = $_POST['status'];
	$_action = $_POST['action'];
	$_funding_note = $_POST['funding_note'];
	$_origin_note = $_POST['origin_note'];
	$_status_note = $_POST['status_note'];
	$_select_supervisor = $_POST['potential_supervisor'];
	$_supervisor = $_POST['supervisor'];
	$_current_time = time();
	$_time = date("d/m/y : H:i:s",time());
	$_error_code = 0;
	$_error = false;
	
	// Thw supervisor that will be add is choose from list of potential or type in box
	if($_select_supervisor != 'select' && $_select_supervisor != NULL){
		// If there is one choose from the box, then add 
		$_adding_supervisor = $_select_supervisor;
	} else {
		// If nothing is choosen,then add the typed in box
		$_adding_supervisor = $_supervisor;
	}
	
	include "func/inputcheck.php";
	// Check if registry number is either null or a numerical term
	if($_regno != NULL){
		if(!isNumerical($_regno)){
			$_error_code += 1;  // if error code = .....1 then regno is not numerical
			$_error = true;
		}
	} else {
		$_regno = 1;
	}
	// Check to make sure surname must be present
	if($_sur_name == NULL) {
		$_error_code += 10; // if error code = ....1. then surname is missing
		$_error = true;
	} else { 
		// If surname present, then it must be an alphabetical term
		if(!isAlphabetical($_sur_name)){
			$_error_code += 100; // if error code = ...1.. then surname is not an alphabet term
			$_error = true;
		}
	}
	// Check if forenames present, then it must be an alphabetical term
	if($_fore_name != NULL & !isAlphabetical($_fore_name)){
		$_error_code += 1000; // if error code = ..1... then forename is not an alphabet term
		$_error = true;
	}
	/* Check if the student is accepted.
		In case the student is accepted, but no supervisor found for the student, then error and let the user input at least one supervisor.
	*/
	if($_status == 'accepted' && $_adding_supervisor == NULL){
		if($_action =='edit'){
			if (!$link){$link=connectdb();}	
			$query = "SELECT COUNT(academic_login) FROM admission_supervisor WHERE admission_id=$_admission_id AND supervisor_flag='supervisor' ;";
			$result = pg_query($query);
			$arr = pg_fetch_array($result);
			if($arr[0] == 0){
				$_error_code += 10000; // if error code = .1.... then student is accepted, but no supervisor found
				$_error = true;
			}
		} else {
			$_error_code += 10000; // if error code = .1.... then student is accepted, but no supervisor found
			$_error = true;
		}
	} else {
		if (!$link){$link=connectdb();}	
	}
	if($_status == 'accepted' &&$_adding_supervisor &&!isAcademic($_adding_supervisor)){
		$_error_code += 100000; // Invalid supervisor
		$_error = true;
	}
	// If there is an error, then come back to editapp.php page to warn user, otherwise, update the database
	if($_error){
		header("Location: ./editapp.php?id=$_admission_id&type=$_type&query=$_query&re=$_regno&su=$_sur_name&fo=$_fore_name&fu=$_funding&or=$_origin&st=$_status&fn=$_funding_note&sn=$_status_note&on=$_origin_note&ac=$_action&ec=$_error_code&revisit=true");
	} else {
		$query = "";
		
		if ($_action =='edit') {
			if($_status == 'accepted' && $_adding_supervisor != NULL){
				$_query_check = "SELECT academic_login, supervisor_flag FROM admission_supervisor
									WHERE admission_id = $_admission_id AND academic_login='$_adding_supervisor';";
				$_check_result = pg_query($_query_check);
				$_check_row = pg_num_rows($_check_result);
				if($_check_row>0){
					$query .= "UPDATE admission_supervisor SET supervisor_flag='supervisor' 
								WHERE admission_id = $_admission_id AND  academic_login='$_adding_supervisor';";
				} else {
					$query .= "INSERT INTO admission_supervisor (admission_id, academic_login, supervisor_flag) 
								VALUES ($_admission_id,'$_adding_supervisor','supervisor');";
				}
			}
			header("Location: ./viewapplication.php?id=$_admission_id&type=$_type&query=$_query");
			$query .= "UPDATE phd_admissions SET registry=$_regno, surname ='$_sur_name', forenames ='$_fore_name', possible_funding='$_funding'
					, origin='$_origin', status='$_status', funding_note= '$_funding_note', origin_note= '$_origin_note',
					status_note = '$_status_note', time_modified = '$_time' WHERE admission_id=$_admission_id;";
			$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		}
		else {
			
			$query_nextval = "SELECT nextval('admission_id')";
			$nextval_result = pg_query($query_nextval);
			$nextval=pg_fetch_array($nextval_result,0);
			header("Location: ./viewapplication.php?id=$nextval[0]&type=status&query=$_status");
			
			$query .= "INSERT INTO phd_admissions (admission_id, registry, surname, forenames,possible_funding, origin , status, funding_note, origin_note, status_note, time_added) 
					VALUES('$nextval[0]','$_regno','$_sur_name','$_fore_name','$_funding','$_origin',
					'$_status','$_funding_note','$_origin_note','$_status_note','$_time');";
			if ($_adding_supervisor != NULL && $_status == 'accepted'){
				$query .= "INSERT INTO admission_supervisor (admission_id, academic_login, supervisor_flag) 
					VALUES ('$nextval[0]','$_adding_supervisor','supervisor');";
			}
			$result = pg_query($query) or die('Query failed: ' . pg_last_error());
			
		}
	}
?>
