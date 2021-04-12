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

if(count($User->FriendsList) < 2)
{
	print "You have no friends, lets get started here!";
}
else
{
	$outputbuilder = array();
	foreach($User->FriendsList as $friend)
	{
		$fetch = $User->GetUserByID($friend);
		if($User->IsOnline($friend))
		{
			if($friend != $User->UserInfo['id'] && $fetch['first_name'] != "")
			{
				$outputbuilder[$fetch['first_name']] = "".$fetch['first_name']." ".$fetch['last_name']."<br />";
			}
		}
		else
		{
			if($friend != $User->UserInfo['id'] && $fetch['first_name'] != "")
			{
				$outputbuilder[$fetch['first_name']] = $fetch['first_name']." ".$fetch['last_name']."  <img height=\"10px\"src=\"images/online.png\" title='Online'><br />";
			}
		}
	}
	sort($outputbuilder, SORT_STRING);
	foreach($outputbuilder as $output)
	{
		print $output;
	}
}
?>
