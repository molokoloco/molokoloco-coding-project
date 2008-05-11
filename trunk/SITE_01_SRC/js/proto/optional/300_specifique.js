/////////// SPECIFIQUES //////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Ici les fonctions spécifiques au site
*/

// ------------------------- ONLOAD INIT JS ---------------------------------- //
var myLightWindow = null;

var initJS = function(evt) {
	ajaxMenu(evt);
	scrollNews(evt);
	//myLightWindow = new lightwindow();
	//setTimeout("if (isIE) addIEReflex(); else addReflex();", 1000);
	
	if (evt) Event.stop(evt);
}
Event.observe(window, 'load', initJS);


// ------------------------- REQUIRE :) ---------------------------------- //
if (typeof Element == 'undefined') throw('specifique.js requires prototype.js library');

// ------------------------- FORMULAIRES ---------------------------------- //
// Index.php - Form contact
contactSubmit = function() {
	param_contact = { mep: 'message', autoScroll: false, action: 'submit' };
	champs_contact = {
		contact_nom: {type:'', alerte:'Le nom est obligatoire'},
		contact_prenom: {type:'', alerte:'Le pr&eacute;nom est obligatoire'},
		contact_email: {type:'mel', alerte:'L\'email est obligatoire et doit &ecirc;tre valide'},
		contact_message: {type:'', alerte:'Le message est obligatoire'}
	};
	formVerif('frm_contact', champs_contact, param_contact);
}

// ------------------------- INFOS SCROLL P ---------------------------------- //
// var arrAccroche is from index.php
var tempo = 5;
var inc = 0;
var totP = 0;
var scrollNews = function(evt) {
	if (!isId('info_scroll')) return;
	// Compute array length
	for (var rant in arrAccroche) totP++;
	// Put first P, erase existant (id_0)
	var reg = new RegExp("#", "g");
	var innerHTL = '<a href="'+arrAccroche[inc]['goto']+'"><strong>'+arrAccroche[inc]['titre']+'</strong></a>';
	innerHTL += '<li>'+arrAccroche[inc]['texte'].replace(reg, '</li><li>')+'</li>';
	$('info_scroll').update(getTpl('div', {id:'div_scroll_'+inc, style:'display:none', inner:innerHTL})); //arrAccroche[inc]['id']
	Effect.Appear('div_scroll_'+inc);
	inc = 1;
	var backDivId = 0;
	new PeriodicalExecuter( function() {
		// Scroll Previous
		if (inc == 0) backDivId = totP - 1;
		else backDivId = inc - 1;
		if (isId('div_scroll_'+backDivId)) {
			var eHeight = Element.getHeight('div_scroll_'+backDivId);
			//Effect.MoveBy('div_scroll_'+backDivId, -parseInt(eHeight), 0, {duration:0.5, afterFinish:function(e){$(e).element.remove();}});
			Effect.Fade('div_scroll_'+backDivId, {duration:0.5, afterFinish:function(e){
				$(e).element.remove();
				// Build Next
				var innerHTL = '<a href="'+arrAccroche[inc]['goto']+'"><strong>'+arrAccroche[inc]['titre']+'</strong></a>';
				innerHTL += '<li>'+arrAccroche[inc]['texte'].replace(reg, '</li><li>')+'</li>';
				new Insertion.Bottom('info_scroll', getTpl('div', {id: 'div_scroll_'+inc, style:'display:none', inner: innerHTL}));
				Effect.Appear('div_scroll_'+inc);
			}});
		}
		if (inc >= (totP-1)) inc = 0;
		else inc++;
	}, tempo);
};
//Event.observe(window, 'load', scrollNews);

