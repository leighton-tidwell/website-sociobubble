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
if($_POST['shout'] == "")
{
	header("location: home");
	exit;
}
if($User->UserInfo['first_name'] == "")
{
	mysql_query("UPDATE `social_users` SET `sessionkey`='DEAD' WHERE `sessionkey`='".session_id()."'") or die(mysql_error());
	unset($_SESSION['OK']);
	session_set_cookie_params(0);
	session_destroy();
	setcookie("PHPSESSID", time()-3600);
	session_regenerate_id(true);
	echo('hea');
}
mysql_query("INSERT INTO `social_feed` (`shout`,`postedby`, `timeposted`, `mood`,`ipaddress`) VALUES ('".htmlspecialchars(str_replace("\n", "*-@", $_POST['shout']))."','".$User->UserInfo['id']."', '".time()."','".mysql_real_escape_string($_POST['mood'])."','".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."')") or die(mysql_error());
$file = $_FILES ['file'];
	$name1 = $file ['name'];
if($name1 != "")
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
	if($name1 != "")
	{
		if($type == "image/png" || $type == "image/pjpeg" || $type == "image/gif" || $type == "image/jpeg")
		{
			if($ext == "png" || $ext == "jpg" || $ext == "gif" || $ext == "jpeg")
			{			move_uploaded_file ($tmppath, 'accounts/'.$User->UserInfo['id'].'/pictures/feed/'.$Name.'.'.$ext);//image is a folder in which you will save image
			}
		}
	}
		mysql_query("UPDATE `social_feed` SET `img`='accounts/".$User->UserInfo['id']."/pictures/feed/".$Name.".".$ext."' WHERE `id`='".mysql_insert_id()."'") or die(mysql_error());
}

header("Location: home");
exit;
?>
