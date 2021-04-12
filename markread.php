<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
ignore_user_abort(true);
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
else
{
	$User = new User();
}
if($_GET['type'] == "regular")
{
	mysql_query("UPDATE `social_notifications` SET `read`='1' WHERE `read`='0' AND `category`='1' AND `uid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
}
if($_GET['id'] != "")
{
	mysql_query("UPDATE `social_notifications` SET `read`='1' WHERE `id`='".mysql_real_escape_string($_GET['id'])."'") or die(mysql_error());
}
else
{
	header("Location: home");
	exit;
}
?>