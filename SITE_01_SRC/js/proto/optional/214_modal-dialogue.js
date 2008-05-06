/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/
/*
Functions :

	fixDialog(msg)
	nativeAlert = window.alert;
	window.alert(msg) = nativeAlert(fixDialog(msg))
	nativePrompt = window.prompt;
	window.prompt(msg, defaultValue) = nativePrompt(fixDialog(msg), fixDialog(defaultValue));
	nativeConfirm = window.confirm;
	window.confirm(msg) = nativeConfirm(fixDialog(msg));
	
	openWindow(url, options)						: href="index.php" onclick="return openWindow(this, {width:790,height:450,center:true});"
	myPop(url, winName, Wwide, Whigh) 				: href="pop_media.php" onClick="return myPop('pop_media.php', 'pop', 260, 120);"
	popImg(imgPath) 								: href="img/media.jpg" onClick="return popImg('img/media.jpg');"
	
	startOverlay()
	removeOverlay()
	removePrintInfo()
	removeFadePrintInfo()
	startLightBox(w,h)
	
	printInfo(infosHtml)
	printPage('_popup_login.php', {title:'hello', boxWidth:'380', options.height:'260', beforeStart:kill, afterFinish:redir});

/////////////////////////////////////////////////////////////////////////////////////////////////////// */

if (typeof db == 'undefined') die("214_modal-dialogue.js requires 200_tools.js");
if (typeof Effect == 'undefined') die("214_modal-dialogue.js requires 110_effects.js");


// ------------------------- FIX DIALOGUE BOX ---------------------------------- //
var fixDialog = function(msg) {
    var result = msg.toString();
    result = result.stripScripts();
    result = result.replace(/\0+/g, '');
    return result.unescapeHTML();
};

// ------------------------- HACK DIALOGUE BOX ---------------------------------- //
var nativeAlert = window.alert;
window.alert = function(msg) { nativeAlert(fixDialog(msg)); };
var nativePrompt = window.prompt;
window.prompt = function(msg, defaultValue) { return nativePrompt(fixDialog(msg), fixDialog(defaultValue)); };
var nativeConfirm = window.confirm;
window.confirm = function(msg) { return nativeConfirm(fixDialog(msg)); };

// ------------------------- POP UP URL ---------------------------------- //
// width, height, left, top, name, scrollbars, menubar, locationbar, resizable, fullscreen
var openWindow = function(url) {  
	var options = Object.extend({url:url}, arguments[1] || {});
	var args = '';
	var wide = client.screenWidth();
	var high = client.screenHeight();
	if (isSet(options.fullscreen)) {  
		args += "width="+wide+",height="+high+",screenx=0,screeny=0,left=0,top=0,scrollbars=0,statusbar=0,menubar=0,location=0,resizable=0,";
	} 
	else {
		options.x = 0;  
		options.y = 0;  
		if (isSet(options.width)) {
			options.width = parseInt(options.width);
			if (wide > options.width) options.x = Math.floor((wide - options.width) / 2);
			else options.width = wide;
			args += "width="+options.width+",";  
	}  
		if (isSet(options.height)) {
		options.height = parseInt(options.height);
			if (high > options.height) options.y = Math.floor((high - options.height) / 2);
			else options.height = high;
			args += "height="+options.height+",";   
		}
		args += "screenx=" + options.x + ",";  
		args += "screeny=" + options.y + ",";  
		args += "left=" + options.x + ",";  
		args += "top=" + options.y + ",";  
		
		args += 'scrollbars='+( !isSet(options.scrollbars) ? '0' : '1' )+',';
		args += 'statusbar='+( !isSet(options.statusbar) ? '0' : '1' )+',';
		args += 'menubar='+( !isSet(options.menubar) ? '0' : '1' )+',';
		args += 'locationbar='+( !isSet(options.locationbar) ? '0' : '1' )+',';
		args += 'resizable='+( !isSet(options.resizable) ? '0' : '1' )+',';
	}  
	
	if (!isSet(options.name)) options.name = '_blank';
	var win = window.open(options.url, options.name, args);
	win.focus();
	return false;
};

