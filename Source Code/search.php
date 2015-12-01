#!/usr/bin/php
<?php
	/* Script for searching registry number and surname through the database */
	$_searchfield= $_GET['searchfield'];
	$_searchfield=strtolower($_searchfield);
	$_searchfield=ucwords($_searchfield);
	$_searchfield=stripslashes($_searchfield);
	
	$numericExpression = '^[0-9]+$';
	$alpha='^[A-Za-z]+$';
	if (eregi($numericExpression,$_searchfield)){
	$_fieldtype="registry";}
	if (eregi($alpha,$_searchfield)){
	$_fieldtype="surname";}
	include "func/connect.php";
	if (!$link){$link=connectdb();}
	$query="SELECT * FROM phd_admissions WHERE ". $_fieldtype . "='$_searchfield';";
	$adm=pg_exec($link,$query);
	$rows=pg_num_rows($adm);
	if ($rows>0){$_header="Location: sortapp.php?query=" . $_searchfield."&type=".$_fieldtype;
		header($_header);	
	}
	
	else {
		echo"No such entry $_regno or application exists";
	}
?>
