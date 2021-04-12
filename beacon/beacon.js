/**
 * Beacon System
 * Written by Mark Ross <mark.ross@whitehatdev.com>
 * Copyright (c) whitehatdev 2013
 *
 *   This file is part of BeaconJS.
 *
 *   BeaconJS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   BeaconJS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with BeaconJS.  If not, see <http://www.gnu.org/licenses/>.
 *
 *   The GPL allows for this file to be used in it's unmodified form
 *   with your project without making the entire project opensource
 *   It is required that any changes to this file be made public.
 */
 
 /**
  * This file requires jQuery to function, You may obtain a copy from http://jquery.com.
  */

var epoch = 0;
var last_req = 0;
var feedreq = 1;

var Notifcation = { "regular": "", "friends": "", "messages": "" };
var Notifcation_count = "0";
var notifcationsound;

var base = "nweh8934yh9r8hd98wgiguigiudfveruyfeu754g74tg76ergrug38rg78rge7827ty47etge76degsjauwyeuashgasjhsehDJRINDFKDJRFKJWSIDSJODJIFDJFIDNIWJIQWERTYUIOPfuwgewtyu7rt47347tyy3eyey";

/**
 * Tick of our clock
 */
function Tick(donotupdate, request)
{
	if(donotupdate)
	{
		return;
	}
	epoch = epoch + 4;
	// As Mr Tidwell spotted...
	var unique = "";
	// Pick out 10 foxy chars (Making sure they are of age ;) we don't want to go down that road again)
	for(var i = 0; i < 10; i++)
	{
		// Random char!
		unique += base.charAt(Math.floor(Math.random() * base.length));
	}
	
	// Need's to be hard configured to the channel
	$.post("http://sociobubble.com/beacon/channel.php?usingepoch=1&unique=" + unique + "&time=" + epoch + "&lastreq=" + last_req,
	{ request:request },
	function(data)
	{
		var response = jQuery.parseJSON(data);
		if(response['epoch'] == null)
		{
			alert("Please refresh!");
			return;
		}
		epoch = parseInt(response['epoch']); // Make sure we are 100% up to date
		last_req = parseInt(response['epoch']);
		if(response['message'] != null)
		{
			console.log(response['message']);
		}
		if(response['alert'] != null)
		{
			alert(response['alert']);
		}
		if(response['navigate'] != null)
		{
			window.location = response['navigate'];
		}
		if(response['add'] != null)
		{
			for(var i = 0; i < response['add'].length; i++)
			{
				$(response['add'][i]['parent']).add(response['add'][i]['nooblet']);
			}
		}
		if(response['remove'] != null)
		{
			for(var i = 0; i < response['remove'].length; i++)
			{
				$(response['remove'][i]['tag']).remove();
			}
		}
		if(response['update'] != null)
		{
			for(var i = 0; i < response['update'].length; i++)
			{
				$(response['update'][i]['tag']).html(response['update'][i]['value']);
			}
		}
		if(response['prepend'] != null)
		{
			for(var i = 0; i < response['prepend'].length; i++)
			{
				$(response['prepend'][i]['tag']).prepend(response['prepend'][i]['value']);
			}
		}
		if(response['append'] != null)
		{
			for(var i = 0; i < response['append'].length; i++)
			{
				$(response['append'][i]['tag']).append(response['append'][i]['value']);
			}
		}
		if(response['isnewchatmessage'] == "yup")
		{
			notifcationsound.get(0).play();
		}
		Notifcation['regular'] = response['notifcations']['regular']['value'];
		Notifcation['friends'] = response['notifcations']['friends']['value'];
		Notifcation['messages'] = response['notifcations']['messages']['value'];
		
		$("div#notification").html(Notifcation['regular']);
		$("div#friendnotification").html(Notifcation['friends']);
		$("div#mesnotification").html(Notifcation['messages']);
		if(response['notifcations']['count'] != Notifcation_count && response['notifcations']['count'] != "0")
		{
			notifcationsound.get(0).play();
		}
		Notifcation_count = response['notifcations']['count'];
		$("div#notificationnumber").html(Notifcation_count);
		
		
	});
}

function Registerchatbox(id)
{
	$(id).keydown(
		function(event)
		{
    			if(event.keyCode == 13)
    			{
    				var longtittienipplevagina = id.replace("textarea#sendmessage_","");
    				//var data = $("sendmesform_" + longtittienipplevagina).serialize();
    				//$.post("page.php", data);
    				Tick(false, "{\"chatbox\":\"" + longtittienipplevagina + "\", \"chatmessage\":\"" + $(id).val() + "\"}");
    				$(id).val("");
    			}
    		}
    	);		
}
/**
 * On Document ready we shall call init.php to obtain the current epoch from the server
 * I'll set the time here for the epoch to be updated
 */
$(document).ready(
	function()
	{
		$.get("http://sociobubble.com/beacon/init.php",
		function(data)
		{
			epoch = parseInt(data);
			Tick(true, null);
			$('<audio id="Notifaudio"><source src="http://sociobubble.com/beacon/notification.mp3" type="audio/mpeg"><source src="http://sociobubble.com/beacon/notification.ogg" type="audio/ogg"></audio>').appendTo('body');
			notifcationsound = $("audio#Notifaudio");
		});
		setInterval("Tick(false, null)", 4000);
	}
);
function showchatscroll(id){
	$("#chatboxcontent_" + id).niceScroll({cursorcolor:"#7ccd7c"});
	$("#chatboxcontent_" + id).animate({ scrollTop: $(this).height() }, "slow");
}