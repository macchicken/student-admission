#!/usr/bin/php
<html xmlns="http://www.w3.org/1999/xhtml">
<body class = "LightGoldenRodYellow" >
<?php 
	include "func/access.php";
	$user=$_SERVER["REMOTE_USER"];
?>
<div align="right"><a href="./main.php">PhD Admission Portal</a></div>
<head>
	<link href="CSS/header.css" rel="stylesheet" type="text/css" />
	<link href="CSS/phd.css" rel="stylesheet" type="text/css" />
	<title>Supervisor Action</title><HR></hr>
	<P class ="centre_blue">Supervisor Actions</p> 
	
	<?php echo"<div align='middle'><a href='" . "http://www.doc.ic.ac.uk/csg/peoplefinder/?name=" . $user ."'<B>$user</B></a></div>"; ?>
    <p class ="s120"><P class = "left" ><a href="secure"></a> </p>
</head>

<BR></br>
<table   border="1" >
  <td ><B>View Applications</b> </td>
  <tr>
    
    <td  ><a href="sortapp.php?query=accepted&type=status">Accepted</a></td></tr>
    <tr><td ><a href="sortapp.php?query=pending&type=status">Pending</a></td></tr>
  
</table>
<BR></br>

</body>
</html><HR></hr><div align="right"><a href="./main.php">PhD Admission Portal</a></div>
