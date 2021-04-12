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

$fatassquery = mysql_query("SELECT * FROM `social_feed` WHERE ( `postedby`='0' ".$querybuilder.") AND `visible`='1' ORDER BY `id` DESC LIMIT 0,30") or die(mysql_error());
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
		echo("<div class='feedItem' id=\"feeditem-".$feedItem['id']."\"'>");
		echo("<div class='title'>");
		if($author['profile_picture'] != "")
		{
			echo("<a href='" .$author['username']. "'><img class='feedprof_pic' src='p.php?src=".$author['profile_picture']."&w=40&h=40'></a>");
		}
		echo("<a href='" .$author['username']. "'>" . $author['first_name'] . " " . $author['last_name'] . "</a><div id='time_feed'>".$User->TimeMath($feedItem['timeposted'])."</div></div>");
		echo("<div class='body'>" .$User->AddEmo(str_replace("*-@", "<br />", $feedItem['shout'])). "");
		if($feedItem['img'] != "")
		{
			echo("<div class='feedimg'>");
			echo("<img src='".$feedItem['img']."'>");
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
		echo("<a onclick=\"javascript: RateShout(".$feedItem['id'].");\" style='cursor:pointer'\">Like</a>&nbsp;|&nbsp;<font color='#fff'><a onclick=\"javascript: ShowComments(".$feedItem['id'].");\" style=\"cursor:pointer;\">Comments</a></font>");
		if($User->UserInfo['id'] != $feedItem['postedby'])
		{
			echo("<div class='trydagetleft'><a href='report?feeditem=".$feedItem['id']."'>Report</a></div>");
		}
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
		echo("<div class='commentbox' style='display:none;' id=\"feeditem-".$feedItem['id']."\" name='".$feedItem['id']."'>");
		echo("<div class='comments'>");
		if($count2 < 1)
		{
			print "There are no comments. Be the first!";
		}
		else
		{
			while(($fetch2 = mysql_fetch_array($query3)) != NULL)
			{
				$tmp2 = $User->GetUserByID($fetch2['uid']);
				echo("<div class='commentitem' id='comment-".$fetch2['id']."'>");
				if($tmp2['profile_picture'] != "")
				{
					echo("<img class='commentprof_pic' src='p.php?src=".$tmp2['profile_picture']."&w=40&h=40'>");
				}
				echo("".$tmp2['first_name']." ".$tmp2['last_name']." -  ".$User->AddEmo($fetch2['message'])."<br /><div id='footerbar'></div>");
				echo("<font color='#FFFFFF'>".$User->TimeMath($fetch2['timeposted'])."</font>");
				if($User->UserInfo['id'] == $tmp2['id'])
				{
					echo("<div class='texttodaright'><a onclick='javascript: DeleteComment(".$fetch2['id'].");' style='cursor:pointer;'>Delete</a></div>");
				}
				else
				{
					echo("<br />");
				}
				echo("</div>");
			}
		}
		echo("</div>");
		echo("Comment on this shout!<br />");
		echo("<form action='post_comment.php?pid=".$feedItem['id']."' method='post'>");
		echo("<input type='text' name='comment' class='comment' onFocus='updatevar();' on onBlur='updatevar1();'>");
		echo("&nbsp;-&nbsp;<input type='submit' value='Comment' class='comment_sub' name='submit'>");
		echo("</form>");
		echo("</div>");
 }
}
?>