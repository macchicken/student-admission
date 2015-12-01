#!/usr/bin/php

<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php 
	//Checking if user is administrator
	include "func/access.php";
	$admin=check_admin();
	$user = $_SERVER["REMOTE_USER"];
	// Getting variable from URL
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>All Academic Comment</title>
    </head>
    
    <body class = "LightGoldenRodYellow" >
    <P class ="centre_blue">All comments</p><BR><HR></hr></br>
    	<!-- //////////////////////////////// Display The Student Basic Info //////////////////////////////////////// -->
        <?php
			// Connection to database
            if (!$link){$link=connectdb();}
            
            $query= "SELECT research_subject, possible_funding, origin , admin_tutor_comment
						FROM phd_admissions WHERE admission_id = $_admission_id";
            $result= pg_query($query);
            $arr=pg_fetch_array($result,0);
			// Extract data from the query result
            $_research_subject= $arr[0];
            $_funding= $arr[1];
            $_origin= $arr[2];
			$_admin_tutor_comment = $arr[3];
        ?>
        <!-- Display data in a table -->
        <table border="1" align="center">
            <tr>
                <td><b>Research Subject</b></td>
                <td><b>Funding</b></td>
                <td><b>Origin</b></td>
                <td><b>Documents</b></td>
            </tr>
            <tr>
                <td><?php echo $_research_subject; ?></td>
                <td><?php echo $_funding; ?></td>
                <td><?php echo $_origin; ?></td>
            </tr>
         </table>
         <HR/><BR />
         <?php 
		 	if($_admin_tutor_comment != NULL){ 
				echo "<b>PhP Administrator Comment: </b>".$_admin_tutor_comment."<BR><BR><HR>";
			}
		 ?>
        
         <!-- //////////////////////////////////////////////////////////////////////////////////////////////////////// -->
         <!-- /////////////////////////Display all comment from academic and supervisors ///////////////////////////// -->
         <div align="center"><b>Academic's Comment</b></div><BR />
         <?php
            $query= "SELECT academic_login, recommendation, comment_and_justification, acceptance_condition, other_supervisor 
								FROM admission_supervisor WHERE admission_id=$_admission_id ;";
            $result= pg_query($query);
            $rows= pg_num_rows($result);
			$_made_comment = false; // variable to check if the user has already made a comment on the profile
            if($rows>0){
                for($i=0; $i<$rows; $i++){
                    $arr = pg_fetch_array($result,$i);
                    $_spvs_login = $arr[0];
                    $_recommendation= $arr[1];
                    $_adc_comment= $arr[2];
                    $_accept_cond= $arr[3];
                    $_other_spvs= $arr[4];
                    
					if($user==$_spvs_login){
						$_made_comment = true;
					}
					
					$_ouput_display = "";
					// Change display of the recommendation
					if($_recommendation != NULL){
						switch($_recommendation){
							case 'accept_supervise' ; $_dis_recommendation = "Accepted and Supervise"; break;
							case 'accept_not_supervise' ; $_dis_recommendation = "Accepted But Not Supervise"; break;
							case 'msc' ; $_dis_recommendation = "Recommend for Msc"; break;
							case 'wait' ; $_dis_recommendation = "Wait to be interviewed"; break;
							case 'rejected' ; $_dis_recommendation = "Rejected"; break;
						}
						$_ouput_display .=  "<tr><td><B>Recommendation</b>: </td>
									<td> $_dis_recommendation</td></tr>
						";
					}
					if($_adc_comment != NULL){
						$_ouput_display .=  "<tr><td><B>Comments & Justification</b>: </td>
									<td> $_adc_comment</td></tr>
						";
					}
					if($_accept_cond != NULL){
						$_ouput_display .=  "<tr><td><B>Acceptance Condition</b>: </td>
									<td> $_accept_cond</td></tr>
						";
					}
					if($_other_spvs != NULL){
						$_ouput_display .=  "<tr><td><B>Other Possible Supervisors</b>: </td>
									<td> $_other_spvs</td></tr>
						";
					}

					if($_ouput_display != ""){
						echo "<div><b>From</b><font color='red'><i> $_spvs_login</i> </font>: $dis_recommendation ";
						//Let an user edit their own comment
						if($user==$_spvs_login){
                			echo "<a href='editacdcomment.php?id=$_admission_id&sv=$_spvs_login&type=$_type&query=$_query'>Edit</a>";
            			}
						echo "<table>".$_ouput_display."</table><BR>";
					}
                }
            }
    		if($_made_comment == false){
				echo "<a href='editacdcomment.php?id=$_admission_id&sv=$user&type=$_type&query=$_query'>Add New Comment</a>";
			}
         ?>
         <HR />
         <div align="right">
            <?php
				// Bread Crum
				if($admin){
					echo "<a href='./main.php'>PhD Admission Portal</a>>";
				}
			?>
            <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
            ><b><i>All comments</i></b>
         </div>
    </body>
</html>