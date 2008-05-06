/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

	TO USE IN VIRTUAL ADMIN ONLY : IT'S NOT FOR THE FRONT BECAUSE NEARLY DEPRECIATED ;)

*/


function page_loaded(evt) {
	if (evt) Event.stop(evt);
	$$('a[rel="popimg"]').each(function(e) {
		href = e.getAttribute('href');
		e.onclick = function () { popImg(href); return false; };
	});
}
Event.observe(window,'load',page_loaded,false);


// CMS
function genMenu() {
	if (confirm('Voulez-vous vraiment regénérer le menu et prendre en compte tous vos changements ?')) document.location.href = '../rubriques/xml_menu.php';
	else return false;
}

// TEST DOM
function testNavigator() {
	if (!document.getElementById) alert("Votre Navigateur n'est pas assez récent pour interpreter correctement les fonctions utilisées par cette administration\nNous conseillons d'utiliser Mozilla FireFox ou Internet Explorer");
}

// MENU MOVE OPTIONS
function MoveOption(sources,cible) { // Switch Up/Down
    var select_source = '';
    var select_cible = '';
	if (document.all) {
        select_source = eval("document.all."+sources);
        select_cible = eval("document.all."+cible);
    } else {
        select_source = document.getElementById(sources);
        select_cible = document.getElementById(cible);
    }
    for (x=0; x<select_source.length; x++) {
        if (select_source.options[x].selected && select_source.options[x].value != '') {
            select_cible.options[select_cible.options.length] = new Option(select_source.options[x].text,select_source.options[x].value);
            select_source.options[x] = null;
            x = x-1;
        }
    }
	var tmp = select_cible.name;
	var maReg = new RegExp( "select_fichier_ids_list", "g" );
	var match = tmp.match( maReg ) ;
	if ( ! match ) {
		for (x=0; x<select_cible.length; x++) {
			select_cible.options[x].selected = true;
		}
	}
}

// MULTI SELECT
function UpDownOption(sourceId,sens) { // Script réalisé par Eric Marcus - 2005
	var objListe = '';
	if (document.all) objListe = eval("document.all."+sourceId);
	else objListe = document.getElementById(sourceId);
	
	if (objListe.options.selectedIndex < 0) return false;
	var objLigneADeplacer = new Option(objListe.options[objListe.options.selectedIndex].text, objListe.options[objListe.options.selectedIndex].value);
	var iPositionAvant = objListe.options.selectedIndex;
	var iPositionApres=(sens=="down")?iPositionAvant+1:iPositionAvant-1;
	if ((iPositionApres>=objListe.length)||(iPositionApres<0)) return false;
	var objLigneAChanger = new Option(objListe.options[iPositionApres].text, objListe.options[iPositionApres].value);
	objListe.options[iPositionAvant] = objLigneAChanger;
	objListe.options[iPositionApres] = objLigneADeplacer;
	objListe.options[iPositionApres].selected=true;
	objListe.focus();
}

// SCROLL MENU
var floatX=0;
var floatY=0;
var lastY = 0;
var layerwidth=160;
var layerheight='auto';
var align='left';
var valign='top';
var delayspeed=3;
var NS6=false;
var IE4 = (document.all);
if (!IE4) { NS6 = (document.getElementById); }
var NS4 = (document.layers);

