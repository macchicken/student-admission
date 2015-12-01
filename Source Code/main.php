#!/usr/bin/php
<!-- /////////////////////////////////////// Administrator main page///////////////////////////////////////////////////// -->
<!DOCTYPE html ml1-transitPUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtional.dtd">
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
	$user=$_SERVER['REMOTE_USER'];
	echo "User   " ."<b><i> $user </i></b>" . "   logged   " . " " ."   in";
?>
<!-- ///////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

<html xmlns="http://www.w3.org/1999/xhtml" >
	<title>PhD Admission Portal</title><head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
    
	<body class = "LightGoldenRodYellow" >
		<P class ="centre_blue">PhD Admissions Portal</p>
		<?php echo "<P class = 'centre_small'> - Welcome <b><i>$user</i></b></p>"?>
		<p class ="s120">Administrative actions
			<HR></hr>
			<!--
			<?php $trimmed = file('links.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                $rows=count($trimmed);
                echo"<table name='linksmenu' border=0 ><tr> ";
                for ($i=0;$i<$rows;($i=$i+2)){
                    $urlref=$trimmed[$i+1];
                    $linkname=$trimmed[$i];
                    echo"<td name='$linkname'><BR><a href='" . $urlref."'>". $linkname . "</a></td>";
                }
            ?><img border="0" align = "centre" src="icons/accepted.png">
            -->
            <a href ="editapp.php?e=addnew" ><H4>Add New Application</h4></a>
            <table border="0">
                <td onMouseOver="this.style.backroundcolour='#990000'">Filter Applications By </td>
              </tr>
              <tr>
                <td  ><a href="sortapp.php?query=accepted&type=status"><img border='0' src='icon/a_Accepted1.gif' /></a></td>
              </tr>
                <tr>
                <td ><a href="sortapp.php?query=pending&type=status"><img border='0' src='icon/a_pending1.gif' /></a></td>
              </tr>  
              <tr>
                <td ><a href="sortapp.php?query=rejected&type=status"><img border='0' src='icon/a_rejected1.gif' /></a></td>
              </tr>
              <tr>
                <td ><a href="sortapp.php?query=unclassified&type=status"><img border='0' src='icon/a_unclassified1.gif' /></a></td>
              </tr>
              <tr>
                <td ><a href="sortapp.php?query=deleted&type=status"><img border='0' src='icon/a_deleted1.gif' /></a></td>
              </tr>
            </table></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>

            <a href ="sortapp.php?query=Showall&type=status" >
            <H4>View all Applications</H4>
            </a> 

			<HR>

			<p class ="s150">Search By Registry number or Surname:</p>
            <form action="search.php" method="get">
            <input type='text' name='searchfield'/>
            <input type='submit' value='Search' />
            </form>
		</p>
	</body>
</html>