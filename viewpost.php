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
	$getquery = mysql_query("SELECT * FROM `social_feed` WHERE `id`='".mysql_real_escape_string($_GET['pid'])."'");
	$fetchquery = mysql_fetch_array($getquery);
	$getinfo = $User->GetUserByID($fetchquery['postedby']);
}
else
{
	header("Location: home");
	exit;
}
if($User->UserInfo['first_name'] == "")
{
	mysql_query("UPDATE `social_users` SET `sessionkey`='DEAD' WHERE `sessionkey`='".session_id()."'") or die(mysql_error());
	unset($_SESSION['OK']);
	session_set_cookie_params(0);
	session_destroy();
	setcookie("PHPSESSID", time()-3600);
	session_regenerate_id(true);
	header("location: index");
}
$postid = $_GET['pid'];
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->
<?php
	include "header.php";
?>

<link rel="stylesheet" type="text/css" href="css/viewpost.css" />
<title><?php print substr($fetchquery['shout'], 0, 32); ?></title>
</head>
<script>
$(document).ready(
 function()
 {
  GetLikes(<?php print $postid ?>);
  GetComments(<?php print $postid ?>);
  setInterval("GetLikes(<?php print $postid ?>);", 60000);
  setInterval("GetComments(<?php print $postid ?>);", 60000);
 }
);
function GetLikes(ID)
{
 $.get("ratelist.php?postid="+ID,
  function(data)
  {
   $('div#postwholiked').html(data);
  }
 );
}
function GetComments(ID)
{
 $.get("getcomments.php?pid="+ID,
  function(data)
  {
   $('div#postcomments').html(data);
  }
 );
}
function RateShout(ID)
{
 $.post("rate_shout.php?postid="+ID,
  function(data)
  {
   GetLikes(<?php print $postid ?>);
  }
 );
}
function DeleteComment(ID)
{
 $.get("delete_comment.php?commentid="+ID,
  function(data)
  {
   $("div#comment-" + ID).slideUp("fast", function() { $(this).delay(100).remove(); });
  }
 );
}
</script>
<?php
	include "banner.php";
?>
<div class="postbody">
	<div class='postprofpicture'>
		<img src="p.php?src=<?php print $getinfo['profile_picture']; ?>&w=100&h=100">
	</div>
	<div class='postname'>
		<a href="<?php print $getinfo['username'] ?>"><?php print "".$getinfo['first_name']." ".$getinfo['last_name'].""; ?></a>
	</div>
	<div class='postpost'>
		<?php print $fetchquery['shout'] ?>
	</div>
	<div class='posttime'>
		<?php print $User->TimeMath($fetchquery['timeposted']); ?>
	</div>
	<div class='postoptions'>
	<a onclick="RateShout(<?php print $_GET['pid'] ?>);">Like</a> | <div style='float: right;'><a href="report?feeditem=<?php print $_GET['id'] ?>">Report</a></div><?php if($fetchquery['postedby'] == $User->UserInfo['id']){ print "<a href='delete_shout.php?id=".$_GET['id']."'>Delete</a>"; } ?>
	</div>
	<div id="postwholiked" class='postwholiked'>
		
	</div>
</div>
<div id='postcomments' class='postcomments'>
</div>
<?php
	include"navigation.php";
	include "chatbar.php";
?>
