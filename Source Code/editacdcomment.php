#!/usr/bin/php
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- //---------------------------------Form for add/edit academic comment -----------------------------// -->
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php
	// Get administration status
	include "func/access.php";
	$admin=check_admin();
	// get logged in username
	$user = $_SERVER["REMOTE_USER"];
	// Get data from URL
	$_spvs_login = $_GET['sv'];
	$_admission_id = $_GET['id'];
	$_query= $_GET['query'];
	$_type= $_GET['type'];
	
	// Check if user is allowed to access the page
	if($user != $_spvs_login){
		echo "<div>Unauthorised access</div>";
		echo "<div>You can only edit your own comment</div>";
		return;
	}
	// Check if the page is reloaded due to input error
	if (!$link){$link=connectdb();}
	if($_GET['revisit']){
		$_adc_comment = urldecode($_GET['comment']);
        $_accept_cond = urldecode($_GET['cond']);
        $_other_spvs = $_GET['other'];
        $_message = " * You must select one Recommendation to proceed.";
	} else {
		$query = "SELECT recommendation, comment_and_justification, acceptance_condition, other_supervisor 
									FROM admission_supervisor WHERE academic_login='$user' AND admission_id=$_admission_id ;";
		$result= pg_query($query);
		$rows=pg_num_rows($result);
		if($rows>0){
			$arr=pg_fetch_array($result,0);
			$_recommendation= $arr[0];
			$_adc_comment= $arr[1];
			$_accept_cond= $arr[2];
			$_other_spvs= $arr[3];
			$_action = 'edit';
		} else {
			 $_action = 'addnew';
			 $_adc_comment= NULL;
			 $_accept_cond= NULL;
			 $_other_spvs= NULL;
		}
	}
				
?>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Edit Tutor Comment</title>
    </head>
    
    <body class = "LightGoldenRodYellow" >
        <P class ="centre_blue">Edit Comments</p> 
        <!-- Academic comment form -->   
            <form <?php echo "action='updateacdcomment.php?id=$_admission_id&sv=$_spvs_login&action=$_action&type=$_type&query=$_query'" ?>
            		method="post">
                <table border="0">
                    <tr><td><label><B>Recommendation</b></label></td>
                        <td><select name="recommendation">
                            <option value="no_selection" <?php if($_recommendation=='no_selection'){echo "selected='selected'";}?>>Please Select One</option>
                            <option value="accept_supervise" <?php if($_recommendation=='accept_supervise'){echo "selected='selected'";}?>>Accepted and Supervise</option>
                            <option value="accept_not_supervise" <?php if($_recommendation=='accept_not_supervise'){echo "selected='selected'";}?>>Accepted But Not Supervise</option>
                            <option value="msc" <?php if($_recommendation=='msc'){echo "selected='selected'";}?>>Recommend for Msc</option>
                            <option value="wait" <?php if($_recommendation=='wait'){echo "selected='selected'";}?>>Wait to be interviewed</option>
                                  <option value="rejected" <?php if($_recommendation=='rejected'){echo "selected='selected'";}?>>Rejected</option>                    
                            </select> <?php echo "<font size='2' face='verdana' color='red'>$_message</font>"?>
                        </td></tr>
                    <tr><td><label><B>Comments & Justifications</b></label></td>
                        <td>
                            <textarea name= "acdcomment" cols="80" rows="6"><?php echo $_adc_comment;?></textarea>
                        </td></tr>
                    <tr><td><label><B>Acceptance Conditions</b></label></td>
                        <td>
                            <textarea name= "accept_cond" cols="80" rows="4"><?php echo $_accept_cond;?></textarea>
                        </td></tr> 
                    <tr><td><label><B>Other Possible Supervisors</b></label></td>
                        <td><input name='other_spvs' type="text" <?php echo "value = $_other_spvs";?>></td></tr>   
                </table>   
                <input type="submit" value="Update" >
            </form>
             
    </body>
    <HR>
    <!-- //////////////////////////////////////////////////////////// Bread Crum //////////////////////////////////////////////////////////-->
    <div align="left">
        <?php 
			// Bread crum
			if($admin){
				echo "<a href='./main.php'>PhD Admission Portal</a>>";
			}
		?>
        <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>>" ?>
        <?php echo "<a href='viewacdcomment.php?id=$_admission_id&us=$_spvs_login&type=$_type&query=$_query'>$_spvs_login comment</a>>";?>
        <i><b>Edit</b></i>
    </div>
</html>