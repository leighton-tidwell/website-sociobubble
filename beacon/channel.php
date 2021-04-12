<?php
set_time_limit(0);
$calltime = time();
/*
 * Beacon logic system
 * Written by Mark Ross
 * 
 * This acts as a channel system - Implementing the reversed enginered Facebook System
 */
 

/* Unlike facebook this implementation is Bi-Directinal - When updates are requested by the client
 * It will also send in infomation. So When a chat message is sent infomation will flow through
 * this channel and they will receive infomation back instead of just a meaningless confirm.
 */
 
// Wont continue to if there is no valid request - Don't want to waste resources opening a Mysql
// connection

if(!isset($_GET['usingepoch']) && !isset($_GET['unique']) && !isset($_GET['time']))
{
	print
	"{ \"error\": \"Invalid request\", \"message\":\"Your request is invalid - The real site should not be doing this.\", \"epoch\": \"".time()."\" }";
	exit;
}
else
{
	// Yes I know that clients will most likely have time zones that are diffrent than the server
	// That's why beaconinit.php will be called to get the time from the server and client will
	// update it them selfs - The channel will keep it in sync - Drop the request if more than 5
	// minutes out of sync.
	if($_GET['time'] < (time() - 30))
	{
		print
		"{ \"error\": \"Out of Sync\", \"message\":\"You are out of Sync, Over 30 seconds Delta\", \"alert\": \"You may want to refresh\", \"epoch\": \"".time()."\" }";
		exit;
	}
}

// Basic includes
include "../sqlconnect.php";
include "../Class.User.php";
session_start();
if(isset($_SESSION['OK']))
{
	$User = new User();
}
else
{
	print "{ \"error\": \"Out of Sync\", \"message\":\"You are out of Sync.\", \"alert\": \"You may want to refresh\", \"navigate\":\"./index\", \"epoch\": \"".time()."\" }";
	exit;
}

// Let's gather some stats - Making sure that we do not respond to old  requests on the off chance
// Receive them out of order/twice

$query = mysql_query("SELECT * FROM `social_channelrequests` WHERE `unique`='".mysql_real_escape_string($_GET['unique'])."'") or 
	die( "{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".
	mysql_error()."\", \"epoch\":\"".time()."\" }");
$num = mysql_num_rows($query);
if($num > 0)
{
	print
	"{ \"error\": \"Double\", \"message\":\"You are out of Sync got a double.\", \"alert\": \"You may want to refresh\", \"epoch\": \"".time()."\" }";
	exit;
}

mysql_query("INSERT INTO `social_channelrequests` (`unique`) VALUES ('".mysql_real_escape_string($_GET['unique'])."')") or
die( "{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");

// NO TOUCHY! >>>
$add = 0;
$remove = 0;
$update = 0;
$append = 0;
$prepend = 0;
// <<<

// All valid so we may process the request now

/**
 * Request Handler
 */

if($_REQUEST['request'])
{
	$request = json_decode(stripslashes($_REQUEST['request']), true);
	if(isset($request['feed']))
	{
		$loadmore = true;
		$feedcount = (int)$request['feed']['count'] * 30;
	}
}

foreach($User->FriendsList as $friend)
{
 $querybuilder .= " OR `postedby`='".$friend."'";
}

/*
	Here we will start loading notifications. 
	Were going to start by loading the regular ones.
*/
$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='1' ORDER BY `id` DESC")
or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
if(mysql_num_rows($select) == 0)
{
		$response['notifcations']['regular']['value'] .= "You have no new notifications!";
}
while(($fetch = mysql_fetch_array($select)) != NULL)
{
	if($fetch['read'] == 0)
	{
		$tits = "style='background:#7ccd7c;'";
	}
	else
	{
		$tits = "";
	}

	$userinfo = $User->GetUserByID($fetch['from']);
	$response['notifcations']['regular']['value'] .= 
	"<div ".$tits." class='notificationitem'>".
		"<div class='notificationpic'>".
			"<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50'>".
		"</div>".
		"<div class='notmessage'>".
			"<a href='".$fetch['link']."'>".
			$fetch['message'].
				"</a>".
		"</div>".
		"<div class='nottime'>".
			$User->TimeMath($fetch2['time']).
			"</div>".
		"</div>";
}

/*
	Now we can load the friend request...
*/

