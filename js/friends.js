$(document).ready(
 function()
 {
	 LoadFeed();
  setInterval("LoadFeed();", 60000);
 }
);

function LoadFeed()
{
 $.get("friends_feed.php",
  function(data)
  {
   $('div#friends').html(data);
  }
 );
}