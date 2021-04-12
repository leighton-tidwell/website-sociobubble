<?php
include "sqlconnect.php";
session_start();
// Verify User Actually input data.
if($_POST['email'] == "")
{
	header("location: index?email=bad");
	exit;
}
if($_POST['first_name'] == "")
{
	header("location: index?first_name=bad");
	exit;
}
if($_POST['last_name'] == "")
{
	header("location: index?last_name=bad");
	exit;
}
if($_POST['password'] == "")
{
	header("location: index?pass2=bad");
	exit;
}
if($_POST['password'] != $_POST['repassword'])
{
	header("location: index?pass=bad");
	exit;
}
if($_POST['birthday_month'] == "-1")
{
	header("location: index?birth=bad");
	exit;
}
if($_POST['birthday_year'] == "-1")
{
	header("location: index?birth=bad");
	exit;
}
if($_POST['birthday_day'] == "-1")
{
	header("location: index?birth=bad");
	exit;
}

//check if user has used the email before.
$query = mysql_query("SELECT * FROM `social_users` WHERE `email`='" .mysql_real_escape_string($_POST['email']). "'") or die(mysql_error());
$countemails = mysql_num_rows($query);
if($countemails > 0){
	exit;
}

//randomize a token to use for activating users account
$basestring = "qwertyuiopasdfghjklzxcvbnm1234567890159753212963aasdfas1f651saf984sa9g4qerqw6t4641321dsafd8s9dg41b32s1c68sd84few8a48ew94f8as4dfas49df1sagh51j31uil3o1p1";
$Token = substr(str_shuffle($basestring), 0, 25);
$testsubject = strstr($_POST['email'], '@', true);

//Insert users account information
mysql_query("INSERT INTO `social_users` (`email`, `first_name`, `last_name`, `sex`,`username`, `password`, `birthday_month`, `birthday_year`, `birthday_day`, `email_token`) VALUES ('".mysql_real_escape_string($_POST['email'])."','".mysql_real_escape_string($_POST['first_name'])."','".mysql_real_escape_string($_POST['last_name'])."','".mysql_real_escape_string($_POST['sex'])."','".mysql_real_escape_string($testsubject)."','".md5($_POST['password'])."','".mysql_real_escape_string($_POST['birthday_month'])."','".mysql_real_escape_string($_POST['birthday_year'])."','".mysql_real_escape_string($_POST['birthday_month'])."','".$Token."')") or die(mysql_error());

//send an email to users email regarding their activation
$email = "Hello ".mysql_real_escape_string($_POST['first_name']).", "."\n"." Your login details are below:"."\n"." Email: ".mysql_real_escape_string($_POST['email']).""."\n"." Please activate your account by clicking this link: http://sociobubble.com/step2?token=".$Token.""."\n"."\n"."Thanks,"."\n"."Sociobubble "; 

//Title the previous mentioned email.
@mail(mysql_real_escape_string($_POST['email']), "Thank you for registering!",$email,"From: Sociobubble <support@sociobubble.com>");

//send an email to admin about account creation
@mail("leighton.tidwell57@gmail.com","Theres a new user in the network!","Dear Leighton "."\n"." A user has signed up at Sociobubble by the username of ".mysql_real_escape_string($_POST['first_name'])." and the email ".mysql_real_escape_string($_POST['email'])." view there profile here: http://sociobubble.com/","From: SocioBubble <support@sociobubble.com>");

$id = mysql_insert_id();
//make users directories
mkdir("accounts/".$id."");
mkdir("accounts/".$id."/profilepics");
mkdir("accounts/".$id."/pictures");
mkdir("accounts/".$id."/pictures/feed/");
mkdir("accounts/".$id."/pictures/private/");
touch("accounts/".$id."/index.php");
touch("accounts/".$id."/profilepics/index.php");
touch("accounts/".$id."/pictures/index.php");
touch("accounts/".$id."/pictures/feed/index.php");
touch("accounts/".$id."/pictures/private/index.php");

//Make it where user can see his/her own post
mysql_query("UPDATE `social_users` SET `friends`=':".$id.":' WHERE `id`='".$id."'") or die (mysql_error());

//Add channel settings for the user
mysql_query("INSERT INTO `social_channelsettings` (`userid`) VALUES ('".$id."')") or die(mysql_error());
//Insert users profile styles
mysql_query("INSERT INTO `social_profiles` (`uid`) VALUES ('".$id."')") or die(mysql_error());
		
//Log the user in
mysql_query("UPDATE `social_users` SET `sessionkey`='".session_id()."' WHERE `email`='".mysql_real_escape_string($_POST['email'])."'") or die(mysql_error());

$_SESSION['OK'] = "true";
header("Location: home");
exit;
?>
