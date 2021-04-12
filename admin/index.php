<?php
	include "sqlconnect.php";
	include "header.php";
?>
<title>SocioBubble Admin</title>
</head>
<body>
<div class="container">
	<div class="logo">
		<img src="http://sociobubble.com/images/logo.png">
	</div>
	<div class="logincontainer">
		<div class="loginform">
			<div class="news">
				Your IP <?php echo("" . $_SERVER['REMOTE_ADDR'] . "");?> has been logged for security purposes.
			</div>
			<form>
				Username:<br />
				<input type="text" class="input_field" name="username"><br />
				Password:<br />
				<input type="password" class="input_field" name="password"><br />
				PIN:<br />
				<input type="password" maxlength="6" class="input_field" name="pin"><br />
				<input type="submit" class="submit" name="submit" value="Login">
			</form>
		</div>
	</div>
</div>
<?php
	include "footer.php";
?>
