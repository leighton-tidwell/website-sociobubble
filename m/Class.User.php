<?php
class User
{
	var $UserInfo;
	var $FriendsList;
	
	 function User()
  {
  // Caches the Users infomation by their current Session Key
  $query = mysql_query("SELECT * FROM `social_users` WHERE `SessionKey`='".session_id()."'") or die(mysql_error());
  $timer = time() + 100;
  $this->UserInfo = mysql_fetch_array($query);
  $this->FriendsList = explode(":", $this->UserInfo['friends']);
 mysql_query("UPDATE `social_users` SET `lastseen`='".$timer."' WHERE `id`='".$this->UserInfo['id']."'");
  }

	function GetUserByID($ID)
	{
		$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($ID)."'") or die(mysql_error());
		$fetch = mysql_fetch_array($query);
		return $fetch;
	}
	function IsOnline($ID)
    {
  		$tmp = $this->GetUserByID($ID);
  		 if((int)$tmp['lastseen'] < time())
  		{
   			return true;
  		}
  		else
  		{
   			return false;
  		}
	
 }
 function TimeMath($timestamp)
{
	// I see the problem Because the time posted is in wrong time 1 sec a bit of bombdas shall solve
	$x = (time() + 3600) -  ((int)$timestamp + 3600);
	if($x < 3600)
	{
		if($x < 60)
		{
			return $x." seconds ago";
		}
		else if($x > 59)
		{
			$y = ($x / 60);
			$z = floor($y);
			if($z == 1)
			{
				return "about a minute ago"; // sorry it was pissing me off Lol, me too TBH :P
			}
			else
			{
				return "about ".$z." minutes ago";
			}
		}
	}
	else if($x < 86400)
	{
		$y = ($x / 60 / 60);
		$z = floor($y);
		if($z != 1)
		{
			return "about ".$z." hours ago";
		}
		else
		{
			return "about an hour ago";
		}
	}
	else
	{
		/*
		if($this->UserInfo['timezone'] == "CST")
		{
			return date("F j, Y, g:i a", (int)$timestamp);
		}
		else
		{ */
			$tmp = mysql_query("SELECT * FROM `social_timezones` WHERE `code`='".$this->UserInfo['timezone']."'") or die(mysql_error());
			$tmp2 = mysql_fetch_array($tmp);
			
			if($tmp2['poll'] == "-")
			{
				$y = (60 * 60 * (int)$tmp2['offset']);
				$z = ((int)$timestamp - $y); 
				return date("F j, Y, g:i a", $z);
			}
			else if($tmp2['poll'] == "+")
			{
				$y = (60 * 60 * (int)$tmp2['offset']);
				$z = ((int)$timestamp + $y);
				return date("F j, Y, g:i a", $z);
			}
			else
			{
				return "An Error occured";
			}
		
	}
	
}
function AddEmo($string)
{
	$a = str_replace(":)", "<img src=\"http://sociobubble.com/emos/smiley1.gif\" />", $string);
	$b = str_replace(";)", "<img src=\"http://sociobubble.com/emos/smiley2.gif\" />", $a);
	$c = str_replace(":O", "<img src=\"http://sociobubble.com/emos/smiley3.gif\" />", $b);
	$d = str_replace(":D", "<img src=\"http://sociobubble.com/emos/smiley4.gif\" />", $c);
	$e = str_replace(":S", "<img src=\"http://sociobubble.com/emos/smiley5.gif\" />", $d);
	$f = str_replace(":(", "<img src=\"http://sociobubble.com/emos/smiley6.gif\" />", $e);
	$g = str_replace("*:(", "<img src=\"http://sociobubble.com/emos/smiley7.gif\" />", $f);
	$h = str_replace("X)", "<img src=\"http://sociobubble.com/emos/smiley8.gif\" />", $g);
	$i = str_replace(":}", "<img src=\"http://sociobubble.com/emos/smiley9.gif\" />", $h);
	$j = str_replace(":*", "<img src=\"http://sociobubble.com/emos/smiley10.gif\" />", $i);
	$k = str_replace("X(", "<img src=\"http://sociobubble.com/emos/smiley11.gif\" />", $j);
	$l = str_replace(":Z", "<img src=\"http://sociobubble.com/emos/smiley12.gif\" />", $k);
	$m = str_replace(";}", "<img src=\"http://sociobubble.com/emos/smiley13.gif\" />", $l);
	$n = str_replace("8)", "<img src=\"http://sociobubble.com/emos/smiley16.gif\" />", $m);
	return $n;
}
function Get_Mounth($m)
{
	switch((int)$m)
	{
		case 1:
		{
			return "January";
		}
		case 2:
		{
			return "February";
		}
		case 3:
		{
			return "March";	
		}
		case 4:
		{
			return "April";
		}
		case 5:
		{
			return "May";
		}
		case 6:
		{
			return "June";
		}
		case 7:
		{
			return "July";
		}
		case 8:
		{
			return "August";
		}
		case 9:
		{
			return "September";
		}
		case 10:
		{
			return "October";
		}
		case 11:
		{
			return "November";
		}
		case 12:
		{
			return "December";
		}
	}
}
}
?>
