<body class="fixed">
<script>
$(document).ready(
 function()
 {
  $('#notification').niceScroll({cursorcolor:"#7ccd7c"});
 }
);
</script>
<div id="header2">
  <div class="logo">
  	<a href="home" title="SocioBubble Home" alt="SocioBubble Home"><img src="images/logo.png" /></a>
  </div>
 <a href="<?php print $User->UserInfo['username'] ?>">
 	<div id="quickbar"><img class="quickbarpic" src="p.php?src=<?php print $User->UserInfo['profile_picture'] ?>&w=40&h=40">
		<span class="quickbartext">
			<?php print $User->UserInfo['first_name'] ?> <?php print $User->UserInfo['last_name'] ?>
		</span>
  </div>
 </a>
 <script>
function opennot(){
		el = document.getElementById("notificationdiv");
		el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
		document.getElementById("ascrail2000").style.display='none';
		regularnot();
		if(el.style.visibility == "visible")
		{
			document.getElementById("ascrail2000").style.display='inherit';
			markreadreg();
		}
}
function regularnot(){
	var message = 0;
	var friends = 0;
	var notification = 1;
	document.getElementById("notification").style.display='inherit';
	if(message = 1)
	{
		document.getElementById("mesnotification").style.display="none";
		message = 0;
	}
	if(friends = 1)
	{
		document.getElementById("friendnotification").style.display="none";
		friends = 0;
	}
}
function messagenot(){
	message = 1;
	document.getElementById("mesnotification").style.display="inherit";
	if(friends = 1)
	{
		document.getElementById("friendnotification").style.display="none";
		friends = 0;
	}
	if(notification = 1)
	{
		document.getElementById("notification").style.display="none";
		notification = 0;
	}
}
function friendnot(){
	friends = 1;
	document.getElementById("friendnotification").style.display="inherit";
	if(message = 1)
	{
		document.getElementById("mesnotification").style.display="none";
		message = 0;
	}
	if(notification = 1)
	{
		document.getElementById("notification").style.display="none";
		notification = 0;
	}
}


function markread(ID)
{
 $.post("markread.php?id="+ID
 );
}
function markreadreg()
 {
  $.post("markread.php?type=regular"
 );
}
</script>
 <div onClick="opennot();" id="notifications">
 	<div id="notificationnumber">
	</div>
 </div>
 <div id="notificationdiv">
 	<div class="notificationnav">
		<a onClick="regularnot();">Regular</a> | <a onClick="messagenot();">Messages</a> | <a onClick="friendnot();">Request</a>
	</div>
	<div id="notification">
		<center>
			<img src="images/ajax-loader.gif">
		</center>
	</div>
	<div id="mesnotification">
		<center>
			<img src="images/ajax-loader.gif">
		</center>
	</div>
	<div id="friendnotification">
		<center>
			<img src="images/ajax-loader.gif">
		</center>
	</div>
 </div>
<!-- 
  <div id="peepsearch">
    <form>
      <input type="text" class="people_search" name="search" placeholder="Search anything here!">
      <input type="submit" value="Search" class="peepsubmit">
    </form>
  </div>
  -->
</div>
