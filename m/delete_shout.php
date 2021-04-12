<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(isset($_SESSION['OK']))
{
	$User = new User();
}
else
{
	mysql_query("UPDATE `social_users` SET `online`='0' WHERE `email`='".mysql_real_escape_string($_SESSION['email'])."'") or die(mysql_error());
	exit;
}
$query = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".$_GET['postid']."'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
if($User->UserInfo['id'] != $fetch['postedby'])
{
	exit;
}
else
{
if($_GET['postid'] == "")
{
	exit;
}
else
{
	mysql_query("UPDATE `social_feed` SET `visible`='0' WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
	exit;
}
}
?>