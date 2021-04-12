<?php
include "../sqlconnect.php";
$query = mysql_query("SELECT * FROM `social_users`") or die(mysql_error());

while(($fetch = mysql_fetch_array($query)) != NULL)
{
	print $fetch['ip']."<br />";
}