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
if($_POST['current'] != "")
{
	$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);
	if($_POST['new'] != "")
	{
		if($_POST['re'] != "")
		{
			if(md5($_POST['current']) == $fetch['password'])
			{
				if($_POST['new'] == $_POST['re'])
				{
					mysql_query("UPDATE `social_users` SET `password`='" .md5($_POST['new']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
					header("Location: settings?pass=changed");
					exit;
				}
				else
				{
					header("Location: settings?match=no");
					exit;
				}
			}
			else
			{
				header("Location: settings?current=bad");
				exit;
			}
		}
		else
		{
			header("Location: settings?filled=false");
			exit;
		}
	}
	else
	{
		header("Location: settings?filled=false");
		exit;
	}
}
else
{
	header("Location: settings?filled=false");
	exit;
}
?>