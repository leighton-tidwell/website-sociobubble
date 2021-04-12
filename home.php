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
<!--Code Written by: Leighton Tidwell -->
<!--Code Designed for: Social Network -->
<?php
	include "header.php";
?>
<script type="text/javascript">
$(document).ready(
 function()
 {
  $('div#shoutloader').hide();
  $('div#shoutloader').delay(5000).fadeIn();
 }
)
$(document).ready(function() {
	$("a.feedimage").fancybox({    
		beforeLoad: function () {
			var el, id = $(this.element).data('title-id');
		
			if (id) {
			    el = $('#' + id);
		
			    if (el.length) {
				this.title = el.html();
			    }
			}
       		 },
		prevEffect	: 'elastic',
		nextEffect	: 'elastic',
		helpers	: {
			title	: {
				type: 'float'
			},
			thumbs	: {
				width	: 50,
				height	: 50
			},
			overlay : {
				closeClick : true,  // if true, fancyBox will be closed when user clicks on the overlay
				speedOut   : 200,   // duration of fadeOut animation
				showEarly  : true,  // indicates if should be opened immediately or wait until the content is ready
				css        : {
						'background' : 'rgba(0, 0, 0, 0.6)'
					},    // custom CSS properties
				locked     : true   // if true, the content will be locked into overlay
		}
		}
	});
});

</script>
<link rel="stylesheet" type="text/css" href="css/home.css" />
<title>SocioBubble | Home</title>
</head>

<?php
	include "banner.php";
?>
<div id="content">
  <div id="shout">
    <form id="shoutformpost"  method="post" action="shout.php" enctype="multipart/form-data">
      <textarea placeholder="Whats up, <?php print $User->UserInfo['first_name'] ?>?" name="shout" class="shoutbox" ></textarea>
      <input type="submit" class="shoutbox_submit" name="submit" value="Post">&nbsp;
      |&nbsp;
      <input type="file" placeholder="Upload" name="file">
      &nbsp;|&nbsp;<input type="text" id="mood" class="mood" name="mood" placeholder="How are you feeling?" maxlength="20">
    </form>
  </div>
  <div id="shout_divider"></div>
  <div id="feed">
  		<center>
        		<img id='loadgif' src="images/ajax-loader.gif" />
        	</center>
  </div>
  <div style="margin-bottom: 10px; margin-top: 10px;" class="noshouts" id="shoutloader">
  <script>
  	function loadmoreyoufucker()
  	{
  		Tick(true, '{ "feed": { "count": ' + feedreq + ' } }');
  		feedreq++;
  	}
  	</script>
  	<div id="loadmore">
    	<a style="cursor:pointer;" onClick="loadmoreyoufucker();">Load More Shouts</a>
    </div>
   	<div id="feed_more" style="display:none;">No More Shouts</div>
    <div id="feed_loader" style="display:none;">
        <center>
            <img src="images/ajax-loader.gif" />
        </center>
      </div>
  </div>
</div>
<div id="amodule">
	The ads can go here, m8.
</div>
<?php
	include"navigation.php";
	include "chatbar.php";
?>