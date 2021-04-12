<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
if($_GET['uid'] == "" && $_GET['une'] == "")
{
	header("Location: home");
	exit;
}
else
{
		if($User->UserInfo['id'] != "")
		{
			mysql_query("UPDATE `social_users` SET `friendrequest`='" . . "' WHERE `id`='" .$_GET['uid']. "' AND `username`='" .$_GET['une']. "')") or die(mysql_error());
		}
}
?>