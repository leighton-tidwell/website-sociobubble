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
		$countdat = mysql_query("SELECT * FROM `social_notifications` WHERE `link`='http://sociobubble.com/viewpost?id=".mysql_real_escape_string($_GET['postid'])."' AND `from`='".mysql_real_escape_string($User->UserInfo['id'])."'");
		if(mysql_num_rows($countdat) > 0){
			mysql_query("DELETE FROM `social_notifications` WHERE `link`='http://sociobubble.com/viewpost?id=".$_GET['postid']."' AND `from`='".mysql_real_escape_string($User->UserInfo['id'])."'");
		}
		mysql_query("UPDATE `social_feed` SET `whorated` = REPLACE(whorated, '".$findme.":','') WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
		exit;
		
	}
	$selectuser = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($fetch['postedby'])."'");
	$fetchuser = mysql_fetch_array($selectuser);
	if($fetchuser['id'] != $User->UserInfo['id'])
	{
		$linkk = "http://sociobubble.com/viewpost?pid=".$_GET['postid']."";
		$messagee = "<a href='".$User->UserInfo['username']."'>".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']."</a> has liked <a href=".$linkk.">your post</a>.";
		$User->AddNotification($fetchuser['id'], $User->UserInfo['id'], $messagee, $linkk, 1);
	}
	mysql_query("UPDATE `social_feed` SET `whorated` = CONCAT(`whorated`, '".$User->UserInfo['id'].":') WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
	exit;
}
?>