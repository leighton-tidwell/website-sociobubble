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
$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
$fetch = mysql_fetch_array($query);
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->

<?php
	include"header.php";
?>
<link rel="stylesheet" type="text/css" href="css/settings.css" />
<title>SocioBubble | Settings</title>
</head>

<?php
	include "banner.php";
?>
<div id="settings_container"> 
	<div id="settings_navigation">
		<ul class="settings">
			<div id="settings_header">
				<center>
					Settings
				</center>
			</div>
			<a href="#"><li class="settings_item">Account</li></a>
			<a href="#"><li class="settings_item">Privacy</li></a>
			<a href="#"><li class="settings_item">Notifications</li></a>
		</ul>
	</div>
	<center>
		Account Settings
	</center>
	<div id="account">
		<div id="basic_info">
			<center>
				Basic info
			</center>
			<form method="post" action="account_change_information.php" enctype="multipart/form-data">
				Email:<br>
				<input type="text" class="text_item" name="email" value="<?php print "" .$fetch['email']. "";?>"><br>
				City<br>
				<input type="text" class="text_item" name="city" value="<?php print "" .$fetch['city'].""; ?>"><br>
				Bio:<br>
				<textarea class="account_bio" name="bio"><?php print "" .stripslashes($fetch['bio']).""; ?></textarea><br>
				Country:<br>
				<input type="text" class="text_item" name="country" value="<?php print "" .$fetch['country'].""; ?>"><br>
				State Or Region:<br>
				<input type="text" class="text_item" name="state_region" value="<?php print "" .$fetch['state_region'].""; ?>"><br>
				Occupation:<br>
				<input type="text" class="text_item" name="occupation" value="<?php print "" .$fetch['occupation'].""; ?>"><br>
				Website:<br>
				<input type="text" class="text_item" name="website" value="<?php print "" .$fetch['website'].""; ?>"><br>
				Profile Picture:<br>
				<input type="file" name="file"><br>
				<input type="submit" class="button" value="Change"><br>
			</form>
		</div>
		<div id="password">
			<center>
				Password
			</center>
			<form method="post" action="account_change_password.php">
				Current:<br>
				<input type="password" class="text_item" name="current"><br>
				New:<br>
				<input type="password" class="text_item" name="new"><br>
				Re-Type New:<br>
				<input type="password" class="text_item" name="re"><br>
				<input type="submit" class="button" value="Change"><br>
			</form>
		</div>
		<div id="interest">
			<center>
				Interest
			</center>
			<form method="post" action="account_change_interest.php">
				<textarea class="interest" name="interest"><?php print "" .$fetch['interest'].""; ?></textarea>
				<center><input type="submit" class="button" value="Change"></center>
			</form>
		</div>
	</div>
</div>
<?php
	include "navigation.php";
	include "chatbar.php";
	include "footer.php";
?>