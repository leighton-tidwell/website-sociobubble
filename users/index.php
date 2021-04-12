<?php
if(!$_GET['url'])
{
	header("404 Not Found");
}
else
{
	print $_GET['url'];
}
?>