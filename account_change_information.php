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
$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
if($_POST['email'] != $fetch['email'])
{
	if($fetch['oldest_email'] == "")
	{
		mysql_query("UPDATE `social_users` SET `oldest_email`='" .$fetch['email']. "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
	}
	mysql_query("UPDATE `social_users` SET `email`='" .mysql_real_escape_string($_POST['email']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());;
}
if($_POST['city'] != $fetch['city'])
{
	mysql_query("UPDATE `social_users` SET `city`='" .mysql_real_escape_string($_POST['city']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}
if($_POST['bio'] != $fetch['bio'])
{
	mysql_query("UPDATE `social_users` SET `bio`='" .mysql_real_escape_string($_POST['bio']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}
if($_POST['country'] != $fetch['country'])
{
	mysql_query("UPDATE `social_users` SET `country`='" .mysql_real_escape_string($_POST['country']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}
if($_POST['state_region'] != $fetch['state_region'])
{
	mysql_query("UPDATE `social_users` SET `state_region`='" .mysql_real_escape_string($_POST['state_region']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}
if($_POST['occupation'] != $fetch['occupation'])
{
	mysql_query("UPDATE `social_users` SET `occupation`='" .mysql_real_escape_string($_POST['occupation']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}
if($_POST['website'] != $fetch['website'])
{
	mysql_query("UPDATE `social_users` SET `website`='" .mysql_real_escape_string($_POST['website']). "' WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
}


//upload  image


$valid_mime_types = array(
    "image/gif",
    "image/png",
    "image/jpeg",
    "image/pjpeg",
);
 $basestring = "il1234s4s3d4as5df67asdf78asdf89asgf9daf8dsad0gf7d8g6dsf6g4ds67fg68dshifuiaskjfb,3h2bjkh3g49q37yo4i3y24u91yoifadsflkjb,nabsfuo32y4u3y29q437823oyihgguk12tyg83e7iwgfroublskdhjeoawyr8e79t62873y4i3gyu29q86te78wfiudkbjsvh3bh3hie9a7s6edf76iagokyug32978adgfboausygd9f6te78wfiudkbjsvh3bh3hie9a7s6edf76iagokyug32978adgfboausygd9f6te78wfiudkbjsvh3bh3hie9a7s6edf76iagokyug32978adgfboausygd9foljsadkl";
// Check that the uploaded file is actually an image
// and move it to the right folder if is.
$file = $_FILES ['file'];
$name1 = $file ['name'];
$type = $file ['type'];
$ext = end(explode(".", $_FILES['file']['name']));
$size = $file ['size'];
$tmppath = $file['tmp_name'];
$shuffle = substr(str_shuffle($basestring), 0, 20);
$shuffle1 = substr(str_shuffle($basestring), 0, 25);
$Name = "" .$shuffle. "_" .time(). "_" .$shuffle1. ""; 
if($name1 != "")
{
	if($type == "image/png" || $type == "image/pjpeg" || $type == "image/gif" || $type == "image/jpeg")
	{
		if($ext == "png" || $ext == "jpg" || $ext == "gif" || $ext == "jpeg" || $ext == "JPG" || $ext == "PNG")
		{
		move_uploaded_file ($tmppath, 'accounts/'.$User->UserInfo['id'].'/profilepics/'.$Name.'.'.$ext);//image is a folder in which you will save image
		mysql_query("UPDATE `social_users` SET `profile_picture`='accounts/".$User->UserInfo['id']."/profilepics/".$Name.".".$ext."' WHERE `id`='".$User->UserInfo['id']."'");
		}
	}
}


header("Location: settings");
exit;
?>