// Alias... <a href="pop_media.php" onClick="return myPop('pop_media.php','popTitle','260','120');">
var myPop = function(url, name, wide, high) {
	return openWindow(url, {name:name, width:wide, height:high, scrollbars:1, statusbar:1, resizable:1});
};

// ------------------------- POP UP IMAGE ---------------------------------- // TO CORRECT !!!
var popImg = function(imgPath) {
    var PositionX = 0;
    var PositionY = 0;
    var defaultWidth = 60;
    var defaultHeight = 60;
    var imgWin = window.open('javascript:void(0)','','resizabe=no,scrollbars=no,width='+defaultWidth+',height='+defaultHeight+',left='+PositionX+',top='+PositionY);
	if (!imgWin) return true; // Open HREF
    with (imgWin.document) {
         writeln('<html><head><title>Chargement...</title><style type="text/css"> body { margin:0; overflow:hidden; } </style><script> var myTime = null; var NS = (navigator.appName == "Netscape") ? true: false; function doTitle() { document.title = "'+affCleanName(imgPath)+'"; } function waiting() { document.getElementById(\'wait\').innerHTML += \'.\'; } function fitPic() { window.resizeTo(document.images[0].width+8,document.images[0].height+30); var wide = screen.availWidth; var high = screen.availHeight; var difW = (NS) ? window.innerWidth: document.body.clientWidth; var difH = (NS) ? window.innerHeight: document.body.clientHeight; var left = 0; var top = 0;  if (wide > difW) left = (wide - difW) / 2; else difW = wide; if (high > difH) top = (high - difH) / 2; else difH = high; window.moveTo(left, top); document.getElementById(\'wait\').style.display = \'none\'; document.getElementById(\'monImage\').style.display = \'block\'; if (myTime) { clearInterval(myTime); myTime = null; } self.focus(); doTitle(); } </script></head><body bgcolor="#000000" scroll="no" onLoad="fitPic();" onblur="self.close();"><img src="'+imgPath+'" name="monImage"  id="monImage" style="cursor:pointer;" title="Fermer la fenêtre" onDblClick="self.close();"><div id="wait" style="color:#FFFFFF; padding-left:10px; padding-top:30px;" width="'+defaultWidth+'" height="'+defaultHeight+'"></div><script>myTime = setInterval("waiting();", 500); </script></body></html>');
        close();
    }
    return false; // Don't open href
};

// ------------------------- Creer une DIV pour afficher des infos DYN ------------------------------ //

var overlayAlpha = 0.6;
var effectDuration = 0.5;
var _infoW = 600; // Largeur par defaut printInfo
var _infoH = 420;
var _boxW = _infoW;
var _boxH = _infoH;
var _myTimer = null;

var getBoxTpl = function(withMessage) {
	if (typeof(withMessage) == 'undefined') withMessage = false;	
	var html = '<div class="hg"><div class="hd"><div class="h">&nbsp;</div></div></div>';
	html += '<div class="g"><div class="d" id="bodyInfo">';
	if (withMessage) html += '<div class="erreur"><p id="messageInfo">&nbsp;</p></div>';
	html += '</div></div>';
	html += '<div class="bg"><div class="bd"><div class="b" id="toolNav"><a href="javascript:void(0);" onClick="removeFadeOverlay();" id="BoxBtClose">Fermer</a></div></div></div>';
	return html;
};

var delTimer = function() {
	if (_myTimer) {
        clearTimeout(_myTimer);
        _myTimer = null;
    }
};

var removeOverlay = function() {
	delTimer();
	Event.stopObserving(window, 'resize');
	Event.stopObserving(window, 'scroll');
	if (isId('dyn_lightbox')) $('dyn_lightbox').hide();
    if (isId('dyn_overlay')) $('dyn_overlay').hide();
    $('divNode').hide();
    showHideBoxes('visible');
};

