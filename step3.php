<?php
include "sqlconnect.php";
/*if(!$_GET['token'])
{
	header("location: index.php");
	exit;
}
else
{
	$token = $_GET['token'];
}
$id = mysql_query("SELECT * FROM `social_users` WHERE `email_token`='".mysql_real_escape_string($_GET['token'])."'") or die(mysql_error());
$fetch = mysql_fetch_array($id);
if($fetch['tokenused'] == "0")
{
	mysql_query("UPDATE `social_users` SET `tokenused`='1' WHERE `email_token`='" .mysql_real_escape_string($_GET['token']). "'") or die(mysql_error());
}
else
{
	header("Location: 404.shtml");
	exit;
}
*/
?>
<!--Code Written by: Leighton Tidwell-->
<!--Code Designed for: SocioBubble -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Welcome to SocioBubble, here you can connect togethere with all your friends in one large community! Sign up is free!" />
<meta name="language" content="English" />
<meta name="keywords" content="social network community people connecting all together helping" >
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/step3.css" />
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script type="text/javascript">
	function popup()
	{
		alert("Information Submitted. Please log in!");
	}
	</script>
<title>SocioBubble | Step 3</title>
</head>

<body>
<div id="header">
  <div id="step"> Step 3 of 3 </div>
  <div id="logo"><a href="index" title="SocioBubble Home" alt="SocioBubble Home"><img src="images/logo.png" /></a></div>
</div>
<div id="step2_container">
  <div id="step2_container_form">
    <center>
      <font size="6">Profile Information<br />
      </font>
    </center>
    <form method="post" action="step3_verify.php" enctype="multipart/form-data">
	<input type="hidden" value="<?php print $token ?>" name="token" />
	<div class="location">
		TimeZone
		<input type="text" name="timezone">
		<br />
		Country
		<input type="text" name="country">
		<br />
		State
		<input type="text" name="state/region">
		<br />
		City
		<input type="text" name="city">
	</div>
	<input type="submit" value="Submit" class="submit">
	<div class="personal">
	</div>
    </form>
      <br />
  </div>
</div>
<div id="login_footer">
  <div id="footerbar"></div>
  Help | About<br />
  Copyright&copy; 2013 SocioBubble<br />
 </div>
</body>
</html>
