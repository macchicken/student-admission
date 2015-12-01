#!/usr/bin/php
<!-- /////////////////////////////////////// Application viewing page /////////////////////////////////////////////////// -->
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<?php 
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
	echo "Unauthorised access. You don't have permission to access this page.";
	return;
	}
	$_query= $_GET['query'];
	$_type= $_GET['type'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>View Application</title>
</head>
<body class = "LightGoldenRodYellow" >
<?php
	/* Get The registry number of the student being view and name of the user logged on*/
	$_admission_id= $_GET['id'];
	$user=$_SERVER["REMOTE_USER"];
	
	// Error appear when the page fail to load.
	$_page_load_error="<div>No matching application is found. This may be caused by some mistake on the page address you typed in or if you failed to add a new application.. </div><BR>
					<div>Please go to the following page and try again : </div><BR>";
	if($admin){
		$_page_load_error .= "<a href='./main.php'>PhD Admission Portal</a>";
	} else {
		$_page_load_error .= "<a href='./sortapp.php?type=status&query=pending'>Pending Applicants</a>";
	}
	
		/* Connect and query the database */
		//include "func/connect.php";
		if (!$link){$link=connectdb();}
		$query = "SELECT registry, surname, forenames,origin, possible_funding, status, origin_note, funding_note, status_note 
					FROM phd_admissions WHERE admission_id = $_admission_id ;";
		$result=@pg_query($query) or die($_page_load_error);
		$rows=pg_num_rows($result);
		/* If there is at least 1 rows in the result, get the basic detail*/
		if ($rows>0){
			$arr=pg_fetch_array($result,0);
			$_regno=$arr[0];
			$_surname=$arr[1];
			$_forename=$arr[2];
			$_funding=$arr[4];
			$_origin=$arr[3];
			$_status=$arr[5];
			$_funding_note=$arr[7];
			$_origin_note=$arr[6];
			$_status_note=$arr[8];
			/* Change the display values according to the values in the database*/
			switch ($_status) {
				case 'pending'; $_dis_status = "Pending" ;break;
				case 'accepted'; $_dis_status = "Accepted" ; break;
				case 'rejected'; $_dis_status = "Rejected" ; break;
				case 'deleted'; $_dis_status = "Deleted" ; break;
				case 'special'; $_dis_status = "Unclassified" ; break;
				case 'msc';$_dis_status = "Recommend For MSc" ; break;
			}
			switch ($_origin) {
				case 'HOME'; $_dis_origin = "HOME" ;break;
				case 'EUROPEAN'; $_dis_origin = "EUROPEAN" ; break;
				case 'OVERSEA'; $_dis_origin = "OVERSEA" ; break;
				case 'other'; $_dis_origin = "OTHER" ; break;
			}
			switch ($_funding) {
				case 'EPSRC'; $_dis_funding = "EPSRC" ;break;
				case 'SELF'; $_dis_funding = "SELF" ; break;
				case 'OTHER'; $_dis_funding = "OTHER" ; break;
			}
			if ($_funding_note != NULL){
				$_dis_funding_note = "(Note: ".$_funding_note." )";
			}
			if ($_origin_note != NULL){
				$_dis_origin_note = "(Note: ".$_origin_note." )";
			}
			if ($_status_note != NULL){
				$_dis_status_note = "(Note: ".$_status_note." )";
			}
		} else {
			echo $_page_load_error;
			return;
		}
?> 



	
	<div align="right">
     	<?php
			if($admin){
				echo "<a href='./main.php'>PhD Admission Portal</a>>";
			}
		?>
        <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
        ><b><i><?php echo $_forename.' '.$_surname;?></i></b>
    </div><BR>
	<P class="centre_blue">View Applicant Details</p><HR/> 


	<!------------------- Display the basic detail the student -------------->
	<HR></hr><div align="center"><B>Basic Details</b></div>
	<table border="0">  
  	<?php
			/* Display the obtained value on an html format */
			echo "
				<tr><td><B>Registry Reference Number</b></td>
				<td>$_regno</td></tr>
				<tr><td><B>Surname</b></td>
				<td>$_surname</td></tr>
				<tr><td><B>Forename</b></td>
				<td>$_forename</td></tr>
				<tr><td><B>Funding</b></td>
				<td>$_dis_funding</td>
				<td>$_dis_funding_note</td></tr>
				<tr><td><B>Origin</b></td>
				<td>$_dis_origin</td>
				<td>$_dis_origin_note</td></tr>
				<tr><td><B>Status</b></td>
				<td>$_dis_status</td>
				<td>$_dis_status_note</td></tr>
				";
	?>
	</table>
	<!------ The Edit Button to go to ediable page for modifying basic detail ------>
    <?php 
		$_href= "editapp.php?id=$_admission_id&type=$_type&query=$_query&re=$_regno&su=$_surname&fo=$_forename&fu=$_funding&or=$_origin&st=$_status&fn=$_funding_note&sn=$_status_note&on=$_origin_note&ac=edit";
		echo "<a href='$_href'>Edit Basic Detail</a>";
	?>
    <!----------------- Finish the basic detail display part ----------------------->
<HR>
    
    <!-------------------------- Document Upload Form ------------------------------>
    <HR></hr>
    <?php 
		// Get upload warning message
		$_warning_code = $_GET['msg'];
	?>
	<div align="center"><B>Document Upload</b></div>
    
    <?php 
		$_error_code = $_GET['ec'];
		if(($_error_code % 10) >= 2){
			$_err_msg[0] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/tick.gif'>File uploaded sucessfully.</font>";
		} else if (($_error_code % 10) >=1 ){
			$_err_msg[0] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/cross.gif'>Failed to upload the file.</font>";
		}
		if(($_error_code % 100) >= 20){
			$_err_msg[1] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/tick.gif'>File uploaded sucessfully.</font>";
		} else if (($_error_code % 100) >=10){
			$_err_msg[1] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/cross.gif'>Failed to upload the file.</font>";
		}
		if($_error_code >= 200){
			$_err_msg[2] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/tick.gif'>File uploaded sucessfully.</font>";
		} else if ($_error_code >=100){
			$_err_msg[2] = "<font size='2' face='verdana' color='red'><img border='0' src='./icon/cross.gif'>Failed to upload the file.</font>";
		}
		
	?>
	<form name='fileupload' enctype='multipart/form-data' <?php echo "action='insertdoc.php?type=$_type&query=$_query'";?> method='POST'>
    	<input type="hidden" name="admission_id" <?php echo "value=$_admission_id" ?> />
    	<div>Choose file(s) to upload:</div><BR>
           
        <!------------------ Different Document type ----------------------->
        	<?php 
				if($_warning_code == '1'){ 
					echo "<font size='2' face='verdana' color='red'>* You need to choose a type for the document in order to process.</font>";
				}
			?>
            <?php
				for($i=0; $i<3; $i++){
			?>
            		<label>Select file </label>
            		<input name="uploadedfile<?php echo $i; ?>" type="file" />
                    <Label> Document Type</Label>
                    <select name="doc_type<?php echo $i; ?>">
                    	<option value="app_form" >Application Form</option>
                        <option value="cv" >CV</option>
                        <option value="paper" >Paper</option>
                        <option value="ref" >Research Summary</option>
                        <option value="transcript" >Transcript</option>
                        <option value="other" selected="selected">Other</option>
                    </select> 
                    <Label> Or type in a label </Label>
                    <input name="label<?php echo $i; ?>" type="text" />
                    <?php echo $_err_msg[$i]; ?>
                    <BR>
            <?php
				}
			?>
        <BR>
        <input name="upload" type="submit" value="Upload all file(s)" />  or 
        <input name="reset" type="reset" value="Clear files" />
          
	</form>
	<!-------------------------- Table of uploaded file ------------------------------>
	<HR></hr><div align="center"><B>Uploaded File</b></div>
	<table border="1">
        <tr>
            <td><B>Reference Number</b> </td>
            <td><B>Documen type</b> </td>
        </tr>
		<?php 
            $query = "SELECT document_id, document_type, hidden, reference FROM uploaded_documents 
						WHERE admission_id=$_admission_id ORDER BY document_type,document_id;";
            $result=pg_query($query);
            $rows=pg_num_rows($result);
            if ($rows>0){
				$_name_index = 1;
				$_last_type = '';
                for ($x=0;$x<$rows;$x++){
                $arr=pg_fetch_array($result,$x);
				$_document_id = $arr[0];
				$_document_type = $arr[1];
				$_hidden = $arr[2];
				$_reference = $arr[3];
				
				$_dis_type = $_document_type;
				switch ($_document_type) {
					case 'app_form'; $_dis_type = "Application" ;break;
					case 'cv'; $_dis_type = "CV" ; break;
					case 'paper'; $_dis_type = "Paper" ; break;
					case 'ref'; $_dis_type = "Research Summary" ; break;
					case 'transcript'; $_dis_type = "Transcript" ; break;
					case 'other'; $_dis_type = "Other" ; break;
				}
				if($_document_type == $_last_type){
					$_dis_type .= $_name_index;
					$_name_index ++;
				} else {
					$_name_index = 1;
				}
				$_last_type = $_document_type;
				
				if($_hidden != 't'){
					$_document_type_link = "<a href='./$_reference' target='_blank'>$_dis_type</a></td>";
					$_document_type_link .= "<td align='middle'><form action='./hidedocument.php?id=$_admission_id&query=$_query&type=$_type&order=$_orderby' method='post'>
        										<input type='hidden' name='document_id' value='$_document_id' />
												<input type='hidden' name='action' value='hide'>
												<input type='submit' value='Hide'  />
        									</form></td></tr>";
				} else {
					$_document_type_link = $_dis_type;
					$_document_type_link .= "<td align='middle'><form action='./hidedocument.php?id=$_admission_id&query=$_query&type=$_type&order=$_orderby' method='post'>
        										<input type='hidden' name='document_id' value='$_document_id' />
												<input type='hidden' name='action' value='show'>
												<input type='submit' value='Show'  />
        									</form></td></tr>";
				}
                echo"<tr><td align='middle'>" . $_document_id . "</td><td align='middle'>" . $_document_type_link ;
                }
            }
            else echo "<tr><td>No Document Uploaded</td><tr>";
        ?>
	</table><HR><HR></hr>
  	<!---------------------------------- Comment -------------------------------------->
	<div align="center"><B>Comments</b></div>
	<?php 
	$query= "SELECT admin_comment_id, date_added, comment, dealt_with, sender
                        FROM admin_comment WHERE admission_id = $_admission_id ORDER BY date_added DESC;";
	$result = pg_query($query);
	$rows= pg_num_rows($result);
	
	/* If there is at least one comment in the database, get the first three comment */
	if ($rows>0){
		echo "
		<table border='1'>
    		<tr>
      			<td><B>From</b></td>
      			<td><B>Time Posted</b></td>
      			<td><B>Comment</b></td>
      			<td><B>Dealt With</b></td>
   			 </tr>
		";
		$_comment_not_dealt = 0;
		$_comment_total = 0;
		for ($x=0;$x<$rows;$x++){
			$arr=pg_fetch_array($result,$x);
			$_date_added = $arr[1];
            $_comment = $arr[2];
            $_dealt_with = $arr[3];
            $_sender = $arr[4];
			if($x<3){			
				echo"<tr><td>" . $_sender . "</td><td>" . $_date_added . "</td><td>" . $_comment . "</td><td align='middle'>";
				if($_dealt_with=='f'){
				echo "<img border='0' src='./icon/cross.gif'></td></tr>";
				}
				else echo "<img border='0' src='./icon/tick.gif'></td></tr>";
			}
			$_comment_total ++;
			if($_dealt_with=='f'){
				$_comment_not_dealt ++;
			}
		}
		echo "</table>";
		echo "<a href='allcomment.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'><B>All Comments</b> </a>";
		if($_comment_total > 0){
			echo "(<I> $_comment_not_dealt comment(s) not dealt with yet / Total of $_comment_total comment(s)</i> )";
		}
	}
	/* No comment Posted */
	else {
		echo "<div align='left'>No comments posted</div>";
		echo "<a href='allcomment.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'>Add a comment </a>";
	}
	?>
    <BR><HR></hr><HR></hr>
    <?php echo "<a href='spvsinfo.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'>Research Info" ?>
    <BR><HR></hr><HR></hr>
    <!-- Navigation Link -->
	<div align="right">
     	<?php
			if($admin){
				echo "<a href='./main.php'>PhD Admission Portal</a>>";
			}
		?>
        <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
        ><b><i><?php echo $_forename.' '.$_surname;?></i></b>
    </div>
</body>
</html>