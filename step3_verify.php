<?php
include "sqlconnect.php";
mysql_query("UPDATE `social_users` SET `city`='".mysql_real_escape_string($_POST['city'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
mysql_query("UPDATE `social_users` SET `bio`='".mysql_real_escape_string($_POST['bio'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
mysql_query("UPDATE `social_users` SET `country`='".mysql_real_escape_string($_POST['country'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
mysql_query("UPDATE `social_users` SET `state_region`='".mysql_real_escape_string($_POST['state_region'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
mysql_query("UPDATE `social_users` SET `interest`='".mysql_real_escape_string($_POST['interest'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
mysql_query("UPDATE `social_users` SET `occupation`='".mysql_real_escape_string($_POST['occupation'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
$basestring = "irwejig0jre903499our4t9ojwokiewnmlkfkwjr3213u21ok32df1i32hj1fuoed321l61e6i51ti31o21n365i1sad1iino321saurfbraollsmbballasandpenisva63465416541651651651651111111561561654196549849afsd196sda854498498as4d984a9s8f4ahkhfsakldfsdakjfsad54f85asd4f9s8da49f8ds4af984as99484fas9d49d8s4f9ds49f82a9s84df98sa42f984dsa9f492asd84f298as4d29f84as92d8429as8df4298as4fd29as84f29as8d4f2984asf98as4df29asd429f842as9d84f298sd4a2f98s4da2f98sd42a9f48sd9f4298asd4f29d8sa42f9d4s9fd42s9f84a9s2df49das84f9asd842f98asd4g89f4g9sh8498g4s89fj48g4sj9t84tuj9j8249s8t4298t48efahsd9hdsa8h9ads8f9h0sf890asjd0f9js0ad9fjd0sa9fh089sadh8903408308hhe08ahfa0e8h08ahf98eayh93h98hq2asdfsdafdasfdsagina4165465lol";
$id = mysql_query("SELECT * FROM `social_users` WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'") or die(mysql_error());
$fetch = mysql_fetch_array($id);
//upload  image
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
		if($ext == "png" || $ext == "jpeg" || $ext == "gif" || $ext == "pjpeg")
		{
		move_uploaded_file ($tmppath, 'accounts/'.$fetch['id'].'/profilepics/'.$Name.'.'.$ext);//image is a folder in which you will save image
		mysql_query("UPDATE `social_users` SET `profile_picture`='accounts/".$fetch['id']."/profilepics/".$Name.".".$ext."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
		}
	}
}
//close

mysql_query("UPDATE `social_users` SET `website`='".mysql_real_escape_string($_POST['website'])."' WHERE `email_token`='".mysql_real_escape_string($_POST['token'])."'");
header("location: index");

/*to change pic: $basestring = "irwejig0jre903499our4t9ojwokiewnmlkfkwjr3213u21ok32df1i32hj1fuoed321l61e6i51ti31o21n365i1sad1iino321saurfbraollsmbballasandpenisva63465416541651651651651111111561561654196549849afsd196sda854498498as4d984a9s8f4ahkhfsakldfsdakjfsad54f85asd4f9s8da49f8ds4af984as99484fas9d49d8s4f9ds49f82a9s84df98sa42f984dsa9f492asd84f298as4d29f84as92d8429as8df4298as4fd29as84f29as8d4f2984asf98as4df29asd429f842as9d84f298sd4a2f98s4da2f98sd42a9f48sd9f4298asd4f29d8sa42f9d4s9fd42s9f84a9s2df49das84f9asd842f98asd4g89f4g9sh8498g4s89fj48g4sj9t84tuj9j8249s8t4298t48efahsd9hdsa8h9ads8f9h0sf890asjd0f9js0ad9fjd0sa9fh089sadh8903408308hhe08ahfa0e8h08ahf98eayh93h98hq2asdfsdafdasfdsagina4165465lol";
$Name = substr(str_shuffle($basestring), 0, 10);
while(file_exists("accounts/".$User->UserInfo['id']."/profilepics/".$Name.".png")) { $Name = substr(str_shuffle($basestring), 0, 10); }
if($_FILES['file']['name'])
{
	move_uploaded_file($_FILES["file"]["tmp_name"], "accounts/".$User->UserInfo['id']."/profilepics/".$Name.".png");
}
mysql_query("UPDATE `Users` SET `profile_picture`='accounts/".$User->UserInfo['id']."/profilepics/".$Name.".png' WHERE `id`='".$User->UserInfo['id']."'") or die(mysql_error()); */
header("Location: index");
die;
?>
