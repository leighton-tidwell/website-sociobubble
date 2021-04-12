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
if($_GET['pid'] != "")
{
	$select = mysql_query("SELECT * FROM `social_comments` WHERE `pid`='".mysql_real_escape_string($_GET['pid'])."' AND `visible`='1'");
	$fetch = mysql_fetch_array($select);
	$count = mysql_num_rows($select);
	if($count != 0)
	{
		while(($comment = mysql_fetch_array($select)) != NULL)
		{
			$getinfo = $User->GetUserByID($comment['uid']);
			echo("<div id='comment-".$comment['id']."' class='postcommentitem'>");
				echo("<div class='commentprofpic'>");
					echo("<img src='p.php?src=".$getinfo['profile_picture']."&h=50&w=50' />");
				echo("</div>");
				echo("<div class='commentbody'>");
					echo("<a href='".$getinfo['username']."'>".$getinfo['first_name']." ".$getinfo['last_name']."</a> -- ".$comment['message']."");
				echo("</div>");
				echo("<div class='commenttime'>");
					echo("".$User->TimeMath($comment['timeposted'])."");
					echo("<div style='float:right;'>");
					if($getinfo['id'] == $User->UserInfo['id'])
					{
						echo("<a onclick='DeleteComment(".$comment['id'].");'>Delete</a>");
					}
					echo("</div>");
				echo("</div>");
			echo("</div>");
		}
		echo("<div class='postcommentform'>");
			echo("<div class='commentformtext'>");
				echo("Comment on this shout!<br />");
				echo("<form action='post_comment.php?pid=".$_GET['pid']."&dir=viewpost' method='post'>");
					echo("<input type='text' name='comment' class='comment'>");
					echo("&nbsp;-&nbsp;<input type='submit' value='Comment' class='comment_sub' name='submit'>");
				echo("</form>");
			echo("</div>");
		echo("</div>");
	}
	else
	{
		echo("There are no comments.");
		exit;
	}
}
else
{
	header("Location: home");
	exit;
}
?>