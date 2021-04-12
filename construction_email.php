<?php
include "sqlconnect.php";

if($_POST['email'] == "") 
{ 
	header("Location: construction?success=false");
	exit; 
}
$query = mysql_query("SELECT * FROM `social_emails` WHERE `emails`='" .mysql_real_escape_string($_POST['email']). "'") or die(mysql_error());
$count = mysql_num_rows($query);
if($count > 0){
	header("Location: construction?email=found");
	exit;
}
mysql_query("INSERT INTO `social_emails` (emails) VALUES ('".mysql_escape_string($_POST['email'])."') ") or die(mysql_error());
$to  = '"'.mysql_escape_string($_POST['email']).'"' . ', '; // note the comma
$message = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html><body>
<img src="http://sociobubble.com/images/logo.png" alt="" /><br /> Thank you for signing up for our latest updates!<br /> <br /> You will get emails that contain pictures of our site and new items such as asking for feedback.<br /> I hope you enjoy!
<div>
<pre>--
Kind regards,
Leighton Tidwell

SocioBubble Owner</pre>
</div>
</body></html>
';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
$headers .= 'To: '.mysql_escape_string($_POST['email']).'' . "\r\n";
$headers .= 'From: SocioBubble Updates <updates@sociobubble.com>' . "\r\n";

mail($to,"Thanks for signing up!", $message, $headers);
header("Location: http://sociobubble.com/construction?success=true");
?>
