#!/usr/bin/php
<!-- /////////////////////////////// Filter and display application in catagories//////////////////////////////////////// -->
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--/////////////////////////////////////// Check Permission //////////////////////////////////////////////////////////// -->
<?php
	include "func/access.php";
	$admin=check_admin();
	$user=$_SERVER['REMOTE_USER'];
	$_searchfield=$_GET['query'];
	$_fieldtype=$_GET['type'];
	if($_fieldtype == NULL ){
		$_fieldtype = 'status';
	}
	if ($_GET['order']){
		$_orderby=$_GET['order'];
	}
	else $_orderby=$_fieldtype;
	if (!$admin&&(($_fieldtype!="status")||($_searchfield!="pending" && $_searchfield!="accepted"))) {
	echo "You can only view Pending or Accepted Applications";
	return;
}
	
?>
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
	<title>Admissions Table View - <?php echo $user;?></title>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
    
	<body class = "LightGoldenRodYellow" >
    	<?php
			// Bread Crum
			if($admin){
				echo "<div align='right'><a href='./main.php'>PhD Admission Portal</a></div><HR>";
			}

			// Different View for different group of user 
            if($admin){
            $_dis_all = "<a href='sortapp.php?type=status&query=Showall'>Show All</a>";
            $_dis_rejected = "<a href='sortapp.php?type=status&query=rejected'>Rejected</a>"; 
            $_dis_unclassified = "<a href='sortapp.php?type=status&query=special'>Unclassified</a>"; 
            $_dis_deleted = "<a href='sortapp.php?type=status&query=deleted'>Deleted</a>";  
            }
            $_dis_accepted = "<a href='sortapp.php?type=status&query=accepted'>Accepted</a>";
            $_dis_pending = "<a href='sortapp.php?type=status&query=pending'>Pending</a>"; 
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
       
			// Display diferent link in differnt view 
            switch($_searchfield){
                case accepted; echo "<b><i>Accepted</i></b>"." ".$_dis_pending."  "."  ".$_dis_rejected."  "."  ".$_dis_unclassified."  "."  ".$_dis_deleted; echo "<BR>"; break;
                case rejected; echo $_dis_accepted."  ".$_dis_pending."  "."<b><i>Rejected</i></b>"."  "."  ".$_dis_unclassified."  "."  ".$_dis_deleted; echo "<BR>"; break;
                case pending; echo $_dis_accepted."  "."  "."<b><i>Pending</i></b>"."  ".$_dis_rejected."  ".$_dis_unclassified."  "."  ".$_dis_deleted; echo "<BR>"; break;
                case special; echo $_dis_accepted."  "."  ".$_dis_pending."  "."  ".$_dis_rejected."  "."<b><i>Unclassified</i></b>"."  ".$_dis_deleted;echo "<BR>"; break;
                case deleted; echo $_dis_accepted."  "."  ".$_dis_pending."  "."  ".$_dis_rejected."  "."  ".$_dis_unclassified."  "."<b><i>Deleted</i></b>"; echo "<BR>"; break;
            }  
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			// Change the display of status 
            switch ($_searchfield) {
                case 'Showall'; $_dis_status = "All" ;break;
                case 'pending'; $_dis_status = "Pending" ;break;
                case 'accepted'; $_dis_status = "Accepted" ; break;
                case 'rejected'; $_dis_status = "Rejected" ; break;
                case 'deleted'; $_dis_status = "Deleted" ; break;
                case 'special'; $_dis_status = "Unclassified" ; break;
                case 'msc';$_dis_status = "Recommend For MSc" ; break;
            }
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ?>
        <P class = 'centre_blue'><?php echo "$_dis_status Applications";?></p><BR>
        <BR>
        <!-- Table Headers -->
        <table border align="center" cellpadding=10   >
          <tr class="tableviewheading">
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=registry";?>'>Registry Number</td>
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=forenames";?>'>Name</a></td>
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=time_added";?>'>Added Date</a></td>
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=research_subject";?>'>Research subject </a></td>
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=possible_funding";?>'>Funding</a></td>
            <td align="center"><a href='sortapp.php?type=<?php echo $_fieldtype."&query="."$_searchfield"."&order=origin";?>'>Origin</a></td>
            <td align="center">Documents </td>
            <td align="center"><?php if($_searchfield!='accepted'){echo "Potential";}?> Supervisors </td>
            <td align="center">Comments</td>
          </tr>
          <?php 
		  	// Connect to database
			if (!$link){$link=connectdb();}
			// Get all PhD admissions from admission table using given criteria
			if ($_searchfield=="Showall"){
				$query="SELECT * FROM phd_admissions ORDER BY ".$_orderby." DESC;";
			} else {
				$query="SELECT * FROM phd_admissions WHERE ". $_fieldtype . "='".$_searchfield."' ORDER BY ".$_orderby." DESC;";
			}
			$admissions=pg_exec($query);
			$rows= pg_num_rows($admissions);
			
			// Display info of each entry of PhD admission in a table view
			for ($i=0; $i<$rows; $i++){
				// For each admission : Collect information for displaying
				$arr=pg_fetch_array($admissions,$i);
				$_admission_id = $arr[0];
				$_registry = $arr[1];
				$_surname = $arr[2];
				$_foresname = $arr[3];
				$_origin = $arr[4];
				$_funding = $arr[6];
				$_research_subject = $arr[12];
				$_date_added = $arr[10];
				$_admin_tutor_comment = $arr[13];
				
				 
					
				/* Change the display values according to the values in the database */
						switch ($_origin) {
							case 'HOME'; $_dis_origin = "H" ;break;
							case 'EUROPEAN'; $_dis_origin = "E" ; break;
							case 'OVERSEA'; $_dis_origin = "O" ; break;
							case 'other'; $_dis_origin = "?" ; break;
						}
						switch ($_funding) {
							case 'EPSRC'; $_dis_funding = "EPSRC" ;break;
							case 'SELF'; $_dis_funding = "SELF" ; break;
							case 'OTHER'; $_dis_funding = "OTHER" ; break;
						}
				// Change name of sudent to link to their information, used only by the administrator
				if($admin){
					$_detail_link = "<a href='viewapplication.php?id=$_admission_id&query=$_searchfield&type=$_fieldtype'> $_foresname ($_surname)</a>";
				} else {
					$_detail_link = "$_foresname ($_surname)";
				}
				
				// Start displaying data on table view
				
				// Display registry number and name of student
				echo "<tr>
			   		<td align='center'> $_registry</td>
			   		<td align='center'>$_detail_link</td>
					<td align='center'>$_date_added</td>
			   		<td align='middle'>";
				
			   	if ($_research_subject)echo"$_research_subject"; else echo"N/A";
			   
			   // Display funding and origin info
			   echo"</td>
			   <td align='middle'>$_dis_funding</td>
			   <td align='middle'>$_dis_origin</td>
			   <td>";
			   
			   $_document_query = "SELECT document_id, document_type, hidden, reference FROM uploaded_documents 
						WHERE admission_id=$_admission_id ORDER BY document_type,document_id;";
            	$_document_result=pg_query($_document_query);
				$_document_rows=pg_num_rows($_document_result);
				if ($_document_rows>0){
					$_name_index = 1;
					$_last_type = ''; 
					for ($x=0;$x<$_document_rows;$x++){
						$_document_arr=pg_fetch_array($_document_result,$x);
						$_document_id = $_document_arr[0];
						$_document_type = $_document_arr[1];
						$_hidden = $_document_arr[2];
						$_reference = $_document_arr[3];
						
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
							$_document_type_link = "<a href='./$_reference' target='_blank'>$_dis_type</a><BR>";
							echo $_document_type_link ;
						} /*else {
							$_document_type_link = $_dis_type."<BR>";
						}*/
			   			// Display link to all document
					}
				} else {	
					echo "<div align= 'middle'>N/A</div>";
				}
			    echo "</td><td>";
			 	// Check if the user is already a potential supervisor or a supervisor
			   $query5 ="SELECT academic_login FROM admission_supervisor 
			   				WHERE admission_id=$_admission_id 
							AND academic_login='$user'
							AND (supervisor_flag='potential' OR supervisor_flag='supervisor');";
				$result5= pg_query($query5);
				$rows5= pg_num_rows($result5);
				
				$student_status = $arr[8];
				$pot_confirmed_supervisors=array();
				// If a student is accepted, only display his/her supervisors
				if ($student_status == 'accepted') {
					$query6="SELECT academic_login FROM admission_supervisor WHERE admission_id='$_admission_id' AND supervisor_flag = 'supervisor';";	
					$confirmed_supersres = pg_exec($link,$query6);
					$confirmed_superno =pg_num_rows($confirmed_supersres);		
					for($k=0;$k<$confirmed_superno;$k++){
						$confirmed_superarray=pg_fetch_array($confirmed_supersres,$k);
						$pot_confirmed_supervisors[$k]=$confirmed_superarray[0];
					}		
				}
				
				if (sizeof($pot_confirmed_supervisors)>0){
					foreach ( $pot_confirmed_supervisors as $ps) {
						echo "<a href='viewacdcomment.php?id=$_admission_id&us=$ps&query=$_searchfield&type=$_fieldtype'><b><i>$ps</i></b></a> ";
					}
				}else { echo "&nbsp";}
				
				/* If a student is not accepeted, then display all of his/her potential supervisors and supervisors */
				// Get all potential supervisors of the student 
				
				if ($student_status != 'accepted' ){
					$query2="SELECT academic_login, supervisor_flag FROM admission_supervisor 
						WHERE admission_id='$_admission_id' AND (supervisor_flag = 'potential' OR supervisor_flag = 'supervisor')
						ORDER BY supervisor_flag DESC;";
					$supersres= pg_exec($query2);
					$superno=pg_num_rows($supersres);
					$pot_supervisors=array();
					$_supervisor_flag=array();
					if($superno>0) {
						for($k=0;$k<$superno;$k++){
							$superarray=pg_fetch_array($supersres,$k);
							$pot_supervisors=$superarray[0];
							$_supervisor_flag= $superarray[1];
							if($_supervisor_flag == 'supervisor'){
								echo "<a href='viewacdcomment.php?id=$_admission_id&us=$pot_supervisors&query=$_searchfield&type=$_fieldtype'><font color='red'><i><b>$pot_supervisors</b><i></a> ";
							} else {
								echo "<a href='viewacdcomment.php?id=$_admission_id&us=$pot_supervisors&query=$_searchfield&type=$_fieldtype'>$pot_supervisors</a> ";
							}
						}
					}
					
					if($rows5==0 && $_searchfield== 'pending'){
						echo "<form action='./addptspvs.php?query=$_searchfield&type=$_fieldtype&order=$_orderby' method='post'>
        						<input type='hidden' name='admission_id' value='$_admission_id' />
								<input type='submit' value='Add Yourself'>
        					</form>";
							//<input align='right' type='image' name='submit' src='icon/add.gif' />
					} 
					echo"</td>";
				}
				
				// Display number of comments made by academics on the student profile		  
				$_query_comment_count="SELECT COUNT(comment_and_justification) FROM admission_supervisor WHERE admission_id=$_admission_id;";
				$_result_comment_count= pg_query($_query_comment_count);
				$_comment_count=pg_fetch_array($_result_comment_count,0);
				if($_admin_tutor_comment != NULL){
					$_total_comment = $_comment_count[0]+1;
				} else {
					$_total_comment = $_comment_count[0];
				}
				echo "<td align='middle'><a href='acdcomments.php?id=$_admission_id&query=$_searchfield&type=$_fieldtype'>View All ($_total_comment)</a>";
				echo "<br><a href='editacdcomment.php?id=$_admission_id&sv=$user&type=$_fieldtype&query=$_searchfield'>Add/Edit</a></td>";
			}
        ?>
        </table>
        <HR></hr>
        	<?php
				// Bread Crum
				if($admin){
					echo "<div align='right'><a href='./main.php'>PhD Admission Portal</a></div><HR>";
				}
			?>
        <BR /><BR /><BR />
        
        <?php if($_searchfield == 'pending'){ ?>
        
        	<div style="font-style:italic">*Note: Click on <input type="submit" value="Add Yourself" /> to add your self as a potential supervisor</div>
		<?php } ?>
        
        
	</body>
</html>
