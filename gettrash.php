<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(!isset($_SESSION['OK']))
{
	mysql_query("UPDATE `social_users` SET `online`='0' WHERE `email`='".mysql_real_escape_string($User->UserInfo['email'])."'") or die(mysql_error());
	session_destroy();
	echo("<div class='sessionexpired'>");
	echo("Sorry, your session has expired. Please click <a href='index'>here</a> to log in again.");
	echo("</div>");
	exit;
}
else
{
 $User = new User();
}

$query = mysql_query("SELECT * FROM `social_mail` WHERE `from`='".$User->UserInfo['id']."' AND `visible`='0' ORDER BY `id` DESC") or die(mysql_error());
$num = mysql_num_rows($query);
if($num < 1)
{
	echo("No trash YAY!");
}
else
{
	while(($fetch = mysql_fetch_array($query)) != NULL)
	{
		$tmp = $User->GetUserByID($fetch['userid']);
		echo("<div id='sentitem'>");
		echo("".$fetch['message']."<br>");
			echo("<div id='convofoot'>");
			echo("Sent to: ".$tmp['first_name']." ".$tmp['last_name']."&nbsp;|&nbsp;Removed: ".$User->TimeMath($fetch['deletetime'])."&nbsp;|&nbsp;Sent: ".$User->TimeMath($fetch['time'])."");
			echo("</div>");
		echo("</div>");
	}
}
?>