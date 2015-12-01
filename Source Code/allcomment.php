#!/usr/bin/php
<!-- //////////////////////////////////View all administrator comment page ////////////////////////////////////////////// -->
<!-- /////////////////////////////////////// Css Declaration //////////////////////////////////////////////////////////// -->
<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
<link href="CSS/header.css" rel="stylesheet" type="text/css" />
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<!--///////////////////////////////////////// Check access permission /////////////////////////////////////////////////// -->
<?php 
	include "func/access.php";
	$admin=check_admin();
	if (!$admin){
		echo "unauthrised access";
		return;
	}
?>
<!--///////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php
	// Get information from URL
	$_admission_id=$_GET['id'];
	$user=$_SERVER["REMOTE_USER"];
	$_surname=$_GET['su'];
	$_forename=$_GET['fo'];
	$_query=$_GET['query'];
	$_type=$_GET['type'];
?> 
<html xmlns="http://www.w3.org/1999/xhtml">
    <title>All Admin Comment</title><P class ="centre_blue">All comments</p>
    
    <body class = "LightGoldenRodYellow" >
        <BR>
        <form method="post" <?php echo "action='updatecomments.php?id=$_admission_id&type=$_type&query=$_query&su=$_surname&fo=$_forename'" ?>>
        <?php
			// Get all admnin comment of the student from the database and display
            if (!$link){$link=connectdb();}
            $query= "SELECT admin_comment_id, date_added, comment, dealt_with, sender
                        FROM admin_comment WHERE admission_id = $_admission_id ORDER BY date_added DESC;";
            $result= pg_query($query);
            $rows= pg_num_rows($result);
            $_box_count = 0;
            if ($rows>0){
                echo "<div align='center'></div><BR><BR><HR><HR>";
                echo "<BR></br>
                <TABLE BORDER=1>	
                    <tr>
                        <td><B>From</b></td>
                        <td><B>Time Posted</b></td>
                        <td><B>Comment</b></td>
                        <td><B>Dealt With</b></td>
                     </tr>
                ";
                for ($x=0;$x<$rows;$x++){
                    $arr=pg_fetch_array($result,$x);
					$_admin_comment_id = $arr[0];
                    $_date_added = $arr[1];
                    $_comment = $arr[2];
                    $_dealt_with = $arr[3];
                    $_sender = $arr[4];
                    echo "<tr><td>" . $_sender . "</td><td>" . $_date_added . "</td><td>" . $_comment . "</td>";
                    if($_dealt_with=='f'){
                        $_box_name = 'box'.$_box_count;
                        echo "<td align='middle'><input type='checkbox' name= '$_box_name'></td>";
                        $_comment_no_id = ''.$_box_count;
                        echo "<input type='hidden' name='$_comment_no_id' value='$_admin_comment_id'>";
                        $_box_count++;
                    } else {
						echo "<td align='middle'><img border='0' src='./icon/tick.gif'></td>";
					}
                    echo "</tr>";
                }
                echo "</table><BR><HR><HR><BR>";
            }
        ?>
        <!----------------------------Form to add new comment or tick a comment as dealt with --------->
        <input type="hidden" name="box_count" <?php echo "value =$_box_count"; ?>>
        <B>New Comment</b>
        <!-- <input type="text" size="60"  name="newcomment"> -->
        <textarea name= "newcomment" cols="40" rows="2"></textarea>
        <B>Dealt With</b> 
        <input  type="checkbox" name="new_tick_box"><BR></br>
        <input type="submit" value="Update Comment(s)">
        
        <HR>
            <div align="right">
                <?php
					// Bread Crum
                    if($admin){
                        echo "<a href='./main.php'>PhD Admission Portal</a>>";
                    }
                ?>
                <?php echo "<a href='./sortapp.php?type=$_type&query=$_query'>$_query Applicants</a>";?>
                ><?php echo "<a href='viewapplication.php?id=$_admission_id&query=$_query&type=$_type'> $_forename ($_surname)</a>";?>
                <b><i>All Comments</i></b>
            </div><BR>
    </body>
</html>