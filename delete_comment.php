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
if($_GET['commentid'] == "")
{
	exit;
}
else
{	
	mysql_query("UPDATE `social_comments` SET `visible`='0' WHERE `id`='".mysql_real_escape_string($_GET['commentid'])."'") or die(mysql_error());
	exit;
}

?>