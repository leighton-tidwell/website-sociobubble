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
if($_GET['minimize'] != "")
{
	if($_GET['convoid'] != "")
	{
		$zequery = mysql_query("SELECT * FROM `social_channelsettings` WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
		$fetchze = mysql_fetch_array($zequery);
		$convo = ":".$_GET['convoid'].":";
		$datshit = "".$fetchze['convosminimized']."".$convo."";
		if($_GET['minimize'] == "1")
		{
		mysql_query("UPDATE `social_channelsettings` SET `convosminimized`='".mysql_real_escape_string($datshit)."' WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
		}
		else
		{
			$convosminimized = $fetchze['convosminimized'];
			$test = str_replace($convo, '', $convosminimized);
			mysql_query("UPDATE `social_channelsettings` SET `convosminimized`='".mysql_real_escape_string($test)."' WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
		}
	}
}
if($_GET['close'] != "")
{
	if($_GET['convoid'] != "")
	{
		$zequery = mysql_query("SELECT * FROM `social_channelsettings` WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
		$fetchze = mysql_fetch_array($zequery);
		$convo = ":".$_GET['convoid'].":";
		$datshit = "".$fetchze['convosopen']."".$convo."";
		if($_GET['close'] == "1")
		{
			$convosclosed = $fetchze['convosopen'];
			$test = str_replace($convo, '', $convosclosed);
			mysql_query("UPDATE `social_channelsettings` SET `convosopen`='".mysql_real_escape_string($test)."' WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE `social_channelsettings` SET `convosopen`='".mysql_real_escape_string($datshit)."' WHERE `id`='".mysql_real_escape_string($_GET['convoid'])."'") or die(mysql_error());
		}
	}
}
?>