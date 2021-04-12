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
if(isset($_GET['pid']))
{
$query = mysql_query("SELECT * FROM `social_comments` WHERE `pid`='".mysql_real_escape_string($_GET['pid'])."'") or die(mysql_error());
$count = mysql_num_rows($query);
if($count < 1)
{
print "NO COMMENTS DUN DUN DUN!";
}
else
{
while(($fetch = mysql_fetch_array($query)) != NULL)
{
$tmp = $User->GetUserByID($fetch['uid']);
print $tmp['first_name']." ".$tmp['last_name']." -  ".$fetch['message'];
}
}
}
else
{
die("Post not found!");
}

?>