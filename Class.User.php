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
	$a = str_replace(":)", "<img src=\"./emos/smiley1.gif\" />", $string);
	$b = str_replace(";)", "<img src=\"./emos/smiley2.gif\" />", $a);
	$c = str_replace(":O", "<img src=\"./emos/smiley3.gif\" />", $b);
	$d = str_replace(":D", "<img src=\"./emos/smiley4.gif\" />", $c);
	$e = str_replace(":S", "<img src=\"./emos/smiley5.gif\" />", $d);
	$f = str_replace(":(", "<img src=\"./emos/smiley6.gif\" />", $e);
	$g = str_replace("*:(", "<img src=\"./emos/smiley7.gif\" />", $f);
	$h = str_replace("X)", "<img src=\"./emos/smiley8.gif\" />", $g);
	$i = str_replace(":}", "<img src=\"./emos/smiley9.gif\" />", $h);
	$j = str_replace(":*", "<img src=\"./emos/smiley10.gif\" />", $i);
	$k = str_replace("X(", "<img src=\"./emos/smiley11.gif\" />", $j);
	$l = str_replace(":Z", "<img src=\"./emos/smiley12.gif\" />", $k);
	$m = str_replace(";}", "<img src=\"./emos/smiley13.gif\" />", $l);
	$n = str_replace("8)", "<img src=\"./emos/smiley16.gif\" />", $m);
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
/* 
 * @param $UID : The UserID to send the request too
 * @returns @type: Mixed : Error message on error and True on on success.
 * @warning Return type may return a boolean value or a value that parses as a boolean value. use '===' operator
*/
function SendRequest($UID, $MID)
{
	$check = mysql_query("SELECT * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($UID)."' AND `from`='".mysql_real_escape_string($MID)."'");
	if(mysql_num_rows($check) > 0)
	{
		return "Friend request already sent.";
	}
	mysql_query("INSERT `social_friendrequest` (`to`,`from`) VALUES ('".mysql_real_escape_string($UID)."','".mysql_real_escape_string($MID)."')") or die(mysql_error());
}

/*
 * @param $UID : User ID of the user who sent it
 * @returns @type: Mixed : Error message on error and True on on success.
 * @warning Return type may return a boolean value or a value that parses as a boolean value. use '===' operator
*/
/*
function AcceptRequest($UID, $MID)
{
	$check = mysql_query("SELECT * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($MID)."' AND `from`='".mysql_real_escape_string($UID)."'") or die(mysql_error());
	if(mysql_num_rows($check) > 0)
	{
		$fetchthequerysauce = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($MID)."'");
		$fetchdat = mysql_fetch_array($fetchthequerysauce);
		mysql_query("UPDATE `social_users` SET `friends`='".mysql_real_escape_string($fetchdat['friends']).":".mysql_real_escape_string($UID)."' WHERE `id`='".$User->UserInfo['id']."'");
		mysql_query("DELETE * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($MID)."' AND `from`='".mysql_real_escape_string($UID)."'") or die(mysql_error());
	}
	else brb
	{
		return "You cannot accept a request you never received.";
	}
}
*/

function AcceptRequest($UID, $MID)
{
	$check = mysql_query("SELECT * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($MID)."' AND `from`='".mysql_real_escape_string($UID)."' LIMIT 1") or die(mysql_error());
	$num = mysql_num_rows($check);
	if($num == 1)
	{
		$fetchthequerysauce = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($MID)."'");
		$fetchthequerysauce2 = mysql_query("SELECT * FROM `social_users` WHERE `id`='".mysql_real_escape_string($UID)."'");
		$fetchdat = mysql_fetch_array($fetchthequerysauce);
		$fetchdat2 = mysql_fetch_array($fetchthequerysauce2);
		mysql_query("UPDATE `social_users` SET `friends`='".mysql_real_escape_string($fetchdat['friends'])."".mysql_real_escape_string($UID).":' WHERE `id`='".mysql_real_escape_string($MID)."'") or die(mysql_error());
		mysql_query("UPDATE `social_users` SET `friends`='".mysql_real_escape_string($fetchdat2['friends'])."".mysql_real_escape_string($MID).":' WHERE `id`='".mysql_real_escape_string($UID)."'") or die(mysql_error());
		mysql_query("DELETE FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($MID)."' AND `from`='".mysql_real_escape_string($UID)."'") or die(mysql_error());
	}
	else
	{
		return "Fuck off, cock sucker trying to accept some friend request you dont have.";
	}
} 

function IsFriend($UID)
{
	if(in_array($UID, $this->FriendsList))
	{
		return true;
	}
	else
	{
		return false;
	}
}
function AddNotification($UID, $from, $Message, $link, $type)
{
	mysql_query("INSERT INTO `social_notifications` (`uid`, `from`, `message`, `link`, `category`, `time`) VALUES ('".mysql_real_escape_string($UID)."','".mysql_real_escape_string($from)."','".mysql_real_escape_string($Message)."','".mysql_real_escape_string($link)."','".mysql_real_escape_string($type)."','".mysql_real_escape_string(time())."')") or die(mysql_error());
}
function UploadImage($file, $location)
{
	$name = $file ['name'];
if($name != "")
{
	$basestring = "irwejig0jre903499our4t9ojwokiewnmlkfkwjr3213u21ok32df1i32hj1fuoed321l61e6i51ti31o21n365i1sad1iino321saurfbraollsmbballasandpenisva63465416541651651651651111111561561654196549849afsd196sda854498498as4d984a9s8f4ahkhfsakldfsdakjfsad54f85asd4f9s8da49f8ds4af984as99484fas9d49d8s4f9ds49f82a9s84df98sa42f984dsa9f492asd84f298as4d29f84as92d8429as8df4298as4fd29as84f29as8d4f2984asf98as4df29asd429f842as9d84f298sd4a2f98s4da2f98sd42a9f48sd9f4298asd4f29d8sa42f9d4s9fd42s9f84a9s2df49das84f9asd842f98asd4g89f4g9sh8498g4s89fj48g4sj9t84tuj9j8249s8t4298t48efahsd9hdsa8h9ads8f9h0sf890asjd0f9js0ad9fjd0sa9fh089sadh8903408308hhe08ahfa0e8h08ahf98eayh93h98hq2asdfsdafdasfdsagina4165465lol";
	$Name = substr(str_shuffle($basestring), 0, 10);
	$valid_mime_types = array(
    "image/gif",
    "image/png",
    "image/jpeg",
    "image/pjpeg",
	);
	
	$type = $file ['type'];
	$ext = end(explode(".", $_FILES['file']['name']));
	$size = $file ['size'];
	$tmppath = $file['tmp_name']; 
	if($name != "")
	{
		if($type == "image/png" || $type == "image/pjpeg" || $type == "image/gif" || $type == "image/jpeg")
		{
			if($ext == "png" || $ext == "jpg" || $ext == "gif" || $ext == "jpeg" || $ext == "PNG" || $ext == "JPG" || $ext == "GIF" || $ext == "JPEG")
			{	
				if($location == "feed"){			
					move_uploaded_file ($tmppath, 'accounts/'.$this->UserInfo['id'].'/pictures/feed/'.$Name.'.'.$ext);
					mysql_query("UPDATE `social_feed` SET `img`='accounts/".$this->UserInfo['id']."/pictures/feed/".$Name.".".$ext."' WHERE `id`='".mysql_insert_id()."'") or die(mysql_error());
				}
			}
		}
	}
}
	
}
}
?>