var removeFadeOverlay = function() {
	delTimer();
    new Effect.Fade('dyn_lightbox', {duration:effectDuration/2, from:1, to:0.0, queue:'front'});
    new Effect.Fade('dyn_overlay', {duration:effectDuration, from:overlayAlpha, to:0.0, queue:'end', afterFinish:removeOverlay});
};

var maximiseOverlay = function() {
	if (!isId('dyn_overlay')) return;
	var myClient = client.getPage();
	Element.setTop('dyn_overlay', 0);
	Element.setLeft('dyn_overlay', 0);
	Element.setWidth('dyn_overlay', myClient.pageW);
	Element.setHeight('dyn_overlay', myClient.pageH);
};

var startOverlay = function() {
	if (!isId('divNode')) return alert('Il manque l\'élément "divNode"');
	removeOverlay();
	showHideBoxes('hidden');
	$('divNode').show();
	if (!isId('dyn_overlay')) {
		var objOverlay = document.createElement('div');
		objOverlay.setAttribute('id','dyn_overlay');
		objOverlay.setAttribute('style','display:none;');
		$('divNode').appendChild(objOverlay); // $$('body')[0]; // IE suck
	}
	if (!isId('dyn_overlay')) die('Error create div "dyn_overlay"');
	maximiseOverlay();
	new Effect.Appear('dyn_overlay', {duration:effectDuration, from:0.0, to:overlayAlpha, queue:'front'});
};

var centerDivBox = function(element, boxSetW, boxSetH) {
	if (isWhat(element) != 'string') { // For auto-resize CALL event
		if (!isId('dyn_lightbox')) return;
		element = 'dyn_lightbox';
		maximiseOverlay();
	}
    if (!isSet(boxSetW) || parseInt(boxSetW) < 1) boxSetW = _boxW;
	else _boxW = boxSetW; // Stock in global for resize event
    if (!isSet(boxSetH) || parseInt(boxSetH) < 1) boxSetH = _boxH;
	else _boxH = boxSetH;
	
	var myClient = client.getPage();
	var offsetX = myClient.scrollX + ((myClient.viewW - parseInt(boxSetW)) / 2);
	var offsetY = myClient.scrollY + ((myClient.viewH - parseInt(boxSetH))  / 2);

	Element.setTop(element, offsetY);
    Element.setLeft(element, offsetX);
};

var startLightBox = function(boxSetW, boxSetH) {
   if (!isId('dyn_lightbox')) {
		var objLightbox = document.createElement("div");
        objLightbox.setAttribute('id','dyn_lightbox');
		objLightbox.style.width = boxSetW + (boxSetW.toString().indexOf('%') == -1 ? 'px' : '');
       // objLightbox.style.height = boxSetW + (boxSetH.toString().indexOf('%') == -1 ? 'px' : ''); FLOATING HEIGHT ???
        $('divNode').appendChild(objLightbox);
    }
	else $('dyn_lightbox').update('');
	$('dyn_lightbox').hide();
	centerDivBox('dyn_lightbox', boxSetW, boxSetH);
	Event.observe(window, 'resize', centerDivBox);
	Event.observe(window, 'scroll', centerDivBox);
};


