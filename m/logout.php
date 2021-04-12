<?php
include "sqlconnect.php";
session_start();
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
mysql_query("UPDATE `social_users` SET `sessionkey`='DEAD' WHERE `sessionkey`='".session_id()."'") or die(mysql_error());
unset($_SESSION['OK']);

header("location: index");
?>
