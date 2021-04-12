<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(!isset($_SESSION['OK']))
{	
	header("location: index");
	exit;
}
else
{
	$User = new User();
}
if(!$_GET['url'])
{
	header("404 Not Found");
}
$query1 = mysql_query("SELECT * FROM `social_users` WHERE `username`='" .mysql_real_escape_string($_GET['url']). "'") or die(mysql_error());
	$fetch1 = mysql_fetch_array($query1);
	$query = mysql_query("SELECT * FROM `social_profiles` WHERE `uid`='" .mysql_real_escape_string($fetch1['id']). "'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);
	
if($fetch1['first_name'] == "")
{
	if($fetch1['last_name'] == "")
	{
		header("location: 404.shtml");
	}
}
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->
<?php
	include "header.php";
?>
<script src="js/profile.js"></script>
<script>

function overlay(){

el = document.getElementById("overlay");
el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";

}

function close() {
    document.getElementById("overlay").style.visibility = 'hidden';
}
</script>
<style type="text/css">
body{
	background-color: <?php print "" .$fetch['body(backgroundcolor)']. ""; ?>;
	background-image: url("<?php print "" .$fetch['body(backgroundimage)']. ""; ?>");
	background-repeat: repeat-y;
	background-size: 100%;
}
.profile{
	background: <?php print "" .$fetch['profile(backgroundcolor)']. ""; ?>;
	border-radius: <?php print "" .$fetch['profile(borderradius)']. ""; ?>px;
	-moz-border-radius: <?php print "" .$fetch['profile(borderradius)']. ""; ?>px;
	-webkit-border-radius: <?php print "" .$fetch['profile(borderradius)']. ""; ?>px;
	border-width: <?php print "" .$fetch['profile(borderwidth)']. ""; ?>px;
	border-style: <?php print "" .$fetch['profile(borderstyle)']. ""; ?>;
	border-color: <?php print "" .$fetch['profile(bordercolor)']. ""; ?>;
	float: left;
	margin-right: auto;
	margin-left: 10%;
	margin-bottom: 55px;
	width: 60%;
	height: 800px;
	z-index: 5;
	margin-top: 90px;	
	color: #fff;
	font-weight: bolder;
	font-family: Verdana, Geneva, sans-serif;
}
</style>
<link rel="stylesheet" type="text/css" href="css/profile.css" />
<title>SocioBubble | <?php print "". $fetch1['first_name'] . " " .$fetch1['last_name']. ""; ?></title>
</head>
<script>
$(document).ready(function() {
    $("#profulpicture").fancybox({
          helpers: {
              title : {
                  type : 'float'
              }
          }
      });
});
</script>
<?php
	include "banner.php";
?>
<div class="profile">
	<div class="profile_header">
        <div class="profile_box">
			<?php
            if($fetch1['profile_picture'] != "")
            {
                echo("<a id='profulpicture' rel='gallery1' href='".$fetch1['profile_picture']."'>");
					echo("<img class='profile_pic' src='p.php?src=" .$fetch1['profile_picture']. "&w=100&h=100'/>");
				echo("</a>");
            }
				echo("<div class='name'>");
					echo("" .$fetch1['first_name']. " " .$fetch1['last_name']. "");
				echo("</div>");
				
				echo("<div class='quickinfo'>");
				if($fetch1['city'] && $fetch1['state_region'] != "")
				{
					echo("Lives In " .substr($fetch1['city'] . ", " . $fetch1['state_region'], 0, 27). "");
					if(strlen($fetch1['city'] . "," . $fetch1['state_region']) > 27)
					{
						echo("<a class='link' style='cursor:pointer;' onClick='overlay();'>...</a><br />");
					}
					else
					{
						echo("<br />");
					}
				}
				else
				{
					if($fetch1['city'] != "")
					{
						echo("Lives In " .$fetch1['city']. "<br />");
					}
					if($fetch1['state_region'] != "")
					{
						echo("Lives In " .$fetch1['state_region']. "<br />");
					}
				}
				if($fetch1['occupation'] != "")
				{
					echo("" .$fetch1['occupation']. "<br />");
				}
				echo("Born on " .$User->Get_Mounth($fetch1['birthday_month']). " " .$fetch1['birthday_day']. ", " .$fetch1['birthday_year']. "<br />");
				if($fetch1['interest'] != "")
				{
					echo ("Likes: ".substr($fetch1['interest'], 0, 27)."");
					if(strlen($fetch1['interest']) > 27)
					{
						echo("<a class='link' style='cursor:pointer;' onclick='overlay();'>...</a><br />");
            	    }
					else
					{
						echo("<br />");
					}
				}
				if($fetch1['website'] != "")
				{
					echo("Website: <a target='_blank' href='" .$fetch1['website']. "'>" .$fetch1['website']. "</a><br />");
				}
				echo("</div>");
				?>
			</div>
        </div>
        <?php
			echo("<div id='addfriend' class='addfriend'>");
			if($User->UserInfo['id'] != "" .$fetch1['id']. "")
			{
				foreach($User->FriendsList as $friend)
				{
				 	$querybuilder = "" .$fetch1['id']. " == ".$User->FriendsList[1]."";
					$queryfinisher .= " || " .$fetch1['id']. " == ".$friend."";
				}
				if($User->IsFriend($fetch1['id']))
				{
					echo("<center>Friends&nbsp;<img src='images/menudown.png'></center>");
				}
				else
				{
					$check = mysql_query("SELECT * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($fetch1['id'])."' AND `from`='".mysql_real_escape_string($User->UserInfo['id'])."'");
					$check2 = mysql_query("SELECT * FROM `social_friendrequest` WHERE `to`='".mysql_real_escape_string($User->UserInfo['id'])."' AND `from`='".mysql_real_escape_string($fetch1['id'])."'");
					if(mysql_num_rows($check) > 0)
					{
						echo("Friend request already sent.");
					}
					elseif(mysql_num_rows($check2) > 0)
					{
						echo("<a href='acceptfriend.php?id=".$fetch1['id']."' style='cursor:pointer;'>Accept Request</a>");
					}
					else
					{
						echo("<a href='addfriend.php?id=".$fetch1['id']."' style='cursor:pointer;'>Add me</a>");
					}
				}
			}
			else
			{
				echo("<center><a href='settings'>Settings&nbsp;<img src='images/menudown.png'></a></center>");
			}
			echo("</div>");
			echo("<div class='profilesummary'>");
				echo("<a  style='cursor:pointer;' onClick='overlay();'>Profile Summary</a>");
			echo("</div>");
		?>
    </div>
</div>
<div id="overlay">
	<div class="overlay_content">
		<?php
		if($fetch1['profile_picture'] != "")
		{
			echo("<div class='info_name'>");
				echo($fetch1['first_name'] ." ". $fetch1['last_name']);
			echo("</div>");	
			echo("<div class='info_pic'>");
				echo("<img class='info_pic' src='p.php?src=" .$fetch1['profile_picture']. "&h=150&w=150'>");
			echo("</div>");
		}
		else
		{
			echo("<div class='info_nopicname'>");
				echo($fetch1['first_name'] ." ". $fetch1['last_name']);
			echo("</div>");
		}
		echo("<div class='info_summary'>");
				if($fetch1['city'] && $fetch1['state_region'] != "")
				{
					echo("Lives In " . $fetch1['city'] . ", " . $fetch1['state_region'] . "<br />");
				}
				else
				{
					if($fetch1['city'] != "")
					{
						echo("Lives In " .$fetch1['city']. "<br />");
					}
					if($fetch1['state_region'] != "")
					{
						echo("Lives In " .$fetch1['state_region']. "<br />");
					}
				}
				if($fetch1['occupation'] != "")
				{
					echo("" .$fetch1['occupation']. "<br />");
				}
				echo("Born on " .$User->Get_Mounth($fetch1['birthday_month']). " " .$fetch1['birthday_day']. ", " .$fetch1['birthday_year']. "<br />");
				if($fetch1['bio'] != "")
				{
					echo("Bio: ".stripslashes($fetch1['bio'])."<br />");
				}
				if($fetch1['interest'] != "")
				{
					echo ("Likes: ".$fetch1['interest']."<br />");
				}
				if($fetch1['website'] != "")
				{
					echo("Website: <a target='_blank' href='" .$fetch1['website']. "'>" .$fetch1['website']. "</a><br />");
				}
			echo("<center>");
				echo("Profile Stats");
			echo("</center>");
			
			$getnumberofshouts = mysql_query("SELECT * FROM `social_feed` WHERE `postedby`='" .$fetch1['id']. "'") or die(mysql_error());
			
			$fetchnumberofshouts = mysql_num_rows($getnumberofshouts);
			
			echo("Number of Shouts: " .$fetchnumberofshouts. "<br />");
			
			$getnumberofcomments = mysql_query("SELECT * FROM `social_comments` WHERE `uid`='" .$fetch1['id']. "'") or die(mysql_error());
			
			$fetchnumberofcomments = mysql_num_rows($getnumberofcomments);
			
			echo("Number of Comments: " .$fetchnumberofcomments. "<br />");
			
			$getnumberofmessages = mysql_query("SELECT * FROM `social_mail` WHERE `from`='" .$fetch1['id']. "'") or die(mysql_error());
			$fetchnumberofmessages = mysql_num_rows($getnumberofmessages);
			
			echo("Number of Messages Sent: " .$fetchnumberofmessages. "<br />");
			echo("<div class='info_options'>");
				echo("<a href='#' class='info_message_send'>Send A Message</a>");
				echo("<a href='javascript:close();' class='info_close'>Close</a>");
			echo("</div>");
		echo("</div>");
		?>
	
	</div>
</div>
<?php
	include "navigation.php";
	include "chatbar.php";
?>