#!/usr/bin/php
<!-- ---------------------------------- Academic viewing page ----------------------------------------------------------- -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php
	include "func/access.php";
	$admin=check_admin();
	$user = $_SERVER["REMOTE_USER"];
	$_spvs_login = $_GET['us'];
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	$_bt= $_GET['bt'];
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>View Academic Comment</title>
        <P class = "centre_blue"><?php echo "Comments from "."<i>$_spvs_login</i>";?></p><BR /><HR /><BR />
    </head>

    <body class = "LightGoldenRodYellow" >
    <?php 
		// Get the comment from the database with specific sudent id and academic login
        if (!$link){$link=connectdb();}
        $query= "SELECT supervisor_flag, recommendation, comment_and_justification, acceptance_condition, other_supervisor
					FROM admission_supervisor WHERE academic_login='$_spvs_login' AND admission_id=$_admission_id ;";
        $result= pg_query($query);
        $rows= pg_num_rows($result);
        if($rows>0){
            $arr=pg_fetch_array($result,0);
            $_recommendation= $arr[1];
            $_adc_comment= $arr[2];
            $_accept_cond= $arr[3];
            $_other_spvs= $arr[4];
			$_change_made = false;
    
            echo "<table border='0'>";
            // Change recommendation display
            if($_recommendation != NULL){
				$_change_made = true;
                switch($_recommendation){
                    case 'accept_supervise' ; $_dis_recommendation = "Accepted and Supervise"; break;
                    case 'accept_not_supervise' ; $_dis_recommendation = "Accepted But Not Supervise"; break;
                    case 'msc' ; $_dis_recommendation = "Recommend for Msc"; break;
                    case 'wait' ; $_dis_recommendation = "Wait to be interviewed"; break;
                    case 'rejected' ; $_dis_recommendation = "Rejected"; break;
                }
                echo "<tr><td><B>Recommendation</b>: </td>
                            <td> $_dis_recommendation</td></tr>
                ";
            }
			// Only display details if they are not null
            if($_adc_comment != NULL){
				$_change_made = true;
                echo "<tr><td><B>Comments & Justification</b>: </td>
                            <td> $_adc_comment</td></tr>
                ";
            }
            if($_accept_cond != NULL){
				$_change_made = true;
                echo "<tr><td><B>Acceptance Condition</b>: </td>
                            <td> $_accept_cond</td></tr>
                ";
            }
            if($_other_spvs != NULL){
				$_change_made = true;
                echo "<tr><td><B>Other Supervisors</b>: </td>
                            <td> $_other_spvs</td></tr>
                ";
            }
            echo "</table>";
			if($_change_made == false){ 
				echo "<div align='middle'>There is no recommendation or comment added yet</div>";
			}
			// Let user edit comment only if the loggin match
            if($user==$_spvs_login){
				if($_change_made == true){
                	echo "<a href='editacdcomment.php?id=$_admission_id&sv=$_spvs_login&type=$_type&query=$_query'>Edit Details</a>";
				} else {
					echo "<a href='editacdcomment.php?id=$_admission_id&sv=$_spvs_login&type=$_type&query=$_query'>Add Comment</a>";
				}
            }
                
        } else {
            if($user==$_spvs_login){
               	echo "<a href='editacdcomment.php?id=$_admission_id&sv=$_spvs_login&action=addnew&type=$_type&query=$_query'>Add Comment</a>";
           	}
        }
    ?>
    <HR />
    <!-- //////////////////////////////////////////////////////////// Bread Crum //////////////////////////////////////////////////////////-->
    <?php
		// Bread crum
        if($_bt!=NULL){
            $_surname= $_GET['surname'];
            $_forename= $_GET['forename'];
            echo "<div align='right'>";
			if($admin){
					echo "<a href='./main.php'>PhD Admission Portal</a>>";
			}
            echo "<a href='./spvsinfo.php?id=$_admission_id'>$_forename $_surname Research Info</a>
                ><b><i>Supervisor Comment</i></b>
                </div>
            ";
        } else {
            echo "<div align='right'>"; 
			if($admin){
					echo "<a href='./main.php'>PhD Admission Portal</a>>";
			}
            echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>
                ><b><i>Comment</i></b>
                </div>
            ";
        }
    ?>
    
    </body>
</html>
