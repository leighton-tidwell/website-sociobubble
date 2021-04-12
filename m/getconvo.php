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
foreach($User->FriendsList as $Friend)
{
	$queryA = mysql_query("SELECT * FROM `social_mail` WHERE `userid`='".$Friend."' AND `from`='".$User->UserInfo['id']."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
	$queryB = mysql_query("SELECT * FROM `social_mail` WHERE `userid`='".$User->UserInfo['id']."' AND `from`='".$Friend."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
	
	if(mysql_num_rows($queryA) == 0 && mysql_num_rows($queryB) == 0)
	{
		// Do nothing
	}
	else
	{
		if(mysql_num_rows($queryA) != 0) { $fetchA = mysql_fetch_array($queryA); }
		if(mysql_num_rows($queryB) != 0) { $fetchB = mysql_fetch_array($queryB); }
		$FriendInfo = $User->GetUserByID($Friend);
		if((int)$fetchA['id'] > (int)$fetchB['id'])
		{
			if($fetchA['visible'] == "0")
			{
			echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' width='50px' src='http://sociobubble.com/".$FriendInfo['profile_picture']."'><a href='" .$FriendInfo[
			'username']. "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("<a href='conversation?with=".$Friend."'><div class='italic'>This message was removed.</div></a>");
			echo("<div id='convofoot'>");
			echo("Removed: ".$User->TimeMath($fetchA['deletetime'])."&nbsp;|&nbsp;Sent: ".$User->TimeMath($fetchA['time'])."");
			echo("</div>");
			echo("</div>");
			}
			else
			{
			echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' width='50px' src='http://sociobubble.com/".$FriendInfo['profile_picture']."'><a href='" .$FriendInfo['username']. "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("<a href='conversation?with=".$Friend."'>".$User->AddEmo(stripslashes($fetchA['message']))."</a>");
			echo("<div id='convofoot'>");
			echo("Sent: ".$User->TimeMath($fetchA['time'])."");
			echo("</div>");
			echo("</div>");
			}
		}
		else
		{
			if($fetchB['visible'] == "0")
			{
				echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' width='50px' src='http://sociobubble.com/".$FriendInfo['profile_picture']."'><a href='" .$FriendInfo['username']. "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("<a href='conversation?with=".$Friend."'><div class='italic'>This message was removed.</a></a>");
			echo("<div id='convofoot'>");
			echo("Removed: ".$User->TimeMath($fetchB['deletetime'])."&nbsp;|&nbsp; Recieved: ".$User->TimeMath($fetchB['time'])." ");
			echo("</div>");
			echo("</div>");
			}
			else
			{
			echo("<div id='convoitem' class='convoitem'>");
			echo("<img class='convopic' height='50px' width='50px' src='http://sociobubble.com/".$FriendInfo['profile_picture']."'><a href='" .$FriendInfo['username']. "'>".$FriendInfo['first_name']." ".$FriendInfo['last_name']."</a><br>");
			echo("<a href='conversation?with=".$Friend."'>".$User->AddEmo(stripslashes($fetchB['message']))."</a>");
			echo("<div id='convofoot'>");
			echo("Recieved: ".$User->TimeMath($fetchB['time'])."");
			echo("</div>");
			echo("</div>");
			}
		}
	}
}
?>
