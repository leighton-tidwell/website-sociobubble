<?php
	$query = mysql_query("SELECT * FROM `social_users` WHERE `id`='" .$User->UserInfo['id']. "'") or die(mysql_error());
	$fetch = mysql_fetch_array($query);
?>
	<ul class="navigation">
		<a href="home">
		<li><img src="/images/icons/50px/home.png" /></li>
		</a> <a href="messages">
		<li><img src="/images/icons/50px/messages.png" /></li>
		</a> <a href="<?php print "" .$User->UserInfo['username']. ""; ?>">
		<li><img src="/images/icons/50px/profile.png" /></li>
		</a> <a href="settings">
		<li><img src="/images/icons/50px/settings.png" /></li>
		</a> <a href="logout">
		<li><img src="/images/icons/50px/logout.png" /></li>
		</a>
	</ul>
</div>

<div id="friends">
	<center>
		Friends List <a style="cursor:pointer;" onclick="CloseFriendsList();">--></a>
	</center>
	<div id="footerbar"></div>
	<div id="friendsfeed">
		<center>
			<img id="loadgif2" src="images/ajax-loader.gif" />
		</center>
		</div>
</div>
<script>
function OpenFriendsList(){

el = document.getElementById("friends");
el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
document.getElementById("togglefriends").style.visibility = 'hidden';

}

function CloseFriendsList() {
    document.getElementById("friends").style.visibility = 'hidden';
    document.getElementById("togglefriends").style.visibility = 'visible';
}
</script>
<div onclick="OpenFriendsList();" id="togglefriends">
	<img src="images/icons/50px/togglefriends.png" />
</div>