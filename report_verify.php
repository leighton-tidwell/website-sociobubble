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
	header("location: index");
	exit;
}
if(!isset($_POST['reason']))
{
	header("location: report?feeditem=".$_GET['id']."&fail=true");
	exit;
}
if($_POST['comment'] == "")
{
	header("location: report?feeditem=".$_GET['id']."&fail=true");
	exit;
}
else
{
	$fquery = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($_GET['id'])."'") or die(mysql_error());
	$feedItem = mysql_fetch_array($fquery);
	mail("support@sociobubble.com","Shout Number ".mysql_real_escape_string($_GET['id'])." was reported","Hello,\n A shout has been reported and needs to be reviewed. \n Shout ID: ".mysql_real_escape_string($_GET['id'])." \n Shout Author ID = ".mysql_real_escape_string($feedItem['postedby'])." \n Time Posted = ".$feedItem['timeposted']." \n Reported by ID: ".$User->UserInfo['id']." \n Reason: ".mysql_real_escape_string($_POST['reason'])." \n Comments: ".mysql_real_escape_string($_POST['comment'])."\n Please review these soon!","From: Report System <noreply@sociobubble.com>");
	header("location: report?feeditem=".$_GET['id']."&fail=false");
	exit;
}
?>