<?php
include "sqlconnect.php";
if($_POST['email_token'] == "")
{
	header("location: step2?code=bad");
	exit;
}
else
{		
}
$query = mysql_query("SELECT * FROM `social_users` WHERE `email_token`='".($_POST['email_token'])."' AND `active`='0'");
$fetch = mysql_fetch_array($query);
if($_POST['email_token'] == $fetch['email_token'])
{
	mysql_query("UPDATE `social_users` SET `active`='1' WHERE `email_token`='".($_POST['email_token'])."'");
	header("location: step3?token=".$_POST['email_token']."");
	exit;
}
else
{
	header("location: step2?fail=true");
	exit;
}
header("location: step3");
?>