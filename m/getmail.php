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
$Friend = mysql_real_escape_string($_GET['with']);
$query = mysql_query("SELECT * FROM `social_mail` WHERE (`userid`='".$User->UserInfo['id']."' AND `from`='".$Friend."') OR (`userid`='".$Friend."' AND `from`='".$User->UserInfo['id']."') ORDER BY `id` DESC") or die(mysql_error());

if(mysql_num_rows($query) == 0)
{
	
}
else
{
	$FriendInfo = $User->GetUserByID($Friend);
	while(($fetch = mysql_fetch_array($query)) != NULL)
	{
		if($fetch['from'] == $User->UserInfo['id'])
		{
			if($fetch['visible'] == "0")
			{
				echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' src='".$User->UserInfo['profile_picture']."'><a href='" .$User->UserInfo['username']. "'>".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']."</a><br>");
			echo("<div class='italic'>This message was removed.</div>");
			echo("<div id='convofoot'>");
			echo("Removed: ".$User->TimeMath($fetch['deletetime'])."&nbsp;|&nbsp;Sent: ".$User->TimeMath($fetch['time'])."");
			echo("</div>");
			echo("</div>");
			}
			else
			{
			echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' src='".$User->UserInfo['profile_picture']."'><a href='" .$User->UserInfo['username']. "'>".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']."</a><br>");
			echo("".$User->AddEmo(stripslashes($fetch['message']))."");
			if($fetch['image'] != "")
			{
				echo("<div class='feedimg'>");
				echo("<img src='".$fetch['image']."'>");
				echo("</div>");
			}
			echo("<div id='convofoot'>");
			echo("Sent: ".$User->TimeMath($fetch['time'])."<div id='floatright'><a href='delete_message.php?id=".$fetch['id']."&with=".mysql_real_escape_string($_GET['with'])."'>Delete</a></div>");
			echo("</div>");
			echo("</div>");
			}
		}
		else
		{
			if($fetch['visible'] == "0")
			{
				echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' src='".$FriendInfo['profile_picture']."'><a href='" .$FriendInfo['username']. "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("<div class='italic'>This message was removed.</div>");
			echo("<div id='convofoot'>");
			echo("Removed: ".$User->TimeMath($fetch['deletetime'])."&nbsp;|&nbsp;Recieved: ".$User->TimeMath($fetch['time'])."");
			echo("</div>");
			echo("</div>");
			}
			else
			{
			echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' src='".$FriendInfo['profile_picture']."'><a href='" . $FriendInfo['username'] . "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("".$User->AddEmo(stripslashes($fetch['message']))."");
			if($fetch['image'] != "")
			{
				echo("<div class='feedimg'>");
				echo("<img src='".$fetch['image']."'>");
				echo("</div>");
			}
			echo("<div id='convofoot'>");
			echo("Recieved: ".$User->TimeMath($fetch['time'])."");
			echo("</div>");
			echo("</div>");
			}
		}
	}
}
?>