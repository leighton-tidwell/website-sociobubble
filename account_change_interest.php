<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
else
{
	$User = new User();
}
$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
if($_POST['interest'] != $fetch['interest'])
{ 
	mysql_query("UPDATE `social_users` SET `interest`='" .mysql_real_escape_string($_POST['interest']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
	header("location: settings");
	exit;
}
else
{
	header("location: settings");
	exit;
}
?>