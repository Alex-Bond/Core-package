//	Uppod.AJAX 0.7 for Uppod.Player (http://uppod.ru/player/ajax/)  
//	based on 1pixelout plugin
//	!!!test only on server!!!

	var uppod_instances = new Array();
	var uppod_instances_id = new Array();

	// SETTINGS
	var uppod_play_next=0; // set 1 for autoplay next player
	
	//*******************************************
	// EVENTS
	//*******************************************
	
	//start
	function uppodStartsReport(playerID) {
		//alert(playerID);
	}
	//file not found
	function uppodErrorReport(playerID) {
		alert(playerID);
	}
	// the end
	function uppodTheEnd(playerID) {
		//alert('end'+playerID);
		if(uppod_play_next==1){
			if(uppod_instances_id[playerID]<uppod_instances.length-1){
				document.getElementById(uppod_instances[uppod_instances_id[playerID]+1]).sendToUppod('play');
			}
			else{
				document.getElementById(uppod_instances[0]).sendToUppod('play');
			}
		}
	}
	//file onLoad (set in style > Plugins > Uppod.AJAX)
	function uppodOnLoad(playerID) {
		//alert(playerID);
	}
	//file OnDownload (set in style > Plugins > Uppod.AJAX)
	function uppodOnDownload(playerID) {
		//alert(playerID);
	}
	//*******************************************
	// COMMAND - stop all players except one (playerID)
	//*******************************************
	function uppodStopAll(playerID) { 
	
		for(var i = 0;i<uppod_instances.length;i++) {
			try {
				if(uppod_instances[i] != playerID){
					document.getElementById(uppod_instances[i]).sendToUppod("stop");
				}
			}
			catch( errorObject ) {
			}
		}
	}
	
	// COMMANDS **********************************
	// play
	// pause
	// toggle - switch (play/pause)
	// stop
	// startN - play N file from playlist (1,2,3...) example - start3, start21
	// vN - set volume 0-100 (v0 - v100)
	// file:URL - play custom file
	// pl:{} - push to playlist
	// text:TEXT - view text with close icon
	// text2:TEXT - view text without close icon
	// getpl - return num of current item in playlist (uppodGetNpl)
	// getv - return volume (uppodGetVolume)
	// getime - return time position (uppodGetTime)
	// getimed - return time position (uppodGetTimeDuration)
	// getstatus - return status (play 1, pause 0)
	// getfull - return fulscreen status (1 / 0)
	
	// ------- Uppod 0.5
	// xtext - close text message
	// xpl - close inside playlist
	// random - on / off random
	// fullscreen - on / off fulscreen (video, photo)
	// seek:sec - seek (peremotka - sek:30)
	// getbytestotal - return the total size, in bytes (uppodGetBytesTotal)
	// getbytesloaded - return the loaded size, in bytes (uppodGetBytesLoaded)
	// getprocent - return procent of loading (0-1) (uppodGetProcent)
	
	
	
	//********************************************
	// com - COMMAND
	// callback - js callback function
	function uppodSend(playerID,com,callback) {
		document.getElementById(playerID).sendToUppod(com,(callback?callback:''));
	}	
	
	//*******************************************
	// RETURN 
	//*******************************************
	// current item in playlist
	function uppodGetNpl(n,playerID) { 
		alert(n);
	}
	// volume
	function uppodGetVolume(n,playerID) { 
		alert(n);
	}
	// time position
	function uppodGetTime(n,playerID) {
		alert(n);
	}
	// time duration
	function uppodGetTimeDuration(n,playerID) { 
		alert(n);
	}
	// status 
	function uppodGetStatus(n,playerID) { 
		alert(n);
	}
	// fullscreen
	function uppodGetFullScreen(n,playerID) { 
		document.getElementById('alert').innerHTML+=n;
	}
	// time duration
	function testCallback(n) { 
		alert('Hello, world!');
	}
	/////////////////////////////////////////////
	// 	find players on the page
	function uppodPlayers() { 
		var objectID;
		var objectTags = document.getElementsByTagName("object");
		for(var i=0;i<objectTags.length;i++) {
			objectID = objectTags[i].id;
			if(objectID.indexOf("player") >-1&uppod_instances.indexOf(objectID)==-1) {
				uppod_instances[i] = objectID;
				uppod_instances_id[objectID]=i;
			}
		}
	}
	// call after loading player
	function uppodInit(playerID) {
		uppodPreloader(playerID); // preloaders on
	}
	// call after loading playlist
	function uppodPL(playerID){
		/*if(playerID=='videoplayer3'){
			uppodSend(playerID,'start3');
		}*/
	}
	// player done (hide preloader)
	function uppodPreloader(playerID) {
		document.getElementById(playerID+"Preloader")?document.getElementById(playerID+"Preloader").style.display="none":'';
		document.getElementById(playerID+"Box")?document.getElementById(playerID+"Box").style.position="static":'';
	}
	// create Array.indexOf for old IE
	if(!Array.indexOf){ 
		Array.prototype.indexOf = function(obj){
		for(var i=0; i<this.length; i++){
			if(this[i]==obj){
				return i;
				}
			}
			return -1;
			}
	}
	var ap_uppodID = setInterval(uppodPlayers, 1000);