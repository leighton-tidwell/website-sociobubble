<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: SocioBubble -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Welcome to SocioBubble, here you can connect together with all your friends in one large community! Sign up is free!" />
<link type="image/png" rel="icon" href="http://sociobubble.com/images/favicon.ico" />
<meta name="language" content="English" />
<meta name="keywords" content="Socio Bubble social network community people connecting all together helping" >
<link rel="stylesheet" type="text/css" href="css/construction.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="css/iestyle.css" />
<![endif]-->
<title>SocioBubble | Coming Soon!</title>
</head>

<body>
	<div class="container">
	<div class="logo">
		<img src="http://sociobubble.com/images/logo.png">
	</div>
	<div class="box">
		<?php
		if($_GET['success'] == "false"){
			echo("There was an error while adding your email");
		}
		if($_GET['email'] == "found"){
			echo("Your email has already been submitted!");
		}
		if($_GET['success'] == "true"){
			echo("Your email was added!");
		}
		?>
		<div class="maincontainer">
			<div class="toptext">
				Coming Soon!
			</div>
			We are currently underconstruction, you can leave your email here though and we will let you know when we're open!
			<form method="post" action="construction_email.php">
				<input type="text" class="input_field" name="email"><br />
				<input type="submit" class="submit" value="Submit" name="submit">
			</form>
		</div>
		<div class="footer">
			Copyright&copy; 2013 Sociobubble
		</div>
	</div>
</div>
</body>
</html>
