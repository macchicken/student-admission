#!/usr/bin/php
<?php
	/*------------------------Script for update supervisor information -------------------------*/
	include "func/access.php";
	
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
	$_spvs_username = $_POST['spvsname'];
	$_status = 'supervisor';
	$_error_code = 0;
	// Check if input supervisor/poteintial supervisor is a valid academic
	include "func/inputcheck.php";
	if(isAcademic($_spvs_username)){
		if (!$link){$link=connectdb();}
		// Get status and all supervisor of the student
		$_query_check = "SELECT admission_supervisor.academic_login, admission_supervisor.supervisor_flag, phd_admissions.status 
							FROM admission_supervisor, phd_admissions
							WHERE admission_supervisor.admission_id = $_admission_id 
							AND admission_supervisor.admission_id = phd_admissions.admission_id;";
		$_check_result = pg_query($_query_check);
		$_check_row = pg_num_rows($_check_result);
		$_inserting = 'potential';
		if($_check_row>0){
			// If there is 1 or more academic/potential superviosr/supervisor
			$_supervisor_count = 0;
			$_academic_exist = false;
			$_is_supervisor = false;
			$_is_potential_supervisor = false;
			$_status_check = pg_fetch_array($_check_result,0);
			// If the student is accepted, then we adding supervisor into the system, otherwise, we add potential supervisor
			if($_status_check[2] == 'accepted'){
				$_inserting = 'supervisor';
			} 
			// Get statistic about supervissor/potential supervisor 
			for($i=0;$i<$_check_row;$i++){
				$arr = pg_fetch_array($_check_result,$i);
				if($arr[0] == $_spvs_username){
					if($arr[1] == 'supervisor'){
						$_academic_exist = true;
						$_is_supervisor = true;
						$_supervisor_count ++;
					} else if ($arr[1] == 'potential') {
						$_is_potential_supervisor = true;
						$_academic_exist = true;
					} else {
						$_academic_exist = true;
					}
				} else {
					if($arr[1] == 'supervisor'){
						$_supervisor_count ++;
					} 
				}
			}
			// if there is more than 1 supervisor , error occur if trying to add more
			if($_supervisor_count >= 2 && $_inserting == 'supervisor'){	
				$_error_code = 1;
			} else {
				// If add a same person, then give error
				if(($_is_supervisor && $_inserting == 'supervisor')||($_is_potential_supervisor && $_inserting == 'potential')){
					$_error_code = 2;
				} else {
					// If every input is ok, then update database and give error flag value 3 to indicate sucessful
					if($_academic_exist){
						$query = "UPDATE admission_supervisor SET supervisor_flag = '$_inserting'
									WHERE admission_id = $_admission_id AND academic_login='$_spvs_username';";
						$_error_code = 3;
					} else {
						$query = "INSERT INTO admission_supervisor (admission_id, academic_login, supervisor_flag) 
									VALUES ($_admission_id,'$_spvs_username','$_inserting');";
						$_error_code = 3;
					}
				}
			} 
	
		} else {// If no potential supervisor/ supervisor is found
			// Ad new entry to database as appropriate
			if($_query == 'accepted'){
				$_inserting = 'supervisor';
			} 
			$query = "INSERT INTO admission_supervisor (admission_id, academic_login, supervisor_flag) 
						VALUES ($_admission_id,'$_spvs_username','$_inserting');";
			$_error_code = 3;
		}
		if($_error_code == 3){
			$result = pg_query($query) or die('Can not add the supervisor: ' . pg_last_error());
		}
	} else {
		$_error_code = 4; // Indivate an invalid academic  login
	}
	header("Location: spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename&ec=$_error_code");
?>