<?php
include "sqlconnect.php";
include "Class.User.php";
session_start();
if(isset($_SESSION['OK']))
{
	$User = new User();
}
else
{
	header("location: index");
	exit;
}
if($_GET['feeditem'] == "")
{
	header("location: home");
	exit;
}
else
{
	$reported = $_GET['feeditem'];
}
if($_GET['fail'] == "true")
{
	$fail = "<font color='red'><center>You didn't fill in all the information!</center></font>";
}
if($_GET['fail'] == "false")
{
	header("location: home");
	exit;
}
?>
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->

<?php
	include "header.php";
?>
<script type="text/javascript">
function popup()
	{
		alert("Information submitted! If no errors present you will be redirected to home!");
	}
</script>
<title>SocioBubble | Report a post</title>
</head>
<?php
	include "banner.php";
?>
<div id="content2">
	<div id="reportbox">
    	<center>
        	<font size="16px">
    			Report This Shout
            </font>
            <?php print $fail ?>
        </center>
    	<div id="reportform">
        <form method="post" action="report_verify.php?id=<?php print $reported ?>">
        	Reason<br>
            <input type="radio" value="spam_scam" name="reason">Spam or scam<br>
            <input type="radio" value="nudity_porn" name="reason">Nudity or pornography<br>
            <input type="radio" value="violence" name="reason">Graphic violence<br>
            <input type="radio" value="hate_speech_symbol" name="reason">Hate speech or symbol<br>
            <input type="radio" value="illegal_drug_use" name="reason">Illegal drug use<br>
            Comment<br>
            <textarea type="text" class="comment_reportbox" name="comment"></textarea>
            <input type="submit" onClick="popup();" class="report_sub" value="Submit">
        </form>
        </div>
    </div>
</div>
<?php
	include "navigation.php";
	include "chatbar.php";
	include "footer.php";
?>