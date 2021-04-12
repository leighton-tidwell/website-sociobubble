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
$id = $_GET['id'];
$mid = $User->UserInfo['id'];
if($id != "")
{
	$from = $User->UserInfo['id'];
	$message = "".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']." has accepted your friend request.";
	$link = "http://sociobubble.com/".$User->UserInfo['username']."";
	$User->AddNotification($id, $from, $message, $link, 3); 
	$User->AcceptRequest($id, $mid);
	header("Location: home");
	exit;
}
else
{
	header("Location: home");
	exit;
}
?>