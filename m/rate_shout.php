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
if($_GET['postid'] == "")
{
	exit;
}
else
{
	$query = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);
	$mystring = $fetch['whorated'];
	$findme   = $User->UserInfo['id'];
	$pos = strpos($mystring, $findme);
	if($pos !== false)
	{
		mysql_query("UPDATE `social_feed` SET `whorated` = REPLACE(whorated, '".$findme.":','') WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
		exit;
		
	}
	mysql_query("UPDATE `social_feed` SET `whorated` = CONCAT(`whorated`, '".$User->UserInfo['id'].":') WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
	exit;
}
?>