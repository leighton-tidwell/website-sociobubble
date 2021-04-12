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
if($_POST['comment'] == "")
{
	header("location: home");
	exit;
}
mysql_query("INSERT INTO `social_comments` (`message`,`uid`, `pid`,`timeposted`,`ipaddress`) VALUES ('".htmlspecialchars(str_replace("\n", "*-@", $_POST['comment']))."','".$User->UserInfo['id']."', '".mysql_real_escape_string($_GET['pid'])."',  '".time()."','".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."')") or die(mysql_error());

$selectid = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($_GET['pid'])."'");
$fetchid = mysql_fetch_array($selectid);
$selectuser = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($fetchid['postedby'])."'");
$fetchuser = mysql_fetch_array($selectuser);
if($fetchuser['id'] != $User->UserInfo['id'])
{
	$messagee = "<a href='".$User->UserInfo['username']."'>".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']."</a> has commented on <a href='viewpost.php?pid=".$_GET['pid']."'>your post.</a>";
	$linkk = "http://sociobubble.com/viewpost?id=".$_GET['pid']."";
	$User->AddNotification($fetchuser['id'], $User->UserInfo['id'], $messagee, $linkk, 1);
}
if($_GET['dir'] != "viewpost")
{
	header("Location: home");
	exit;
}
else
{
	header("Location: viewpost.php?pid=".$_GET['pid']."");
	exit;
}
?>