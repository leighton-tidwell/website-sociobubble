<div class="header">
	<div class="navigation">
		<a href="home"><img src="images/icons/50px/home.png"></a>
		<a href="#" onClick="expandsettings();"><img src="images/icons/50px/settings.png"></a>
		<a href="#" onClick="expandnotifications();"><img src="images/icons/50px/notifications.png"></a>
		<a href="#" onClick="expandmessages();"><img src="images/icons/50px/messages.png"></a>
	</div>
</div>
<script type="text/javascript">
var open = false;
var divopen = 0;
  function expandsettings(){
	  if(open){
	  	  var div = document.getElementById( 'settingsmenu' );
		  open = false;
		  div.style.display = "none";
	  }
	  else
	  {
		  if(!(divopen==0)){
		  	if(divopen == document.getElementById( 'settingsmenu' )){
				 open = false;
		 		 divopen.style.display = "none";
			 }
			 if(divopen == document.getElementById( 'notificationsmenu' )){
				 open1 = false;
		 		 divopen.style.display = "none";
			 } 
			 if(divopen == document.getElementById( 'messagesmenu' )){
				 open2 = false;
		 		 divopen.style.display = "none";
			 }
		  }
		  divopen = document.getElementById( 'settingsmenu' );
	  	  var div = document.getElementById( 'settingsmenu' );
	  	  open = true;
		  div.style.display = "block";
	  }
  }
var open1 = false;
  function expandnotifications(){
	  if(open1){
	  	  var div = document.getElementById( 'notificationsmenu' );
		  open1 = false;
		  div.style.display = "none";
	  }
	  else
	  {
		 if(!(divopen==0)){
		 	 if(divopen == document.getElementById( 'settingsmenu' )){
				 open = false;
		 		 divopen.style.display = "none";
			 }
			 if(divopen == document.getElementById( 'notificationsmenu' )){
				 open1 = false;
		 		 divopen.style.display = "none";
			 } 
			 if(divopen == document.getElementById( 'messagesmenu' )){
				 open2 = false;
		 		 divopen.style.display = "none";
			 }
		  }
		  divopen = document.getElementById( 'notificationsmenu' );
	  	  var div = document.getElementById( 'notificationsmenu' );
	  	  open1 = true;
		  div.style.display = "block";
	  }
  }
var open2 = false;
  function expandmessages(){
	  if(open2){
	  	  var div = document.getElementById( 'messagesmenu' );
		  open2 = false;
		  div.style.display = "none";
	  }
	  else
	  {
		  if(!(divopen==0)){
		 	 if(divopen == document.getElementById( 'settingsmenu' )){
				 open = false;
		 		 divopen.style.display = "none";
			 }
			 if(divopen == document.getElementById( 'notificationsmenu' )){
				 open1 = false;
		 		 divopen.style.display = "none";
			 } 
			 if(divopen == document.getElementById( 'messagesmenu' )){
				 open2 = false;
		 		 divopen.style.display = "none";
			 }
		  }
		  divopen = document.getElementById( 'messagesmenu' );
	  	  var div = document.getElementById( 'messagesmenu' );
	  	  open2 = true;
		  div.style.display = "block";
	  }
  }
</script>
<div id="settingsmenu">
	<a href="#">Account</a><br />
	<a href="#">Privacy</a><br />
	<a href="#">Notifications</a><br />
	<a href="http://sociobubble.com/home">Desktop Site</a><br />
</div>
<div id="notificationsmenu">
	<center>
        	<img src="http://sociobubble.com/images/ajax-loader.gif" />
        </center>
</div>
<div id="messagesmenu">
	<center>
        	<img src="http://sociobubble.com/images/ajax-loader.gif" />
        </center>
</div>
