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
	exit;
}
$query = mysql_query("SELECT * FROM `social_mail` WHERE `id`='".$_GET['id']."'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
if($User->UserInfo['id'] != $fetch['from'])
{
	exit;
}
else
{
if($_GET['id'] == "")
{
	exit;
}
else
{
	mysql_query("UPDATE `social_mail` SET `visible`='0' WHERE `id`='".mysql_real_escape_string($_GET['id'])."'") or die(mysql_error());
	mysql_query("UPDATE `social_mail` SET `deletetime`='" .time(). "' WHERE `id`='".mysql_real_escape_string($_GET['id'])."'") or die(mysql_error());
	header("location: conversation.php?with=".mysql_real_escape_string($_GET['with'])."");
	exit;
}
}
?>