#!/usr/bin/php
<?php

	include "func/access.php";
	$date_send_all_comments = "Monday";
	//$date_send_all_comments = "Sunday";
	
	// this is the root address of the web portal. Change this appropriately when integrate
   $portal_root = "https://www.doc.ic.ac.uk/project/2008/362/g0836218/phdadm/";
	
	if (date('l') == $date_send_all_comments){
		
		// send weekly email
		
		
		if (!$link){$link=connectdb();}
      $query= "SELECT admin_comment_id, date_added, comment, admission_id, sender
      		 	FROM admin_comment WHERE dealt_with = 'f' ORDER BY admission_id;";
      $result= pg_query($query);
      $rows= pg_num_rows($result);
      $_box_count = 0;
      if ($rows>0){
     
			// recipients
			$to  = 'rz105@doc.ic.ac.uk';
		
			// subject
			$subject = 'Phd Admin Comments Reminder';

			// message
			$message = '
			<html>
			<head>
			<title>PhD Admissions Portal - Weekly Reminder</title>
			</head>
			<body>
			<h1><a href="'. $portal_root .'main.php">
			<font color="#3366ff">PhD Admissions Portal - Weekly Reminder</font></a> 
			</h1>
		 	<p>This email contains all comments in database that has not been dealt with.<br> <br>Generated on: '. date('Y-m-d') .'</p>';
		  
        	$current_admission_id;
         for ($x=0;$x<$rows;$x++){
			 
         	$arr=pg_fetch_array($result,$x);
		  		$_admin_comment_id = $arr[0];
         	$_date_added = $arr[1];
         	$_comment = $arr[2];
         	$_admission_id = $arr[3];
         	$_sender = $arr[4];
				
				if($current_admission_id != $_admission_id){
					$current_admission_id = $_admission_id;

					$query2= "SELECT forenames, surname, research_subject FROM phd_admissions WHERE admission_id = $_admission_id;";
            	$result2= pg_query($query2);
					$arr2=pg_fetch_array($result2,0);
			
		      	$_forenames = $arr2[0];
            	$_surname = $arr2[1];
            	$_subject = $arr2[2];
                    		
            	$message .= '<hr style="width: 100%; height: 2px;">
							<span style="font-weight: bold;">'. $_forenames.' '. $_surname .'</span> ('. $_subject .')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<font style="font-family: Times New Roman;" size="3"><a href="'. $portal_root .'viewapplication.php?id='. $_admission_id .'&query=Showall&type=status">view profile</a></font>
							<br><br><br>';
			 	}


			 	$message .= '<span style="font-style: italic;">'. $_sender .' wrote:</span>
						<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						<font size="1"><span style="font-style: italic;"> '. $_date_added .'</span></font>
						<br>
						<br>'. $_comment .'<br><br>
						</span><br>';
 
         }
         $message .= '</body></html>';

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'From: Phd Admission Portal' . "\r\n";

			// Mail it
			mail($to, $subject, $message, $headers);
		
			echo "Message successfully sent!";
     		
      }
	}
	
	
	
	else {
		// send daily email 

		
		if (!$link){$link=connectdb();}
      $query= "SELECT admin_comment_id, date_added, comment, admission_id, sender
             	FROM admin_comment WHERE dealt_with = 'f' ORDER BY admission_id;";
      $result= pg_query($query);
      $rows= pg_num_rows($result);
      $_box_count = 0;
      
      if ($rows>0){
                	

			// recipients
			$to  = 'rz105@doc.ic.ac.uk';
		
			// subject
			$subject = 'Phd Admin Comments Reminder';

			// message
			$message = '
			<html>
			<head>
		  	<title>PhD Admissions Portal - Daily Reminder</title>
			</head>
			<body>
		 	<h1><a href="'. $portal_root .'main.php">
			<font color="#3366ff">PhD Admissions Portal - Daily Reminder</font></a> 
			</h1>
		  	<p>This email contains all comments in database that has not been dealt with for more than 1 week.<br> <br>Generated on: '. date('Y-m-d') .'</p>';
		  

			
      	$current_admission_id;
     		for ($x=0;$x<$rows;$x++){
			 
           	$arr=pg_fetch_array($result,$x);
		   	$_admin_comment_id = $arr[0];
           	$_date_added = $arr[1];
           	$_comment = $arr[2];
           	$_admission_id = $arr[3];
           	$_sender = $arr[4];
					
				$compare_query = "SELECT EXTRACT(DAY FROM AGE(current_timestamp, timestamp '". $_date_added ."'));";
         	$compare_date = pg_query($compare_query);
         	$_date_array = pg_fetch_array($compare_date,0);					
         	$_date_difference = $_date_array[0];
      
					
			   if ($_date_difference > 7){
					   
					  if($current_admission_id != $_admission_id){
						$current_admission_id = $_admission_id;

						$query2= "SELECT forenames, surname, research_subject FROM phd_admissions WHERE admission_id = $_admission_id;";
            		$result2= pg_query($query2);
						$arr2=pg_fetch_array($result2,0);
			
		      		$_forenames = $arr2[0];
            		$_surname = $arr2[1];
            		$_subject = $arr2[2];
                    		
            		$message .= '<hr style="width: 100%; height: 2px;">
						<span style="font-weight: bold;">'. $_forenames.' '. $_surname .'</span> ('. $_subject .')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<font style="font-family: Times New Roman;" size="3"><a href="'. $portal_root .'viewapplication.php?id='. $_admission_id .'&query=Showall&type=status">view profile</a></font>
						<br><br><br>';
			 		}


					$message .= ' 

			
					<span style="font-style: italic;">'. $_sender .' wrote:</span>
					<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					<font size="1"><span style="font-style: italic;"> '. $_date_added .'</span></font>
					<br>
					<br>'. $_comment .'<br><br>
					</span><br>'; 
					   
				}                 
         }
         
			$message .= '</body></html>'; 
			
			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

			// Additional headers
			$headers .= 'From: Phd Admission Portal' . "\r\n";

			// Mail it
			mail($to, $subject, $message, $headers);
	
			echo "Message successfully sent!";        
         
      }

	
	}




/*
	$type = $_POST['type'];
	
	include "func/connect.php";
if (!$link){$link=connectdb();}	echo("<p>$type</p>");

		
		$to = "rz105@doc.ic.ac.uk";
		$subject = "Hi!";
		$body = "Hi,\n\nHow are you?";
		$headers = "From: PHD admin portal\r\n" .
   	 	"X-Mailer: php";
		if (mail($to, $subject, $body, $headers)) {
	  	echo("<p>Message sent!</p>");
	 	} else {
	  	echo("<p>Message delivery failed...</p>");
	 	}
*/	



?>
