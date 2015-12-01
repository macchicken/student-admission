#!/usr/bin/php
<?php  
include "func/access.php";
$admin=check_admin();
if (!$admin){
header('Location: ./sortapp.php?type=status&query=pending');
return;

}

header('Location: ./main.php');


?>