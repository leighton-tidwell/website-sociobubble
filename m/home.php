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
if($User->UserInfo['first_name'] == "")
{
	mysql_query("UPDATE `social_users` SET `sessionkey`='DEAD' WHERE `sessionkey`='".session_id()."'") or die(mysql_error());
	unset($_SESSION['OK']);
	session_set_cookie_params(0);
	session_destroy();
	setcookie("PHPSESSID", time()-3600);
	session_regenerate_id(true);
	header("location: index");
}
?>
<?php
	include "header.php";
?>
<title>Sociobubble Home</title>
<script type="text/javascript">
$(document).ready(
 function()
 {
  $('div#shoutloader').hide();
  $('div#shoutloader').delay(5000).fadeIn();
 }
);
var loadme = true;
function Loadfeedresults(){
	if(loadme == true){
		loadme == false;
 $('div#feed_loader').show();
 $.ajax({
 url: "feed_load_more_results.php?last_post="+ $(".feedItem:last").attr('id') ,
 success: function(html) {
 if(html){
 $('div#feed_loader').hide();
 $("#feed").append(html);
 }else{
 $('div#loadmore').hide();
 $('div#feed_more').fadeIn();
 $('div#feed_loader').hide();
 }
 }
 });
 }
}
</script>
</head>

<?php
	include "navigation.php";
?>
<div id="content">
  <div id="shout"> <font  size="5">Shout out!</font>
    <form  method="post" action="shout.php" enctype="multipart/form-data">
      <textarea name="shout" class="shoutbox" ></textarea><br />
      Add an image &raquo;<input type="file" placeholder="Upload" name="file"><br />
      Set a mood &raquo; <input type="text" id="mood" class="mood" name="mood" placeholder="Type a mood *optional" maxlength="20"><br />
      <input type="submit" class="shoutbox_submit" name="submit" value="SHOUT!">&nbsp;
    </form>
  </div>
  <div id="shout_divider"></div>
  <div id="feed">
  		<center>
        	<img src="http://sociobubble.com/images/ajax-loader.gif" />
        </center>
  </div>
  <div style="margin-bottom: 10px;" class="noshouts" id="shoutloader">
  	<div id="loadmore">
    	<a style="cursor:pointer;" onClick="Loadfeedresults();">Load More Shouts</a>
    </div>
   	<div id="feed_more" style="display:none;">No More Shouts</div>
    <div id="feed_loader" style="display:none;">
        <center>
            <img src="http://sociobubble.com/images/ajax-loader.gif" />
        </center>
      </div>
  </div>
</div>
