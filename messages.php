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
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->
<?php
	include"header.php";
?>
<script src="js/inbox.js"></script>
<script>
$(document).ready(
 function()
 {
  alert("This page is under heavy construction. Some features may not be working ie. tabs.");
 }
);
</script>
<link rel="stylesheet" type="text/css" href="css/message.css" />
<title>SocioBubble | Inbox</title>
</head>

<?php
	include"banner.php";
?>
<div class="inbox_container">
	<div class="inbox_nav">
		<ul class="inav">
			<a href="#mail"><li>Inbox</li></a>
			<a href="#sent"><li>Sent</li></a>
			<a href="#trash"><li>Trash</li></a>
		</ul>
	</div>
	<div class="inbox_mail">
	</div>
	<div class="inbox_sent">
	</div>
	<div class="inbox_trash">
	</div>
</div>
<?php
	include "navigation.php";
	include "chatbar.php";
?>
