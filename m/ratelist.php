<?php
require_once "sqlconnect.php";
require_once "Class.User.php";
session_start();
if($_SESSION['OK'])
{
	$User = new User();
}
else
{
	die("Your session has biten the dust");
}
$query = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($_GET['postid'])."'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
$whom = explode(":", $fetch['whorated']);
if(count($whom) > 1)
{
	foreach($whom as $who)
	{
		$tmp = $User->GetUserByID($who);
		if($tmp['first_name'] != "")
		{
			print $tmp['first_name']." ".$tmp['last_name'].", ";
		}
	}
	print "Rated this post";
}
else
{
	print "Nobody has rated this post, Be the first";
}
?>