$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='3' ORDER BY `id` DESC")
or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
if(mysql_num_rows($select) == 0)
{
		$response['notifcations']['friends']['value'] .= "You have no friend request!";
}
while(($fetch = mysql_fetch_array($select)) != NULL)
{
	if($fetch['read'] == "0"){
	$userinfo = $User->GetUserByID($fetch['from']);
	$response['notifcations']['friends']['value'] .= 
	"<div ".$tits." class='notificationitem'>".
		"<div class='notificationpic'>".
			"<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50'>".
		"</div>".
		"<div class='notmessage'>".
			"<a href='".$fetch['link']."'>".
			$fetch['message'].
				"</a>".
		"</div>".
		"<div class='nottime'>".
			$User->TimeMath($fetch2['time']).
			"</div>".
		"</div>";
	}
	else{
		$response['notifcations']['friends']['value'] .= 
	"You have no friend request!";
	}
}

/*
	And lastly, the messages.
*/

$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `category`='2' ORDER BY `id` DESC")
or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
if(mysql_num_rows($select) == 0)
{
		$response['notifcations']['messages']['value'] .= "You have no messages!";
}
while(($fetch = mysql_fetch_array($select)) != NULL)
{
	if($fetch['read'] == 0)
	{
		$tits = "style='background:#7ccd7c;'";
	}
	else
	{
		$tits = "";
	}
	$userinfo = $User->GetUserByID($fetch['from']);
	$response['notifcations']['messages']['value'] .= 
 "<div ".$tits." class='notificationitem'>".
  "<div class='notificationpic'>".
   "<img src='p.php?src=".$userinfo['profile_picture']."&w=50&h=50'>".
  "</div>".
  "<div class='notmessage'>".
   "<a href='".$fetch2['link']."' onClick=\"markread(".$fetch2['id'].");\">".
   substr($fetch['message'], 0, 30);

   if(strlen($fetch['message']) > 30)
     {
      $response['notifcations']['messages']['value'] .= "...</a>";
     }
     else
     {
      $response['notifcations']['messages']['value'] .= "</a>";
     }
  $response['notifcations']['messages']['value'] .= "</div>".
  "<div class='nottime'>".
   $User->TimeMath($fetch2['time']).
   "</div>".
  "</div>";
}

//Lets run a query to get the unread notification count.
$select = mysql_query("SELECT * FROM `social_notifications` WHERE `uid`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `read`='0'")
or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
$response['notifcations']['count'] = mysql_num_rows($select);
$fetch = mysql_fetch_array($query);

/*
=====================
====   End Nots    ==
=====================
*/


/**
 * Doing all the Feed crap under here
 */


