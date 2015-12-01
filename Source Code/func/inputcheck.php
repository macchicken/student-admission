<?php
	function isNumerical($_input){
		$numericExpression = '^[0-9]+$';
		if (eregi($numericExpression,$_input)){
			return true;
		} else {
			return false;
		}
	}
	function isAlphabetical($_input){
		$alpha = '^[A-Za-z ]+$';
		if (eregi($alpha,$_input)){
			return true;
		} else {
			return false;
		}
	}
	function isAcademic($_input){
		$_command = "groups ".$_input;
		$_groups_list = exec($_command);
		if($_groups_list != NULL){
			return true;
		} else {
			return false;
		}
	}
?>