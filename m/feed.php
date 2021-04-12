<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(isset($_SESSION['OK']))
{
	$User = new User();
}
else
{
	mysql_query("UPDATE `social_users` SET `online`='0' WHERE `email`='".mysql_real_escape_string($User->UserInfo['email'])."'") or die(mysql_error());
	session_destroy();
	echo("<div class='sessionexpired'>");
	echo("Sorry, your session has expired. Please click <a href='index'>here</a> to log in again.");
	echo("</div>");
	exit;
}
foreach($User->FriendsList as $friend)
{
 $querybuilder .= " OR `postedby`='".$friend."'";
}

$fatassquery = mysql_query("SELECT * FROM `social_feed` WHERE ( `postedby`='0' ".$querybuilder.") AND `visible`='1' ORDER BY `id` DESC LIMIT 0,10") or die(mysql_error());
$num = mysql_num_rows($fatassquery);
if($num < 1)
{
	echo("<div class='noshouts'>");
	echo("Looks like you have no shouts, type in the big white box above and click shout to get started!");
	echo("</div>");
	exit;
}
else
{
	while(($feedItem = mysql_fetch_array($fatassquery)) != NULL)
	{
		
		$author = $User->GetUserByID($feedItem['postedby']);
		$whohasrated = explode(":", $feedItem['whorated']);
		$ratecount = count($whohasrated) - 1;
		print "<script>CacheData(\"post-".$feedItem['id']."\", \"http://sociobubble.com/ratelist?postid=".$feedItem['id']."\");</script>";
		echo("<div class='feedItem' id=\"feeditem-".$feedItem['id']."\"'>");
		echo("<div class='title'>");
		if($author['profile_picture'] != "")
		{
			echo("<a href='" .$author['username']. "'><img class='feedprof_pic' src='http://sociobubble.com/".$author['profile_picture']."'></a>");
		}
		echo("<a href='" .$author['username']. "'>" . $author['first_name'] . " " . $author['last_name'] . "</a><div id='time_feed'>".$User->TimeMath($feedItem['timeposted'])."</div></div>");
		echo("<div class='body'>" .$User->AddEmo(str_replace("*-@", "<br />", $feedItem['shout'])). "");
		if($feedItem['img'] != "")
		{
			echo("<div class='feedimg'>");
			echo("<img src='http://sociobubble.com/".$feedItem['img']."'>");
			echo("</div>");
		}
		echo("</div>");
		echo("<div id='footerbar'></div>");
		echo("<div class='options'>");
		if($feedItem['mood'] != "")
		{
			echo("Mood:<font color='#FFF'> ".$feedItem['mood']."</font>&nbsp;|&nbsp;");
		}
		$query3 = mysql_query("SELECT * FROM `social_comments` WHERE `pid`='".mysql_real_escape_string($feedItem['id'])."' AND `visible`='1' ORDER BY `id` ASC") or die(mysql_error());
		$count2 = mysql_num_rows($query3);
		echo("<a onclick=\"javascript: RateShout(".$feedItem['id'].");\" style='cursor:pointer'\"><img src='images/thumsup.png'></a>&nbsp;|&nbsp;<font color='#fff'>Comment</font>");
		if($User->UserInfo['id'] == $feedItem['postedby'])
		{
			echo("<div class='trydagetleft'><a onclick=\"javascript: DeleteShout(".$feedItem['id'].");\" style='cursor:pointer'>Delete</a>&nbsp;</div>");
			echo("</div>");
			echo("</div>");
		}
		else
		{
		}
		echo("</div>");
		echo("</div>");
		print "<div style=\"position: absolute; background-color: #CCC; display: none; height: 0; width: 0;\" id=\"rater-list-".$feedItem['id']."\">Loading...</div>";
 }
}
?>