// Let's give these vars some offensive names
if($_GET['lastreq'] == "0")
{
	$jimsfix = "LIMIT 0,30";
	$bluewaffle = "";
	$response['remove'][$remove]['tag'] = "img#loadgif";
	$remove++;
	$state = "append";
}
else
{
	if($loadmore)
	{
		$jimsfix = "LIMIT ". mysql_real_escape_string($feedcount) .",". mysql_real_escape_string($feedcount + 30);
		$state = "append";
	}
	else
	{
		$jimsfix = "";
		$bluewaffle = " `timeposted` > '".mysql_real_escape_string($_GET['lastreq'])."' AND";
		$state = "prepend";
	}
}
$fatassquery = mysql_query("SELECT * FROM `social_feed` WHERE ( `postedby`='0' ".$querybuilder.") AND".$bluewaffle." `visible`='1' ORDER BY `id` DESC ".$jimsfix) or die(mysql_error());
$num = mysql_num_rows($fatassquery);
if($num < 1)
{
	if($_GET['lastreq'] == "0")
	{
		$response['update'][$update]['tag'] = "div#feed";
		$response['update'][$update]['value'] = "<div class='noshouts'>".
		"Looks like you have no shouts, type in the big white box above and click shout to get started!".
		"</div>";
		$update++;
	}
	else if($loadmore)
	{
		//$response['alert'] = "poop";
		$response['update'][$update]['tag'] = "div#loadmore";
		$response['update'][$update]['value'] = "<a style='cursor:pointer;' onClick='loadmoreyoufucker();'>No More Shouts</a>";
		$update++;
  
	}
}
else
{
	while(($feedItem = mysql_fetch_array($fatassquery)) != NULL)
	{
		$response[$state][$append]['tag'] = "div#feed";
		$author = $User->GetUserByID($feedItem['postedby']);
		$whohasrated = explode(":", $feedItem['whorated']);
		$ratecount = count($whohasrated) - 1;
		$response[$state][$append]['value'] .= "<div class='feedItem' id='feeditem-".$feedItem['id']."'>";
		$response[$state][$append]['value'] .="<div class='title'>";
		if($author['profile_picture'] != "")
		{
			$response[$state][$append]['value'] .= 
			"<a href='" .$author['username']. "'><img class='feedprof_pic' src='p.php?src=".$author['profile_picture']."&w=40&h=40'></a>";
		}
		$response[$state][$append]['value'] .=
		"<a href='" .$author['username']. "'>" . $author['first_name'] . " " . $author['last_name'] . "</a><div id='time_feed'>".$User->TimeMath($feedItem['timeposted'])."</div></div>";
		if(get_magic_quotes_gpc())
		{
		   $feedItem['shout'] = stripslashes($feedItem['shout']);
		}
		$response[$state][$append]['value'] .=
		"<div class='body'>" .$User->AddEmo(str_replace("*-@", "<br />", $feedItem['shout']));
		if($feedItem['img'] != "")
		{
			
			$response[$state][$append]['value'] .= "<div class='feedimg'>".
			"<a class='feedimage' data-title-id='feedtitle-".$feedItem['id']."' rel='fancybox-thumb' href='".$feedItem['img']."'>".
			"<img src='";
			list($width, $height) = getimagesize("http://sociobubble.com/".$feedItem['img']."");
			if($width > 709){
				$response[$state][$append]['value'] .= "p.php?src=".$feedItem['img']."&w=709";
			}
			else
			{
				$response[$state][$append]['value'] .= "".$feedItem['img']."";
			}
			$response[$state][$append]['value'] .= "'>".
			"</a>".
			"</div>";
		}
		$response[$state][$append]['value'] .= "</div>".
		"<div id='footerbar'></div>";
		$getlikers = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($feedItem['id'])."'") or die(mysql_error());
		$fetchlikers = mysql_fetch_array($getlikers);
		$whom = explode(":", $fetchlikers['whorated']);
		if(in_array($User->UserInfo['id'], $whom, true))
		{
				$response[$state][$append]['value'] .= "<div id='like-list-".$feedItem['id']."' style='display:inherit;' class=\"like-list\">";
		}
		else
		{
			$response[$state][$append]['value'] .= "<div id='like-list-".$feedItem['id']."' style='display:none;' class=\"like-list\">";
		}
		foreach($whom as $who)
		{
			$tmp = $User->GetUserByID($who);
			if($tmp['first_name'] != "" || $who != "")
			{
				$response[$state][$append]['value'] .= "<a href=\"".$tmp['username']."\" >".$tmp['first_name']." ".$tmp['last_name']."</a>, ";
			}
		}
		$response[$state][$append]['value'] .= "liked this post.</div>".
		"<div class='options'>";
		if($feedItem['mood'] != "")
		{
			$response[$state][$append]['value'] .= "Mood:<font color='#FFF'> ".$feedItem['mood']."</font>&nbsp;|&nbsp;";
		}
		$query3 = mysql_query("SELECT * FROM `social_comments` WHERE `pid`='".mysql_real_escape_string($feedItem['id'])."' AND `visible`='1' ORDER BY `id` ASC") or die(mysql_error());
		$count2 = mysql_num_rows($query3);
		$response[$state][$append]['value'] .=
		"<a onclick=\"javascript: showcomments(".$feedItem['id'].",'".$User->UserInfo['first_name']." ".$User->UserInfo['last_name'].", ');\" style='cursor:pointer'\">Like</a>&nbsp;|&nbsp;<font color='#fff'><a onclick=\"javascript: ShowComments(".$feedItem['id'].");\" style=\"cursor:pointer;\">Comments</a></font>";
		if($User->UserInfo['id'] != $feedItem['postedby'])
		{
			$response[$state][$append]['value'] .= "<div class='trydagetleft'><a href='report?feeditem=".$feedItem['id']."'>Report</a></div>";
		}
		if($User->UserInfo['id'] == $feedItem['postedby'])
		{
			$response[$state][$append]['value'] .=
			"<div class='trydagetleft'><a onclick=\"javascript: DeleteShout(".$feedItem['id'].");\" ".
			"style='cursor:pointer'>X</a>&nbsp;</div>";
		}
		$response[$state][$append]['value'] .= "</div>".
		"</div></div></div>".
		"<div class='commentbox' style='display:none;' id=\"feeditem-".$feedItem['id']."\" name='".$feedItem['id']."'>".
		"<div class='comments'>";
		if($count2 < 1)
		{
			$response[$state][$append]['value'] .= "There are no comments. Be the first!";
		}
		else
		{
			while(($fetch2 = mysql_fetch_array($query3)) != NULL)
			{
				$tmp2 = $User->GetUserByID($fetch2['uid']);
				$response[$state][$append]['value'] .= "<div class='commentitem' id='comment-".$fetch2['id']."'>";
				if($tmp2['profile_picture'] != "")
				{
					$response[$state][$append]['value'] .= 
					"<img class='commentprof_pic' src='p.php?src=".$tmp2['profile_picture']."&w=40&h=40'>";
				}
				$response[$state][$append]['value'] .= 
				$tmp2['first_name']." ".$tmp2['last_name']." -  ".$User->AddEmo($fetch2['message'])."<br /><div id='footerbar'></div>";
				$response[$state][$append]['value'] .= "<font color='#FFFFFF'>".$User->TimeMath($fetch2['timeposted'])."</font>";
				if($User->UserInfo['id'] == $tmp2['id'])
				{
					$response[$state][$append]['value'] .= 
					"<div class='texttodaright'><a onclick='javascript: DeleteComment(".$fetch2['id'].");' ".
					"style='cursor:pointer;'>Delete</a></div>";
				}
				else
				{
					$response[$state][$append]['value'] .= "<br />";
				}
				$response[$state][$append]['value'] .= "</div>";
			}
		}
		$author = $User->GetUserByID($feedItem['postedby']);
		$response[$state][$append]['value'] .= "</div>".
		"Comment on this shout!<br />".
		"<form action='post_comment.php?pid=".$feedItem['id']."' method='post'>".
		"<input type='text' name='comment' class='comment' onFocus='updatevar();' on onBlur='updatevar1();'>".
		"&nbsp;-&nbsp;<input type='submit' value='Comment' class='comment_sub' name='submit'>".
		"</form>".
		"</div>".
		"<div style='display:none;' id='feedtitle-".$feedItem['id']."'>".
		"<a href='".$author['username']."'><img style='float:left;border-top-left-radius:5px;border-bottom-left-radius:5px;' src='p.php?src=".$author['profile_picture']."&w=50&h=50'></a>".
		"<span style='margin-left:1px;float:left;font-size:12px;width:75%;text-align:left;'><a style='color:#fff;text-decoration:none;' href='".$author['username']."'>".$author['first_name']." ".$author['last_name']."</a></span>".
		"<span style='margin-left:4px;float:left;font-size:10px;width:75%;text-align:left;'>".$feedItem['shout']."</span>".
		"</div>";
		$append++;
	}
}
/*
=====================
====   End Feed    ==
=====================
*/


