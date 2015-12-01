#!/usr/bin/php
<!-- ////////////////////////////////////Add/ Edit applicant detail form////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--///////////////////////////////////// Check access permission /////////////////////////////////////////////////// -->
<?php 
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "Unauthorised access. You don't have permission to access this page.";
		return;
	}
?>
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Basic Detail Form</title>
    </head>

	<body class = "LightGoldenRodYellow" >
		<P class ="centre_blue"  >Add Applicant</p>
  		<hr />
		<div align="center" class=""><B> Basic Details</b><hr /></div>

        <!-- Basic Detail Form -->
        <?php
			// Get student detail if editting or revisit the page due to error --> avoid user having to re input data
            $_error_code= $_GET['ec'];
            $_query= $_GET['query'];
            $_type= $_GET['type'];
            $_action = $_GET["ac"];
			$_revisit = $_GET ["revisit"];
            if($_action == "edit" || $_revisit == "true") {
                $_admission_id= $_GET['id'];
                $_regno = $_GET['re'];
                $_sur_name = $_GET['su'];
                $_fore_name = $_GET['fo'];
                $_funding = $_GET['fu'];
                $_origin = $_GET['or'];
                $_status = $_GET['st'];
                $_funding_note = $_GET['fn'];
                $_status_note = $_GET['sn'];
                $_origin_note = $_GET['on'];
            } else {
                $_regno = NULL;
                $_sur_name = NULL;
                $_fore_name = NULL;
                $_funding = NULL;
                $_origin = NULL;
                $_status = NULL;
                $_funding_note = NULL;
                $_origin_note = NULL;
                $_status_note = NULL;
            }
			/* Error code:
				1....  : Student is accepted, but no supervisor found
				.1...  : forename is not an alphabet term
				..1..  : surname is not an alphabet term
				...1.  : surname is missing
				....1  : regno is not numerical
			*/
			$_error_msg_1 = "";
			$_error_msg_2 = "";
			$_error_msg_3 = "";
			$_error_msg_4 = "";
			$_error_msg_5 = "";
			if ($_error_code != 0){
				if(($_error_code % 10) >= 1){
					// regno is not numerical
					$_error_msg_1 = " * Registry number must be an numerical term." ;
				}
				if(($_error_code % 100) >= 10){
					// surname is missing
					$_error_msg_2 = " * Surname is missing. Please input a surname." ;
				}
				if(($_error_code % 1000) >= 100){
					// surname is not an alphabet term
					$_error_msg_3 = " * Surname must contain only aphabetical characters." ;
				}
				if(($_error_code % 10000) >= 1000){
					// forename is not an alphabet term
					$_error_msg_4 = " * Forenames must contain only aphabetical characters." ;
				}
				if(($_error_code % 100000) >= 10000){
					// Student is accepted, but no supervisor found
					$_error_msg_5 = " * No supervisor is appointed for this student." ;
				}
				if($_error_code >= 100000){
					// Invalid academic login is typed in
					$_error_msg_6 = " * The login you gave is not a valid academic again. Please retry again. ";
				}
			}
        ?>
		<!-- The actual form body -->
         <FORM <?php echo "action='savebasicdetail.php?query=$_query&type=$_type'" ?> method="post">
            <Input type="hidden" name="action" <?php echo"value=$_action";?>>
            <Input type="hidden" name="admission_id" <?php echo"value=$_admission_id";?>>
            <P>
            <table border="0">
            
            <tr>
            	<td><LABEL><B>Registry Reference Number</b> </LABEL></td>
                <td><INPUT name="registry" type="int" id="registry" <?php echo"value=$_regno ";?>></td>
                <td>&nbsp;</td>
                <td><?php echo "<font size='2' face='verdana' color='red'>$_error_msg_1</font>";?></td>
            </tr>
            <tr>
            	<td><LABEL><B>Surname</b> </LABEL></td>
                <td><INPUT name="surname" type="text" id="surname" <?php echo"value=$_sur_name ";?>></td>
                <td>&nbsp;</td>
                <td><?php if($_error_msg_2 != NULL){
							echo "<font size='2' face='verdana' color='red'>$_error_msg_2</font>";
						} else {
							echo "<font size='2' face='verdana' color='red'>$_error_msg_3</font>";
						}
						
					?></td>	
            </tr>
            <tr>
            	<td><LABEL><B>Forename</b> </LABEL></td>
                <td><INPUT name="forename" type="text" id="forename" <?php echo"value='$_fore_name' ";?>></td>
                <td>&nbsp;</td>
                <td><?php echo "<font size='2' face='verdana' color='red'>$_error_msg_4</font>";?></td>
            </tr>
                      
            <tr><td><LABEL><B>Possible Funding</b> </LABEL></td>                     
                <td><select name="funding">
                    <option value="EPSRC" <?php if($_funding=='EPSRC'){echo "selected='selected'";}?>>EPSRC</option>
                    <option value="SELF" <?php if($_funding=='SELF'){echo "selected='selected'";}?>>SELF</option>
                    <option value="OTHER" <?php if($_funding=='OTHER'){echo "selected='selected'";}?>>OTHER</option>
                    </select></td>
                <td><LABEL>Note</LABEL></td>
                <td><INPUT name="funding_note" type="text" id="funding_note" <?php echo"value='$_funding_note' ";?>></td>
            </tr>
            
            <tr><td><LABEL><B>Origin(Fee Classification)</b> </LABEL></td>                      
                <td><select name="origin">
                    <option value="HOME" <?php if($_origin=='HOME'){echo "selected='selected'";}?>>HOME</option>
                    <option value="EUROPEAN" <?php if($_origin=='EUROPEAN'){echo "selected='selected'";}?>>EUROPEAN</option>
                    <option value="OVERSEA" <?php if($_origin=='OVERSEA'){echo "selected='selected'";}?>>OVERSEA</option>
                    <option value="other" <?php if($_origin=='other'){echo "selected='selected'";}?>>OTHER</option>
                    </select></td>
                <td><LABEL>Note</LABEL></td>
                <td><INPUT name="origin_note" type="text" id="origin_note" <?php echo"value='$_origin_note' ";?>></td>
            </tr>
            
            <tr><td><LABEL><B>Status</b> </LABEL></td>                   
                <td><select name="status">
                    <option value="pending" <?php if($_status=='pending'){echo "selected='selected'";}?>>Pending</option>
                    <option value="accepted" <?php if($_status=='accepted'){echo "selected='selected'";}?>>Accept</option>
                    <option value="rejected" <?php if($_status=='rejected'){echo "selected='selected'";}?> >Reject</option>
                    <option value="deleted" <?php if($_status=='deleted'){echo "selected='selected'";}?>>Delete</option>
                    <option value="special" <?php if($_status=='special'){echo "selected='selected'";}?>>Unclassified</option>
                    <option value="msc" 
                        <?php if($_status=='recommend_For_MSc'){echo "selected='selected'";}?>>Recommend For MSc</option>
                    </select></td>
                <td><LABEL>Note</LABEL></td>
                <td><INPUT name="status_note" type="text" id="status_note" <?php echo"value='$_status_note' ";?>></td>
            </tr>
            </table>
            <?php echo "<font size='2' face='verdana' color='red'>$_error_msg_5</font><BR>"; ?>
            <?php 
				// Promp user to input at least 1 supervisor in order to accept the student
				if($_error_msg_5 != NULL || $_error_msg_6 !=NULL){
					if($_action == "edit"){
						if (!$link){$link=connectdb();}
						$query = "SELECT academic_login,supervisor_flag FROM admission_supervisor WHERE admission_id = $_admission_id;";
						$result=pg_query($query);
						$rows=pg_num_rows($result);
						if($rows > 0) {
							$_haha = "Select from list of potential supervisors: <select name='potential_supervisor'>
										<option value='select'> Select... </option>";
							echo $_haha;			
							for($i=0;$i<$rows;$i++){
								$arr=pg_fetch_array($result,$i);
								if($arr[1] == 'potential'){
									echo "<option value='$arr[0]'>$arr[0]</option>";
								}
							}	
							echo "</select>";
							echo " or type in academic login: ";
							echo "<INPUT name='supervisor' type='text' id='supervisor'>";
						} else {
							echo "Type in supervisor login: <INPUT name='supervisor' type='text' id='supervisor'>";
						}
					} else {
						echo "Type in supervisor login: <INPUT name='supervisor' type='text' id='supervisor'>";
					}
				}	
				if($_error_msg_6 != NULL){
					echo "<font size='2' face='verdana' color='red'>$_error_msg_6</font><BR>";
				}
			?>
                       
            <HR>
            <INPUT type="submit" value="Save"> <INPUT type="reset" value="Reset">
            <HR>
            <!-------------------------Navigation -------------------------->
            <div align="right">
              <?php
			  	// Bread crum
                if ($_action == 'edit') { 
					if($admin){
						echo "<a href='./main.php'>PhD Admission Portal</a>>";
					}
					echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>>";
                    echo "<a href='./viewapplication.php?id=$_admission_id&type=$_type&query=$_query'>$_fore_name $_sur_name</a>>";
                    echo "Edit Detail";
                } else {
                	if($admin){
					echo "<a href='./main.php'>PhD Admission Portal</a>>";
					}
                    echo "<i><b>New Application</b></i>";
                }
            ?>
            </div>
         </form>
</body>
</html>

 