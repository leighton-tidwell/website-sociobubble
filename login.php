<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if($_POST['email'] == "")
{
	header("index.php");
	exit;
}
if($_POST['password'] == "")
{
	header("index.php");
	exit;
}
if(isset($_POST['email']) && isset($_POST['password']))
{
	$query = mysql_query("SELECT * FROM `social_users` WHERE `email`='".mysql_real_escape_string($_POST['email'])."'");
	$fetch = mysql_fetch_array($query);
    if($_POST['password'] == "") 
    { 
		header("location: index?login=false");
    	die;
    }   
	if($fetch['password'] == md5($_POST['password']))
    
	{
		$poo = mysql_query("SELECT * FROM `social_users` ORDER BY `ID` DESC");
		while(($fetching = mysql_fetch_array($poo)) != NULL)
		{
			if((int)$fetching['active'] == "0")
		{
			header("location: step2");
			die;
		}
		else
		{
		}
		}
		mysql_query("UPDATE `social_users` SET `loginattempt`='0' WHERE `email`='".mysql_real_escape_string($_POST['email'])."'") or die(mysql_error());
		mysql_query("UPDATE `social_users` SET `sessionkey`='".session_id()."' WHERE `email`='".mysql_real_escape_string($_POST['email'])."'") or die(mysql_error());
		$_SESSION['OK'] = "true";
		header("Location: home");
		exit;
	}
      
	else
	{
		$loginattem = mysql_query("SELECT * FROM `social_users` WHERE `email`='".mysql_real_escape_string($_POST['email'])."'") or die(mysql_error());
		$login = mysql_fetch_array($loginattem);
		if((int)$login['loginattempt'] == "5")
		{
			
			exit;
		}
		else
		{
			mysql_query("UPDATE social_users SET loginattempt=loginattempt+1 WHERE `email`='".mysql_escape_string($_POST['email'])."'") or die(mysql_error());
			$loginattem2 = mysql_query("SELECT * FROM `social_users` WHERE `email`='".mysql_escape_string($_POST['email'])."'") or die(mysql_error());
			$login2 = mysql_fetch_array($loginattem2);
			header("location: index?loginattempt=".mysql_escape_string($login2['loginattempt'])."/5");
			exit;
		}
    }
}

?><title>Logging in...</title> 
