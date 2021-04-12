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
<?php
	include "header.php";
?>
<title>Sociobubble | Join the bubble, get social!</title>
</head>
<body class="loginbody">
	<div class="content">
		<div class="logo">
			<img width="350px" src="http://sociobubble.com/images/logo.png">
		</div>
		<div class="login">
			<form method="post" action="login.php">
				Username:<br />
				<input type="text" name="email" class="login_field"><br />
				Password:<br />
				<input type="password" name="password" class="login_field"><br /><br />
				<input type="submit" class="login_submit" value="Login"><br />
				<p style="font-size: 150%;">Or register <a href="">here</a></p>
			</form>
		</div>
	</div>
</body>
</html>