// ------------------------- AJAX MENU NAVIGATION ---------------------------------- //
var getAjaxUrl = function(e) {
	eUrl = e.getAttribute('href');
	if (eUrl.match(/\#/)) {
		eUrl = eUrl.split('#');
		eUrl = eUrl[0]+'?ajax=1#'+eUrl[1];
	}
	else if (!eUrl.match(/\?/)) eUrl += '?ajax=1';
	else eUrl += '&amp;ajax=1';
	$$('#menu li.current_page').each(function(t) { Element.removeClassName(t, 'current_page'); });
	$($(e).parentNode).className = 'current_page';
	eUrl = eUrl.split('?');
	var laRequete = new Ajax.Request(eUrl[0], {
		method: 'get',
		evalScripts: true,
		parameters: eUrl[1],
		onSuccess: function(transport) {
			$('content').update(transport.responseText);
			// Add new Call to some fonctions // Wait loading images
		}
	});
	return false;
};
var ajaxMenu = function(evt) {
	if (!isId('menu')) return;
	$$('#menu a').each(function(e) {
		$(e).setAttribute('onclick', 'return getAjaxUrl(this);');
		$(e).setAttribute('ondblclick', 'redir(this.href); return false;');
	});
};
//Event.observe(window, 'load', ajaxMenu);



// ------------------------- MINI GAL ARTICLE ---------------------------------- //
var imageSrc = '';
var setGalerieImage = function(e) {
	if (imageSrc == '') imageSrc = $('art_image_1').src;
	if (isSet(this.src)) {
		pathZoom = strRep(this.src, '/mini/', '/grand/');
		pathZoom = strRep(pathZoom, '/medium/', '/grand/');
		$('art_image_1').src = pathZoom;
	}
};
var unsetGalerieImage = function(e) {
	$('art_image_1').src = imageSrc;
};
var initGalerieImage = function() {
	var conteneur = $$('#article_galerie img');
	conteneur.each( function(e) {
		Event.observe(e, 'mouseover', setGalerieImage);
		Event.observe(e, 'mouseout', unsetGalerieImage);
		pathZoom = strRep($(e).src, '/mini/', '/grand/');
		pathZoom = strRep(pathZoom, '/medium/', '/grand/');
		loadImg(pathZoom);
	});
};
swfobject.addDomLoadEvent(function() {
	if (isId('article_galerie')) initGalerieImage();
});


// -------------------- PROMPT VIDEO CODE -------------------- //
function promptVideo(myInput) {
	var videoURL = prompt("Collez l\'url compl&egrave;te de la page de votre vid&eacute;o (Exception pour Dailymotion et Wat, il faut mettre le code embed)\nSites reconnus : DAILYMOTION, GOOGLE, YOUTUBE, METACAFE, iFILM, WAT, MYSPACE... :", '');
	if (!videoURL) {
		alert('D&eacute;sol&eacute; nous n\'avons pas r&eacute;ussi &agrave; identifier le code ni &agrave; en extraire l\'ID');
		//$(myInput).value = '';
		$(myInput).blur();
		return;
	}
	
	// Let's find the path to direct swf player of each site
	var videoID = '';
	// SITES CONNUS // OK SWF // Great Code from "anarchy media player" : http://an-archos.com/anarchy-media-player
	mediavid = new RegExp("^http:\/\/(.*)(\.mp3$|\.flv$|\.mov$|\.mp4$|\.m4v$|\.m4a$|\.m4b$|\.3gp$|\.wmv$|\.avi$|\.asf$)");
	googlevid = new RegExp("video\.google\.(.*)?\/videoplay");
	youtubevid = new RegExp("youtube\.com\/watch");
	dailymotionvid = new RegExp("dailymotion\.com\/swf");
	ifilmvid = new RegExp("ifilm\.com\/video\/");
	metacafevid = new RegExp("metacafe\.com\/watch\/");
	goearvid = new RegExp("goear\.com\/listen\.php");
	myspacevid = new RegExp("vids\.myspace\.com\/index\.cfm");
	ipodplayer = new RegExp("ax\.phobos\.apple\.com\.edgesuite\.net\/flash\/feedreader\.swf");
	atomvid = new RegExp("atomfilms\.com(:80)?\/film");
	breakvid = new RegExp("embed\.break\.com\/");

	if(mediavid.test(videoURL)) {
		videoID = videoURL;
	}
	else if(googlevid.test(videoURL)) {
	videoID1 = videoURL.replace(/\.(.*)\/videoplay/, '.google.com/googleplayer.swf')
	videoID = videoID1.replace(/&(.*)?/, "")		
	}
	else if(youtubevid.test(videoURL)) {
	videoID1 = videoURL.replace(/watch\?v\=/, 'v/')
	videoID = videoID1.replace(/&(.*)?/, "")	
	}
	else if(dailymotionvid.test(videoURL)) {
	videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")
	}
	else if(ifilmvid.test(videoURL)) {
	videoID1 = videoURL.replace(/http:\/\/www\.ifilm\.com\/video\/([a-z0-9])/i, "$1") 
	videoID = 'http://www.ifilm.com/efp?'+videoID1.replace(/\?(.*)?/, "")
	}
	else if(metacafevid.test(videoURL)) {
	videoID1 = videoURL.replace(/watch/i, "fplayer") 
	videoID = videoID1.replace(/\/$/, ".swf")	
	}
	else if(goearvid.test(videoURL)) {
	videoID1 = videoURL.replace(/http:\/\/www\.goear\.com\/listen\.php\?v=([a-z0-9])/i, "$1") 
	videoID = 'http://www.goear.com/files/localplayer.swf?file='+videoID1.replace(/\&(.*)?/, "")
	}
	else if(myspacevid.test(videoURL)) {
	videoID = videoURL.replace(/http:\/\/vids\.myspace\.com\/index\.cfm\?fuseaction=vids\.individual&videoid=([a-z0-9])/i, "$1")
	videoID = 'http://lads.myspace.com/videos/vplayer.swf?m='+videoID;
	}		
	else if(ipodplayer.test(videoURL)) {
	videoID = videoURL.replace(/^(.*)src=\"(.*)\" quality=\"(.*)$/i, "$2")	
	}
	else if(atomvid.test(videoURL)) {
	videoID = videoURL.replace(/http:\/\/www\.atomfilms\.com(:80)?\/film\/(.*)/i, "$2") 
	videoID = videoID.replace(/\?(.*)?/, "")
	videoID = videoID.replace(/\.jsp/, "")
	videoID = 'http://www.atomfilms.com:80/a/autoplayer/shareEmbed.swf?keyword='+videoID;	
	}
	else if(breakvid.test(videoURL)) {
	videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")	
	}

	if (videoID != '') {
		$(myInput).value = videoID;
	}
	else {
		alert('D&eacute;sol&eacute; nous n\'avons pas r&eacute;ussi &agrave; identifier le code ni &agrave; en extraire l\'ID');
		//$(myInput).value = '';
		promptVideo(myInput); // Re ?
	}
}

// ------------------------- HORLOGE ---------------------------------- //
var startTimeTime;
var startTime = function(e) {
	var digits = new Date();
	if (digits.getMilliseconds() > 500) {
		e.stop();
		new PeriodicalExecuter(displayTime, 1);
	}
};
var displayTime = function() {
	if (!isId('menu_date_heure')) return;
	var digits = new Date();
	var hours = digits.getHours();
	var minutes = digits.getMinutes();
	var seconds = digits.getSeconds();
	if (hours <= 9) hours = '0'+hours;
	if (minutes <= 9) minutes = '0'+minutes;
	if (seconds <= 9) seconds = '0'+seconds;
	$('menu_date_heure').update(hours+':'+minutes+':'+seconds);
};
swfobject.addDomLoadEvent(function() {
	new PeriodicalExecuter(startTime, 0.0001);
});