/**
 * CHAT BULLSHIT
 */

$chatquery = mysql_query("SELECT * FROM `social_convos` WHERE `people` LIKE '%\"".$User->UserInfo['id']."\"%'") or
 die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
 if($_GET['lastreq'] == "0")
 {
	$response['append'][$append]['tag'] = "body";
	$response['append'][$append]['value'] = "<div id=\"chatcontainer\" class=\"chatcontainer\"></div>";
	$append++;
 }

while(($row = mysql_fetch_array($chatquery)) != NULL)
{
	$zequery = mysql_query("SELECT * FROM `social_channelsettings` WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
		$fetchze = mysql_fetch_array($zequery);
		$convos = explode(":", $fetchze['convosopen']);
		if(in_array($row['id'], $convos, true))
		{
	if($row['time'] > (time() - 4) || $_GET['lastreq'] == "0")
	{
		if (get_magic_quotes_gpc()) {
			$tittiehumans = stripslashes($row['people']);
		}
		else
		{
			$tittiehumans = $row['people'];
		}
		$peeps = json_decode($tittiehumans,true);
		
		$i = 0;
		
		foreach($peeps['clients'] as $value)
		{
			$countdis = count($peeps['clients']);
			$tmp = $User->GetUserByID($value);
			if($countdis == 2){
				if($i == 0){
					if($value == $User->UserInfo['id']){
						$i++;
					}
					else
					{
						$title .= "<a href=\"".$tmp['username']."\">".$tmp['first_name']." ".$tmp['last_name']."</a>";
						$i++;
					}
				}
				else
				{
					if($value == $User->UserInfo['id']){
					}
					else
					{
						$title .= "<a href=\"".$tmp['username']."\">".$tmp['first_name']." ".$tmp['last_name']."</a>";
					}
				}
			}
			if($countdis > 2)
			{
				$countdis2 = $countdis - 1;
				if($i != $countdis2)
				{
					if($value == $User->UserInfo['id']){
						$title .= "Me, ";
						$i++;
					}
					else
					{
						$title .= "<a href=\"".$tmp['username']."\">".$tmp['first_name']."</a>";
						$title .= ", ";
						$i++;
					}
				}
				else
				{
					if($value == $User->UserInfo['id']){
						$title .= ", and I";
						$i++;
					}
					else
					{
						$title .= "and ";
						$title .= "<a href=\"".$tmp['username']."\">".$tmp['first_name']."</a>";
						$i++;
					}
				}
			}
		}
		$response['append'][$append]['tag'] = "div#chatcontainer";
		$response['append'][$append]['value'] .= 
			"<div id=\"chatbox_".$row['id']."\" class=\"chatbox\" style='display: block;'>".
			"<div onclick=\"\" id=\"chathead_".$row['id']."\" class=\"chatboxhead\">".
			"<script>showchatscroll(".$row['id'].");</script>".
			"<div class=\"chatboxtitle\">".$title."</div>".
			"<div class=\"chatboxoptions\">".
			"<img height='18px' src='images/icons/50px/wrench.png'>".
			"<a id=\"minmaxize_".$row['id']."\" onclick=\"javascript: minimizechat(".$row['id'].")\">-</a> ".
			"<a onclick=\"closechat(".$row['id'].")\">X</a>".
			"</div>".
			"<br clear=\"all\">".
			"</div>";
			$zequery = mysql_query("SELECT * FROM `social_channelsettings` WHERE `userid`='".mysql_real_escape_string($User->UserInfo['id'])."'") or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
			$fetchze = mysql_fetch_array($zequery);
			$convos = explode(":", $fetchze['convosminimized']);
			if(in_array($row['id'], $convos , true))
			{
					$response['append'][$append]['value'] .= "<script>minimizechat(".$row['id'].");</script>";
			}
			$response['append'][$append]['value'] .= "<div id='chatwrap_".$row['id']."'>".
			"<div id='chatboxcontent_".$row['id']."' class=\"chatboxcontent\">";
			$chatmessages = mysql_query("SELECT * FROM `social_convos_messages` WHERE `convoid`='".mysql_real_escape_string($row['id'])."'") or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
			while(($message = mysql_fetch_array($chatmessages)) != NULL)
			{
				if($message['from'] == $User->UserInfo['id']){
					$response['append'][$append]['value'] .= "<div class=\"message_wrap\">".
					"<div class=\"message_right\" id=\"message_".$message['id']."\">".
					"<div class=\"arrow bottom right\"></div>".
					"".$message['message']."".
					"<div class=\"messagetime\">".
					"".$User->TimeMath($message['time'])."".
					"</div>".
					"</div>".
					"</div>";					
				}
				else
				{
					$response['append'][$append]['value'] .= "<div class=\"message_wrap\">".
					"<div class=\"message_left\" id=\"message_".$message['id']."\">".
					"<div class=\"arrow bottom2 left\"></div>".
					"".$message['message']."".
					"<div class=\"messagetime\">".
					"".$User->TimeMath($message['time'])."".
					"</div>".
					"</div>".
					"</div>";
				}
			}
			$response['append'][$append]['value'] .="</div>".
			"<div class=\"chatboxinput\">".
			"<form style='margin:0px;padding:0px;' id=\"sendmesform_".$row['id']."\">".
			"<textarea id=\"sendmessage_".$row['id']."\" class=\"chatboxtextarea\"></textarea>".
			"</form>".
			"</div>".
			"</div>".
			"<script>Registerchatbox('textarea#sendmessage_".$row['id']."');</script>";
			$append++;
		$title = "";
		}
	}
}

/*
=====================
====   End Chat    ==
=====================
*/


/*
	Were now going to load in the friends list.
	For now we are only going to load in the friends that are online.
	
	Later we will add features such as being able to sort by those that are:<br />
	> Online
	> Offline
	> All
	> Favorites
	
	And anymore categories that we see fit.
*/

if(count($User->FriendsList) < 2)
{
	$response['update'][$update]['tag'] = "div#friendsfeed";
	$response['update'][$update]['value'] .= "You have no friends, lets get started here!";
	$update++;
}
else
{
	$outputbuilder = array();
	foreach($User->FriendsList as $friend)
	{
		$fetch = $User->GetUserByID($friend);
		if($User->IsOnline($friend))
		{
			/*
			if($friend != $User->UserInfo['id'] && $fetch['first_name'] != "")
			{
				$outputbuilder[$fetch['first_name']] = "".$fetch['first_name']." ".$fetch['last_name']."<br />";
			}
			*/
		}
		else
		{
			if($friend != $User->UserInfo['id'] && $fetch['first_name'] != "")
			{
				$outputbuilder[$fetch['first_name']] = "<a onclick=\"openchat(".$fetch['id'].");\"".$fetch['first_name']." ".$fetch['last_name']."</a>  <img height=\"10px\"src=\"images/online.png\" title='Online'><br />";
			}
		}
	}
	sort($outputbuilder, SORT_STRING);
	foreach($outputbuilder as $output)
	{
		$response['update'][$update]['tag'] = "div#friendsfeed";
		$response['update'][$update]['value'] .= $output;
	}
	$response['remove'][$remove]['tag'] = "img#loadgif2";
	$remove++;
	$update++;
}

/*
=====================
====   End flist   ==
=====================
*/



/**
 * Because I am awesome let's send messages!
 */

if(isset($request['chatbox']))
{
	mysql_query("INSERT `social_convos_messages` (`convoid`, `message`, `from`,`ip`, `time`) VALUE('".
	mysql_real_escape_string($request['chatbox'])
	."', '".mysql_real_escape_string($request['chatmessage'])."', '".
	mysql_real_escape_string($User->UserInfo['id'])."', '".$_SERVER['REMOTE_ADDR']."', '".time()."')");
	$chatto = mysql_query("SELECT * FROM `social_convos` WHERE `id`='".mysql_real_escape_string($request['chatbox'])."'");
	$fetchabove = mysql_fetch_array($chatto);
	if (get_magic_quotes_gpc()) {
		$tittiehumans = stripslashes($fetchabove['people']);
	}
	else
	{
		$tittiehumans = $fetchabove['people'];
	}
	$peeps = json_decode($tittiehumans,true);
	foreach($peeps['clients'] as $titties)
	{
		if($titties != $User->UserInfo['id'])
		{
			$User->AddNotification($titties, $User->UserInfo['id'], "<a href='".$User->UserInfo['username']."'>".$User->UserInfo['first_name']." ".$User->UserInfo['last_name']."</a> has sent you a <a href='#'>chat message</a>", "", "1");
		}
	}
}

$chatquery = mysql_query("SELECT * FROM `social_convos` WHERE `people` LIKE '%\"".$User->UserInfo['id']."\"%'") or
 die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");

while(($arr = mysql_fetch_array($chatquery)) != NULL)
{

	$chatmessages = mysql_query("SELECT * FROM `social_convos_messages` WHERE `convoid`='".mysql_real_escape_string($arr['id'])."'") or die("{ \"error\": \"Mysql error\", \"message\":\"Server encountered a mysql error\", \"alert\": \"".mysql_error()."\", \"epoch\":\"".time()."\" }");
	
	while(($message = mysql_fetch_array($chatmessages)) != NULL)
	{
	if((int)$message['time'] > $calltime - 4)
	{
		$response['append'][$append]['tag'] = "div#chatboxcontent_" . $arr['id'];
		if($message['from'] == $User->UserInfo['id'])
		{
			$response['append'][$append]['value'] .= "<div class=\"message_wrap\">".
				"<div class=\"message_right\" id=\"message_".$message['id']."\">".
				"<div class=\"arrow bottom right\"></div>".
				"".$message['message']."".
				"<div class=\"messagetime\">".
				"".$User->TimeMath($message['time'])."".
				"</div>".
				"</div>".
				"</div>";
						
		}
		else
		{
			$response['append'][$append]['value'] .= "<div class=\"message_wrap\">".
				"<div class=\"message_left\" id=\"message_".$message['id']."\">".
				"<div class=\"arrow bottom2 left\"></div>".
				"".$message['message']."".
				"<div class=\"messagetime\">".
				"".$User->TimeMath($message['time'])."".
				"</div>".
				"</div>".
				"</div>";
				$response['isnewchatmessage'] = "yup";	
		}
		$append++;
	}
	}
}
  
//Output the response now.
$response['epoch'] = time();
print json_encode($response);
?>