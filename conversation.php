<?php
include "sqlconnect.php";
@session_start();
include "Class.User.php";
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
else
{
	$User = new User();
}

if($_GET['with'] == "")
{
	header("location: messages");
	exit;
}
$query = mysql_query("SELECT * FROM `social_mail` WHERE (`from`='".$User->UserInfo['id']."' AND `userid`='".mysql_real_escape_string($_GET['with'])."') OR (`from`='".mysql_real_escape_string($_GET['with'])."' AND `userid`='".$User->UserInfo['id']."')") or die(mysql_error());
$fetch = mysql_fetch_array($query);
if($fetch['from'] != $User->UserInfo['id'] && $fetch['userid'] != $User->UserInfo['id'])
{
	header("Location: messages");
	exit;
}

?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->
<?php
	include "header.php";
?>
<script src="js/inbox.js"></script>
<script>
function LoadMessages()
{
 $.get("getmail.php?with=<?php print "".mysql_real_escape_string($_GET['with']).""; ?>",
  function(data)
  {
   $('div#messages').html(data);
  }
 );
}
LoadMessages();
setInterval("LoadMessages();", 60000);
function SendMessage()
{
 $.get("send_convo_mail.php",
  function(data)
  {
   LoadMessages();
  }
 );
}
</script>
<title>Conversation | <?php $with = mysql_real_escape_string($_GET['with']); $person = $User->GetUserByID($with); print "" .$person['first_name'] . " " . $person['last_name']. ""; ?></title>
</head>

<?php
	include "banner.php";
?>
<div id="message_container">
  <div class="messages">
    <div id="tabs">
      <div id="inbox_nav_hold">
        <ul class="inbox_nav">
          <li class="inbox_nav_option"><a href="messages">Inbox</a></li>
        </ul>
      </div>
      <div id="tabs-1">
      <form method="post" action="convo_send_mail.php?with=<?php print "".$_GET['with'].""; ?>" enctype="multipart/form-data">
      	<textarea class="conversation_message" name="message"></textarea>
        <input class="conversation_sub" name="submit" value="Send" type="submit">
        &nbsp;|&nbsp;Add an image &raquo;<input class="conversation_file" name="file" type="file">
      </form>
		<div id="messages">
        </div>
	</div>
    </div>
  </div>
</div>
</div>
<?php
	include "navigation.php";
	include "chatbar.php";
?>