function adjust() {
	if (NS4 || NS6) {
		if (lastX==-1 || delayspeed==0) {
			lastX=window.pageXOffset + floatX;
			lastY=window.pageYOffset + floatY;
		}
		else {
			dx=Math.abs(window.pageXOffset+floatX-lastX);
			dy=Math.abs(window.pageYOffset+floatY-lastY);
			d=Math.sqrt(dx*dx+dy*dy);
			c=Math.round(d/10);
			if (window.pageXOffset+floatX>lastX) {lastX=lastX+delayspeed+c;}
			if (window.pageXOffset+floatX<lastX) {lastX=lastX-delayspeed-c;}
			if (window.pageYOffset+floatY>lastY) {lastY=lastY+delayspeed+c;}
			if (window.pageYOffset+floatY<lastY) {lastY=lastY-delayspeed-c;}
		}
		if (NS4) document.layers['floatlayer'].pageY = lastY;
		if (NS6)document.getElementById('floatlayer').style.top=lastY;
	}
	else if (IE4) {
		if (lastX==-1 || delayspeed==0) {
		lastX=document.body.scrollLeft + floatX;
		lastY=document.body.scrollTop + floatY;
		}
		else {
		dx=Math.abs(document.body.scrollLeft+floatX-lastX);
		dy=Math.abs(document.body.scrollTop+floatY-lastY);
		d=Math.sqrt(dx*dx+dy*dy);
		c=Math.round(d/10);
			if (document.body.scrollLeft+floatX>lastX) lastX=lastX+delayspeed+c;
			if (document.body.scrollLeft+floatX<lastX) lastX=lastX-delayspeed-c;
			if (document.body.scrollTop+floatY>lastY) lastY=lastY+delayspeed+c;
			if (document.body.scrollTop+floatY<lastY) lastY=lastY-delayspeed-c;
		}
		document.all['floatlayer'].style.posTop = lastY;
	}
	if (screen.availHeight>700) {setTimeout('adjust()',50);}
}
function define() {
	if ((NS4) || (NS6)) {
		if (align=="left") {floatX=ifloatX};
		if (align=="right") {floatX=window.innerWidth-ifloatX-layerwidth-20};
		if (align=="center") {floatX=Math.round((window.innerWidth-20)/2)-Math.round(layerwidth/2)};
		if (valign=="top") {floatY=ifloatY};
		if (valign=="bottom") {floatY=window.innerHeight-ifloatY-layerheight};
		if (valign=="center") {floatY=Math.round((window.innerHeight-20)/2)-Math.round(layerheight/2)};
	}
	if (IE4) {
		if (align=="left") {floatX=ifloatX};
		if (align=="right") {floatX=document.body.offsetWidth-ifloatX-layerwidth-20;}
		if (align=="center") {floatX=Math.round((document.body.offsetWidth-20)/2)-Math.round(layerwidth/2);}
		if (valign=="top") {floatY=ifloatY;}
		if (valign=="bottom") {floatY=document.body.offsetHeight-ifloatY-layerheight;}
		if (valign=="center") {floatY=Math.round((document.body.offsetHeight-20)/2)-Math.round(layerheight/2);}
	}
}

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
	else if (dailymotionvid.test(videoURL)) {
		videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")
	}
	else if(ifilmvid.test(videoURL)) {
		videoID1 = videoURL.replace(/http:\/\/www\.ifilm\.com\/video\/([a-z0-9])/i, "$1") 
	videoID = 'http://www.ifilm.com/efp?'+videoID1.replace(/\?(.*)?/, "")
	}
	else if (metacafevid.test(videoURL)) {
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

// Resize TexteArea In Fiche
var ResizingTextArea = Class.create();
ResizingTextArea.prototype = {
    defaultRows: 1,
    initialize: function(field)  {
        this.defaultRows = Math.max(field.rows, 1);
        this.resizeNeeded = this.resizeNeeded.bindAsEventListener(this);
        Event.observe(field, "click", this.resizeNeeded);
        Event.observe(field, "keyup", this.resizeNeeded);
    },
    resizeNeeded: function(event) {
        var t = Event.element(event);
        var lines = t.value.split('\n');
        var newRows = lines.length + 1;
        var oldRows = t.rows;
        for (var i = 0; i < lines.length; i++) {
            var line = lines[i];
            if (line.length >= t.cols) newRows += Math.floor(line.length / t.cols);
        }
        if (newRows > t.rows) t.rows = newRows;
        if (newRows < t.rows) t.rows = Math.max(this.defaultRows, newRows);
    }
};