<?php
include "sqlconnect.php";
if($_GET['token'])
{
	$query = mysql_query("SELECT * FROM `social_users` WHERE `email_token`='".($_GET['token'])."' AND `active`='0'");
	$fetch = mysql_fetch_array($query);
	if($_GET['token'] == $fetch['email_token'])
	{
		mysql_query("UPDATE `social_users` SET `active`='1' WHERE `email_token`='".($_GET['token'])."'");
		header("location: step3?token=".$_GET['token']."");
		exit;
	}
	else
	{
		header("location: step2?fail=true");
		exit;
	}
}
if($_GET['post'] == "true")
{
	include "step2_verify.php";
}
else
{
}
?>

<!--Code Written by: Leighton Tidwell-->
<!--Code Designed for: Sociobubble -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Welcome to Sociobubble, here you can connect togethere with all your friends in one large community! Sign up is free!" />
<meta name="language" content="English" />
<meta name="keywords" content="social network communitie people connecting all together helping" >
<link type="image/png" rel="icon" href="http://sociobubble.com/images/favicon.png" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/step2.css" />
<title>SocioBubble | Step 2</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
</head>

<body>
<div id="header">
  <div id="step"> Step 2 of 3 </div>
  <div id="logo"><a href="index" title="SocioBubble Home" alt="SocioBubble Home"><img src="images/logo.png" /></a></div>
</div>
<div id="step3_container">
  <div id="step3_container_form">
    <center>
      <font size="5"> Your almost there!<br>
      Go to your email and click the activation link or enter the activation code: </font>
      <form action="step2?post=true" method="post">
        <input class="activation" type="text" name="email_token">
        <br>
        <input class="activate_ok" type="submit" value="Activate">
      </form>
      <?php if($_GET['fail'] == "true"){print "<font color=\"red\">Sorry that was an invalid key!</font>";} ?>
    </center>
  </div>
</div>
<div id="login_footer">
  <div id="footerbar"></div>
  Copyright&copy; 2013 SocioBubble<br>
  Help | About<br>
  </div>
</body>
</html>
