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
if($_GET['type'] == "regular")
{
	$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='1' ORDER BY `id` DESC");
	if(mysql_num_rows($select) == 0)
	{
			echo("You have no new notifications!");
			exit;
	}
	while(($fetch = mysql_fetch_array($select)) != NULL)
	{
		if($fetch['read'] == 0){ 
			$tits = "style='background:#7ccd7c;'";
		}
		else
		{ 
			$tits = "";
		}
		$userinfo = $User->GetUserByID($fetch['from']);
		echo("<div ".$tits." class='notificationitem'>");
			echo("<div class='notificationpic'>");
				echo("<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50'>");
			echo("</div>");
			echo("<div class='notmessage'>");
				echo("<a href='".$fetch['link']."'>");
					echo($fetch['message']);
				echo("</a>");
			echo("</div>");
			echo("<div class='nottime'>");
				echo($User->TimeMath($fetch2['time']));
			echo("</div>");
		echo("</div>");
	}

}
elseif($_GET['type'] == "message")
{
	
	$select2 = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='2' ORDER BY `id` DESC");
	if(mysql_num_rows($select2) == 0)
	{
			echo("You have no new messages!");
			exit;
	}
	while(($fetch2 = mysql_fetch_array($select2)) != NULL)
	{
		if($fetch2['read'] == 0){ 
			$tits = "style='background:#7ccd7c;'";
		}
		else
		{ 
			$tits = "";
		}
		$userinfo = $User->GetUserByID($fetch2['from']);
		echo("<div ".$tits." class='notificationitem'>");
			echo("<div class='notificationpic'>");
				echo("<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50'>");
			echo("</div>");
			echo("<div class='notmessage'>");
				echo("<a href='".$fetch2['link']."' onClick=\"markread(".$fetch2['id'].");\">");
					echo(substr($fetch2['message'], 0, 30));
					if(strlen($fetch2['message']) > 30)
					{
						echo("...</a>");
					}
					else
					{
						echo("</a>");
					};
			echo("</div>");
			echo("<div class='nottime'>");
				echo($User->TimeMath($fetch2['time']));
			echo("</div>");
		echo("</div>");
	}

}
elseif($_GET['type'] == "friend")
{
	$select3 = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='3' AND `read`='0' ORDER BY `id` DESC");
	if(mysql_num_rows($select3) == 0)
	{
			echo("You have no new friend request!");
			exit;
	}
	while(($fetch3 = mysql_fetch_array($select3)) != NULL)
	{
		if($fetch3['read'] == 0){ 
			$tits = "style='background:#7ccd7c;'";
		}
		else
		{ 
			$tits = "";
		}
		$userinfo = $User->GetUserByID($fetch3['from']);
		echo("<div ".$tits." id='notificationitem-".$fetch3['id']."' class='notificationitem'>");
			echo("<div class='notificationpic'>");
				echo("<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50' />");
			echo("</div>");
			echo("<div class='notmessage'>");
				echo("<a href='".$fetch3['link']."' onClick=\"markread(".$fetch3['id'].");\">");
					echo($fetch3['message']);
				echo("</a>");
			echo("</div>");
			echo("<div class='nottime'>");
				echo($User->TimeMath($fetch2['time']));
			echo("</div>");
		echo("</div>");
	}

}
elseif($_GET['type'] == "count")
{
	$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `read`='0'");
	echo mysql_num_rows($select);
}
else
{
	header("Location: home");
	exit;
}
?>