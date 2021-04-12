<?php
include "sqlconnect.php";
session_start();
if(isset($_SESSION['OK']))
{	
	header("location: home");
	exit;
}
else
{
}
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: SocioBubble -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Welcome to SocioBubble here you can connect togethere with all your friends in one large community! Sign up is free!" />
<meta name="language" content="English" />
<meta name="keywords" content="SocioBubble, social network a community people connecting all together helping" >
<link type="image/png" rel="icon" href="http://sociobubble.com/images/favicon.png" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/index.css" />
<title>Sociobubble | Join the bubble, get social!</title>
</meta>
<script>
var i_am_old_ie = false;
<!--[if LT IE 8]>
alert("This website is optimized for Google Chrome, IE 8, and Mozilla Firefox. To get the best web experience go here http://google.com/chrome");
<![endif]-->
</script>
</head>

<body class="lobody">
<div id="header">
  <div id="logo">
    <a href="index" title="SocioBubble Home" alt="SocioBubble Home"><img src="images/logo.png" /></a>
  </div>
</div>
</div>
<div id="login_footer">
  <div id="footerbar"></div>
  Help | About<br>
  Copyright&copy; 2013 SocioBubble
</div>
</body>
</html>
