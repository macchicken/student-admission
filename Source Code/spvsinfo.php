#!/usr/bin/php
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--///////////////////////////////////////// Check access permission /////////////////////////////////////////////////// -->
<?php 
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access. You don't have permission to access this page.";
		return;
	}
?>
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
		$user=$_SERVER["REMOTE_USER"];
        $_admission_id = $_GET['id'];
        $_query= $_GET['query'];
        $_type= $_GET['type'];
        $_surname=$_GET['su'];
        $_forename=$_GET['fo'];
		$_email_send_report=$_GET['st'];
    	$_to = $_GET['to'];
		$_error_code = $_GET['ec']; // Add supervisor status
        if (!$link){$link=connectdb();}
          
    ?>
    
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Supervisor Info</title>
    </head>
    
    <body class = "LightGoldenRodYellow" >
        <div align="center"> 
        <form <?php echo "action='updatespvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'"; ?> method="post">
            <input name="spvsname" type="text" id="spvsname">
            <?php 
			if($_query == 'accepted'){
				echo "<input type='submit' value='Add Supervisor'>";
			} else {
				echo "<input type='submit' value='Add Potential Supervisor'>";
			}
			?>
        </form>
        </div>
         <?php 
		
			// Display error message
			if($_error_code == 1){
				$_error_msg = "<img border='0' src='./icon/cross.gif'><font size='2' face='verdana' color='red'>Each student can only have maximum 2 supervisor.  </font>";
			} 
			if($_error_code == 2){
				$_error_msg = "<img border='0' src='./icon/cross.gif'><font size='2' face='verdana' color='red'>You had already added that academic as a supervisor.  </font>";
			} 
			if($_error_code == 3){
				$_error_msg = "<img border='0' src='./icon/tick.gif'><font size='2' face='verdana' color='red'>Supervisor successfully added. </font>";
			} 
			if($_error_code == 4){
				$_error_msg = "<img border='0' src='./icon/cross.gif'><font size='2' face='verdana' color='red'>Invalid academic login. </font>";
			} 
			if($_error_msg != NULL){
				echo "<div align='middle'>".$_error_msg."</div>";
			}
			
		?>	
        <BR /><div align="center"><B>Supervisor List</b></div>
        
        <?php
			if($_query == 'accepted'){
				$_supervisor_flag = 'supervisor';
			} else {
				$_supervisor_flag = 'potential';
			}
            $query="SELECT academic_login, recommendation, acceptance_condition, initial_email_send, comment_and_justification
							FROM admission_supervisor WHERE admission_id=$_admission_id AND supervisor_flag='$_supervisor_flag';";
            $result= pg_query($query);
            $rows= pg_num_rows($result);
            if($rows>0){
                echo"
                <table border='1' align='center'>
                    <tr>
                        <td>Supervisor</td>
                        <td>Recommendation</td>
                        <td>Conditions</td>            
                        <td>Comment & Justification</td>";
				if($_supervisor_flag != 'supervisor'){
					echo "<td>Initial Email</td>";
				} 
				echo  "</tr>";
				
                    
                for( $x=0 ; $x < $rows ; $x ++){
                    $arr=pg_fetch_array($result,$x);
                    $_supervisor=$arr[0];
                    $_recommendation=$arr[1];
                    $_accept_condition = $arr[2];
					$_initial_email_send = $arr[3];
					$_comment_and_justification = $arr[4];
                    switch($_recommendation){
                        case 'not_view' ; $_dis_recommendation = "Not View"; break;
                        case 'accepted_supervise' ; $_dis_recommendation = "Accepted For Supervise"; break;
                        case 'accepted_not_supervise' ; $_dis_recommendation = "Accepted But Not Supervise"; break;
                        case 'rejected' ; $_dis_recommendation = "Rejected To Msc"; break;
                        case 'wait' ; $_dis_recommendation = "Wait To Interview"; break;
                    }
                    echo "
                        <tr>
                            <td>$_supervisor</td>
                        ";
                    if($_dis_recommendation!=NULL){
                        echo "<td>$_dis_recommendation</td>";
                    } else echo "<td>&nbsp</td>";
                    if($_accept_condition!=NULL){
                        echo "<td>$_accept_condition</td>";
                    } else echo "<td>&nbsp</td>";
					if($_comment_and_justification != NULL){
                    	echo "<td><a href='viewacdcomment.php?id=$_admission_id&us=$_supervisor&bt=spvs&surname=$_surname&forename=$_forename'>Link</a></td>";
					} else {
						echo "<td>No Comment Posted Yet ...</td>";
					}
					if($_supervisor_flag == 'supervisor'){
						echo "<td><form action='removesupervisor.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename' method='post'>
								<input type='hidden' name='supervisor' value='$_supervisor'>
								<input type='submit' value='Remove'>
							</form></td>
						";
					} else {
						if($_initial_email_send=='t'){
							echo "<td align='center'><img border='0' src='./icon/tick.gif'></td>";
						} else {
							echo "<td align='center'>
								  <form action='sendInitialEmail.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename' method='post'>
									<input type='hidden' name='receiver' value='$_supervisor'>
									<input type='hidden' name='sender' value='$user'>
									<input type='submit' value='Send'>
								  </form></td>
							";
						}
					}
					
                    echo "</tr>";
                }
                echo "</table>";
            }
			if($_email_send_report == 'sucess'){ 
				$_sucess_msg = " An initial email has been sucessfully sent to ".$_to;
				echo "<div align='middle'><img border='0' src='./icon/tick.gif'><font size='2' face='verdana' color='red'>$_sucess_msg</font></div>
				";
			} else if ($_email_send_report == 'fail'){
				$_fail_msg = " Fail to send an iniatial email to ".$_to.". Please try again later." ;
				echo "<div align='middle'><img border='0' src='./icon/cross.gif'><font size='2' face='verdana' color='red'>$_fail_msg</font></div>
				";
			}
        ?>
        <BR /><HR></hr><BR></br>
        <?php 
            $query_adm_info= "SELECT research_subject, admin_tutor_comment 
								FROM phd_admissions WHERE admission_id=$_admission_id ";
            $result= pg_query($query_adm_info);
            $rows= pg_num_rows($result);
            if($rows>0){
                $arr=pg_fetch_array($result,0);
                $_research_subject=$arr[0];
                $_tutor_comment=$arr[1];
            
            }
            if ($_research_subject == NULL & $_tutor_comment== NULL) {
                echo "No research subject and tutor comment has been put in yet.";
            } else {
                if($_research_subject == NULL){
                    $_dis_rs= 'Not set yet ... ';
                } else $_dis_rs=$_research_subject;
                if($_tutor_comment == NULL){
                    echo $_tutor_commnet;
                    $_dis_tc= 'No comment added ... ';
                } else $_dis_tc=$_tutor_comment;
                
                echo "
                    <table border='0' align='left'>
                        <tr><td><B>Research Subject</b> : </td>
                        <td> <i>$_dis_rs</i></td></tr>
                        <tr><td><B>Admin Tutor's Comment</b> : </td>
                        <td> <i>$_dis_tc</i></td></tr>
                     </table>
                    <BR><BR><BR>
                    ";
            }
            echo "<a href='./editrsadmin.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'>Update Research Admin</a><HR>";
        ?>	
        
            <div align="right">
                <?php
                    if($admin){
                        echo "<a href='./main.php'>PhD Admission Portal</a>>";
                    }
                ?>
                <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
                ><?php echo "<a href='viewapplication.php?id=$_admission_id&query=$_query&type=$_type'> $_forename ($_surname)</a>";?>>
                <b><i>Research Info</i></b>
            </div><BR>
</html>