// -------- onclick="printInfo('Bla bla<br>blabla', {boxWidth;'380',options.height:'260',afterFinish:redir});" ------------ //
var printInfo = function(infosHtml) {
    if (!isSet(infosHtml)) return;
	var options = Object.extend({infosHtml:infosHtml}, arguments[1] || {});

	options.infosHtml = options.infosHtml.stripScripts();
	options.infosHtml = stripslashes(options.infosHtml);
	if (options.infosHtml.match(/<br>/) || options.infosHtml.match(/<br \/>/)) options.infosHtml += '<br />&nbsp;';

	options.infosHtml = '<div id="divFormMessage"><p>'+options.infosHtml+'</p></div>';

	myLightWindow.activateWindow({
		href: options.infosHtml,
		title: 'Information',
		type: 'inline'
	});
	
	if (!isSet(options.timeOut) || parseInt(options.timeOut) < 1) options.timeOut = (parseInt(options.infosHtml.length) * 82);
   if (options.timeOut < 1600) options.timeOut = 2000;
	options.timeOut += 2000; // Window loading
	
	actionTimeOut = 'myLightWindow.deactivate();';
	if (options.afterFinish) actionTimeOut += ' exec('+options.afterFinish+');';
	_myTimer = setTimeout(actionTimeOut, options.timeOut);

	// printInfo('dsfh kfdhgfdgkfd');
	
	/*startOverlay();
	$('dyn_overlay').onclick = function() { 
		removeFadeOverlay();
		if (options.afterFinish) exec(options.afterFinish);
		return false;
	}
	
	if (!isSet(options.width) || parseInt(options.width) < 1) options.width = 445; // class divFormMessage 20px moins
	else options.width.toString();
	if (!isSet(options.height) || parseInt(options.height) < 1) options.height = 200;
	else options.height.toString();
	
	startLightBox(options.width, options.options.height);
	$('dyn_lightbox').update(getBoxTpl(true));
	
	if (options.afterFinish && isId('BoxBtClose')) $('BoxBtClose').setAttribute('onClick',"removeFadeOverlay(); exec("+options.afterFinish+");");
	
	options.infosHtml = options.infosHtml.stripScripts();
	options.infosHtml = stripslashes(options.infosHtml);
	if (options.infosHtml.match(/<br>/) || options.infosHtml.match(/<br \/>/)) options.infosHtml += '<br />&nbsp;';
	
	$('messageInfo').update(options.infosHtml);
	new Effect.Appear('dyn_lightbox', {duration:effectDuration*2});
	
	var timeOut = (parseInt(options.infosHtml.length) * 82);
   if (timeOut < 1600) timeOut = 2000;
	if (options.afterFinish) _myTimer = setTimeout("removeFadeOverlay(); exec("+options.afterFinish+");", timeOut);
	else _myTimer = setTimeout("removeFadeOverlay();", timeOut);*/
};

// -------- onclick="printPage('_popup_login.php', {title:'hello', boxWidth:'380', options.height:'260', beforeStart:kill, afterFinish:redir, simple:false});" ------------ //
var printPage = function(url) {
    if (!isSet(url)) return;
	var options = Object.extend({url:url}, arguments[1] || {});
	
	if (!options.simple) options.simple = false;
	
	startOverlay();
	$('dyn_overlay').onclick = function() { 
		removeFadeOverlay();
		if (options.afterFinish) exec(options.afterFinish);
		return false;
	}
	if (!isSet(options.width) || parseInt(options.width) < 1) options.width = _boxW;
	if (!isSet(options.options.height) || parseInt(options.options.height) < 1) options.options.height = _boxH;
	
	startLightBox(options.width, options.options.height);
	if (!options.simple) $('dyn_lightbox').update(getBoxTpl(false));

    if (isSet(options.title)) new Insertion.Top('toolNav', '<span id="toolTitle">'+options.title+'</span>');
	var specs = options.url.split('?');
    var contentUrl = specs[0]
    var parameters = specs[1];
    var laRequete = new Ajax.Request(contentUrl, {
		method: 'get',
		evalScripts: true,
		parameters: parameters,
		onComplete: function(transport) { 
			if (!options.simple) $('bodyInfo').update(transport.responseText);
			else $('dyn_lightbox').update(transport.responseText);
			if (isSet(options.beforeStart)) exec(options.beforeStart);
			new Effect.Appear('dyn_lightbox', {duration:effectDuration*2});
			if (isSet(options.afterFinish)) {
				$('BoxBtClose').onclick = function() { 
					removeFadeOverlay();
					exec(options.afterFinish);
					return false;
				}
			}
		}
	});
    return false; // Don't open href
};