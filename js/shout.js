var ShallI = true;

$(document).ready(
 function()
 {
 }
);
function updatevar()
{
	ShallI = false;
}
function updatevar1()
{
	ShallI = true;
}

function PostShout()
{
 alert("Shouts are not working at this time.");
}
function LoadList()
{
 $.get("friends_feed.php",
  function(data)
  {
   $('div#friendsfeed').html(data);
  }
 );
}

function DeleteShout(ID)
{
 $.get("delete_shout.php?postid="+ID,
  function(data)
  {
   $("div#feeditem-" + ID).animate({"width":0, "height":0, "opacity":"0.5"}, "slow", function() { $(this).delay(100).remove(); });
   $("div.commentbox-" + ID).animate({"width":0, "height":0, "opacity":"0.5"}, "slow", function() { $(this).delay(100).remove(); });
  }
 );
}

function showcomments(ID, NAME)
{
 if($("div#like-list-" + ID).is(":hidden")){
	   var text2 = $("div#like-list-" + ID).text();
	   $("div#like-list-" + ID).text(NAME + text2);   
	   $("div#like-list-" + ID).slideDown("fast");
   } else{
	 var val = $("div#like-list-" + ID).text();
	    if ( val.indexOf(NAME) !== -1 ){
		$("div#like-list-" + ID).text(val.replace(NAME, ''));     
	    }
	$("div#like-list-" + ID).hide();
   }
}

function minimizechat(ID)
{
 if($("div#chatwrap_" + ID).is(":hidden")){
	   $("#minmaxize_" + ID).text('-');
	   $("div#chatwrap_" + ID).css({"display":"block"});
	   $("div#chatbox_" + ID).css({"margin-top":"0px"});
	   showchatscroll(ID);
	   $.post("togglechatopt.php?convoid=" + ID + "&minimize=0");
   } else{
	$("div#chatwrap_" + ID).hide();
	$("#minmaxize_" + ID).text('+');
	$("div#chatbox_" + ID).css({"margin-top":"272px","width":"auto"});
	$.post("togglechatopt.php?convoid=" + ID + "&minimize=1");
   }
}

function closechat(ID)
{
 if($("div#chatbox_" + ID).is(":hidden")){
	   $("div#chatbox_" + ID).css({"display":"block"});
	   $.post("togglechatopt.php?convoid=" + ID + "&close=0");
   } else{
	$("div#chatbox_" + ID).hide();
	$.post("togglechatopt.php?convoid=" + ID + "&close=1");
   }
}

function DeleteComment(ID)
{
 $.get("delete_comment.php?commentid="+ID,
  function(data)
  {
   $("div#comment-" + ID).animate({"width":0, "border":"none", "height":0, "opacity":"0.5"}, "slow", function() { $(this).delay(100).remove(); });
  }
 );
}

function RateShout(ID)
{
 $.post("rate_shout.php?postid="+ID,
  function(data)
  {
   LoadFeed();
  }
 );
}

function ShowComments(ID)
{
   if($("div#feeditem-" + ID + ".commentbox").is(":hidden")){
	   $("div#feeditem-" + ID + ".commentbox").slideDown("fast");
	   $("div#feeditem-" + ID).css({"margin-bottom":"0px"});
   } else{
	$("div#feeditem-" + ID + ".commentbox").hide();
	$("div#feeditem-" + ID).css({"margin-bottom":"20px"});
   }
}