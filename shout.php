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
if($_POST['shout'] == "")
{
	header("location: home");
	exit;
}
if($User->UserInfo['first_name'] == "")
{
	mysql_query("UPDATE `social_users` SET `sessionkey`='DEAD' WHERE `sessionkey`='".session_id()."'") or die(mysql_error());
	unset($_SESSION['OK']);
	session_set_cookie_params(0);
	session_destroy();
	setcookie("PHPSESSID", time()-3600);
	session_regenerate_id(true);
	header("location: index");
}
mysql_query("INSERT INTO `social_feed` (`shout`,`postedby`, `timeposted`, `mood`,`ipaddress`) VALUES ('".htmlspecialchars(str_replace("\n", "*-@", $_POST['shout']))."','".$User->UserInfo['id']."', '".time()."','".mysql_real_escape_string($_POST['mood'])."','".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."')") or die(mysql_error());
$file = $_FILES ['file'];
$User->UploadImage($file, "feed");

header("Location: home");
exit;
?>