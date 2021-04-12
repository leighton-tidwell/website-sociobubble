<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(!isset($_SESSION['OK']))
{
	header("Location: index");
	exit;
}
else
{
	$User = new User();
}
if($_POST['message'] == "")
{
	header("location: conversation?with=".$_GET['with']."");
	exit;
}

mysql_query("INSERT INTO `social_mail` (`userid`,`message`,`from`,`time`, `ipaddress`) VALUES ('".mysql_real_escape_string($_GET['with'])."','".mysql_real_escape_string($_POST['message'])."','".$User->UserInfo['id']."','".time()."','".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])."')") or die(mysql_error());
$basestring = "irwejig0jre903499our4t9ojwokiewnmlkfkwjr3213u21ok32df1i32hj1fuoed321l61e6i51ti31o21n365i1sad1iino321saurfbraollsmbballasandpenisva63465416541651651651651111111561561654196549849afsd196sda854498498as4d984a9s8f4ahkhfsakldfsdakjfsad54f85asd4f9s8da49f8ds4af984as99484fas9d49d8s4f9ds49f82a9s84df98sa42f984dsa9f492asd84f298as4d29f84as92d8429as8df4298as4fd29as84f29as8d4f2984asf98as4df29asd429f842as9d84f298sd4a2f98s4da2f98sd42a9f48sd9f4298asd4f29d8sa42f9d4s9fd42s9f84a9s2df49das84f9asd842f98asd4g89f4g9sh8498g4s89fj48g4sj9t84tuj9j8249s8t4298t48efahsd9hdsa8h9ads8f9h0sf890asjd0f9js0ad9fjd0sa9fh089sadh8903408308hhe08ahfa0e8h08ahf98eayh93h98hq2asdfsdafdasfdsagina4165465lol";

//upload  image


$valid_mime_types = array(
    "image/gif",
    "image/png",
    "image/jpeg",
    "image/pjpeg",
);
 
// Check that the uploaded file is actually an image
// and move it to the right folder if is.
$file = $_FILES ['file'];
$name1 = $file ['name'];
$type = $file ['type'];
$ext = end(explode(".", $_FILES['file']['name']));
$size = $file ['size'];
$tmppath = $file['tmp_name']; 
$shuffle = substr(str_shuffle($basestring), 0, 20);
	$shuffle1 = substr(str_shuffle($basestring), 0, 25);
	$Name = "" .$shuffle. "_" .time(). "_" .$shuffle1. ""; 
	if($name1 != "")
	{
		if($type == "image/png" || $type == "image/pjpeg" || $type == "image/gif" || $type == "image/jpeg")
		{
			if($ext == "png" || $ext == "jpg" || $ext == "gif" || $ext == "jpeg")
			{
			move_uploaded_file ($tmppath, 'accounts/'.$User->UserInfo['id'].'/pictures/private/'.$Name.'.'.$ext);//image is a folder in which you will save image
		mysql_query("UPDATE `social_mail` SET `image`='accounts/".$User->UserInfo['id']."/pictures/private/".$Name.".".$ext."' WHERE `id`='".mysql_insert_id()."'");
			}
		}
	}

//close

$messagee = "http://sociobubble.com/conversation?with=".$User->UserInfo['id']."";
$User->AddNotification($_GET['with'],$User->UserInfo['id'],$_POST['message'], $messagee, 2);
header("location: conversation?with=".$_GET['with']."");
?>