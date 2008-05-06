/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

					Ces fonctions sont à copier-coller et à mettre dans specifique.js, SI besoin....

Functions :

	resizeMyFrame(id)
	setFooter(evt)
	linkShowHide(linkId,myElement,imgId,imgStart,imgEnd)
	lshwOnMouseOver(myElement,imgId,imgStart_over,imgEnd_over)
	lshwOnMouseOut(myElement,imgId,imgStart,imgEnd)
	showHideBoxes(visibility) // visibility = hidden|visible
	ajaxCheck(input,valeur,divInfo)
	tabAjaxUrl(currentLink,arrayLink,contentId,contentUrl)
	parseXml()
	getXml(xmlPath)
	promptVideo(myInput)
	moovIt(evt)
	addEmailDest(contentId)
	checkCommentaire()
	toolHelp()

/////////////////////////////////////////////////////////////////////////////////////////////////////// */

if (typeof db == 'undefined') throw("client.js requires tools.js");


// ------------------------- SCROLL ---------------------------------- //
var tempo = 5;
var inc = 0;
var totP = 0;
//var arrAccroche = from index;php
var scrollNews = function(evt) {
	if (evt) Event.stop(evt);
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
		// Build Next
		var innerHTL = '<a href="'+arrAccroche[inc]['goto']+'"><strong>'+arrAccroche[inc]['titre']+'</strong></a>';
		innerHTL += '<li>'+arrAccroche[inc]['texte'].replace(reg, '</li><li>')+'</li>';
		new Insertion.Bottom('info_scroll', getTpl('div', {id: 'div_scroll_'+inc, style:'display:none', inner: innerHTL}));
		Effect.Appear('div_scroll_'+inc);
		// Scroll Previous
		if (inc == 0) backDivId = totP - 1;
		else backDivId = inc - 1;
		if (isId('div_scroll_'+backDivId)) {
			var eHeight = Element.getHeight('div_scroll_'+backDivId);
			Effect.MoveBy('div_scroll_'+backDivId, -parseInt(eHeight), 0, {duration:0.5, afterFinish:function(e){$(e).element.remove();}});
		}
		if (inc >= (totP-1)) inc = 0;
		else inc++;
	}, tempo);
};
Event.observe(window, 'load', scrollNews);

// ------------------------- LAYOUT ---------------------------------- //
// Auto Size Some Div // To correct in future version // CSS TIPS !
var setPageSize = function(){
	if (!isId('page_left')) return;
	var div_height = Element.getHeight('page_interieur');
	$('page_left').style.height = div_height+'px';
	$('page_right').style.height = div_height+'px';
};
Event.observe(window, 'load', setPageSize, false);

// ------------------------- IFRAME AUTO RESIZE ------------------------------ //

var resizeMyFrame = function(id) {
	if (!parent.document.getElementById(id)) return;
	var noeFrame = parent.document.getElementById(id);
	var yScroll = 350; // taille par defaut si echec fonction
	try {
		if (noeFrame.contentDocument && noeFrame.contentDocument.body.scrollHeight) yScroll = noeFrame.contentDocument.body.scrollHeight + 30;
		else if (noeFrame.document.body.scrollHeight) yScroll = noeFrame.Document.body.scrollHeight + 30;
		else if (noeFrame.offsetHeight) yScroll = noeFrame.offsetHeight + 30;
	}
	catch(e) {}
	noeFrame.height = yScroll+'px';
};

// ------------------------- TO TEST LATER... ------------------------------ //
var setFooter = function(evt) {
	if (evt) Event.stop(evt);
    var windowHeight = client.windowHeight();
    if (windowHeight > 0) {
        var contentHeight = $('content').offsetHeight;
        var footerElement = $('footer');
        var footerHeight  = footerElement.offsetHeight;
        if (windowHeight - (contentHeight + footerHeight) >= 0) {
            footerElement.style.position = 'relative';
            footerElement.style.top = (windowHeight - (contentHeight + footerHeight)) + 'px';
        }
        else {
            footerElement.style.position = 'static';
        }
    }
};
//Event.observe(window,'load',setFooter);
//Event.observe(window,'resize',footerDown);

/*
	var footerDown = function() {
		bodyHeight = $('bodyall').offsetHeight;
		winHeight  = window.innerHeight ? window.innerHeight : document.body.clientHeight;
		if (bodyHeight < winHeight)     {
			$('content').style.height = (winHeight - (bodyHeight - $('content').offsetHeight)) + 'px';
		}
	}
*/

// ------------------------- linkShowHide ------------------------------ //
/* <a id="linkOption" href="javascript:void(0);" onclick="linkShowHide('linkOption','otpions','showOpt1','images/nav/flech_down.png','images/nav/flech_up.png');"  onmouseover="lshwOnMouseOver('otpions','showOpt1','images/nav/flech_down_over.png','images/nav/flech_up_over.png');"
onmouseout="lshwOnMouseOut('otpions','showOpt1','images/nav/flech_down.png','images/nav/flech_up.png');" title="Afficher/Masquer les options"><img src="../scripts/images/nav/flech_down.png" id="showOpt1" border="0"></a><div id="otpions" style="display:none;">TEST</div> */
var pos = new Array();
var linkShowHide = function(linkId,myElement,imgId,imgStart,imgEnd) {
    if (!pos[myElement]) pos[myElement] = 'open'; // Store init pos..
    ElinkId = $(linkId);
    EmyElement = $(myElement);
    EimgId = $(imgId);
    if (pos[myElement] == 'open') {
        EmyElement.style.display = 'block';
        if (document.images[imgId]) document.images[imgId].src = imgEnd;
        else EimgId.src = imgEnd;
        pos[myElement] = 'close';
    }
    else {
        EmyElement.style.display = 'none';
        if (document.images[imgId]) document.images[imgId].src = imgStart;
        else EimgId.src = imgStart;
        pos[myElement] = 'open';
    }
};

var lshwOnMouseOver = function(myElement,imgId,imgStart_over,imgEnd_over) {
    if (!pos[myElement]) pos[myElement] = 'open'; // Store init pos..
    if (pos[myElement] == 'open') setImg(imgId,imgStart_over);
    else setImg(imgId,imgEnd_over);
};

var lshwOnMouseOut = function(myElement,imgId,imgStart,imgEnd) {
    if (pos[myElement] == 'open') setImg(imgId,imgStart);
    else setImg(imgId,imgEnd);
};

// -------------------- LOAD URL IN ELEMENT -------------------- //
var tabAjaxUrl = function(currentLink, arrayLink, contentId, contentUrl) { // onClick="return tabAjaxUrl('link3',['link1','link2','link3'],'contentText','inc/_pieces_ami.php?id=10');"
	var stockLoadId = getUniqueId();
	var imgS = loadImg(loadingImagePath);
	var imgHtml = getImageHtm(loadingImagePath, stockLoadId);
	for (var i=0; i < arrayLink.length; i++) {
		if (currentLink == arrayLink[i]) { // Toggle Class
			$(arrayLink[i]).removeClassName('rouge');
			$(arrayLink[i]).addClassName('griss');
			new Insertion.Bottom(arrayLink[i], ' '+imgHtml);
		}
		else {
			$(arrayLink[i]).removeClassName('griss');
			$(arrayLink[i]).addClassName('rouge');
		}
	}
	var specs = contentUrl.split('?');
    var contentUrl = specs[0]
    var parameters = specs[1];
	var laRequete = new Ajax.Request(contentUrl, {
		method: 'get',
		evalScripts: true,
        parameters: parameters,
		onSuccess: function(transport) {
			$(stockLoadId).remove();
			$(contentId).update(transport.responseText);
		}
	});
	return false; // Dont open href
};

// ------------------------- Parse XML ------------------------------ //
var xmlDoc = null;
var xmlArray = {};
var parseXml = function() {
    if (xmlDoc) {
        var nodes = $A(xmlDoc.getElementsByTagName('galerie'));
        nodes.each( function(e) {
            xmlArray.push({
                'id':        e.getElementsByTagName('id')[0].childNodes[0].nodeValue,
                'titre':     e.getElementsByTagName('titre')[0].childNodes[0].nodeValue,
                'legende':   e.getElementsByTagName('legende')[0].childNodes[0].nodeValue.stripTags(),
                'miniature': e.getElementsByTagName('miniature')[0].childNodes[0].nodeValue
            });   
        });
    }
    else printInfo('Probl&egrave;me de lecture du fichier XML.');
    return xmlArray;
};

// ------------------------- Get XML ------------------------------ //
var getXml = function(xmlPath) {
    if (document.implementation && document.implementation.createDocument) {
        xmlDoc = document.implementation.createDocument("", "", null);   
        xmlDoc.onload = parseXml;
    }
    else if (window.ActiveXObject) {
        xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
        xmlDoc.onreadystatechange = function() {
            if (xmlDoc.readyState == 4) parseXml();
        };
    }
    else {
        printInfo('Votre navigateur ne supporte pas le XML.');
        return;
    }
    xmlDoc.load(xmlPath);
};
// -------------------- PROMPT VIDEO CODE (Thanks to Anarchy media for source code) -------------------- //
var promptVideo = function(myInput) {

	var videoURL = prompt('Collez l\'url compl&agrave;te de votre vid&eacute;o (Exception pour Dailymotion et Wat, il faut mettre le code embed)\nSites reconnus : DAILYMOTION, GOOGLE, YOUTUBE, METACAFE, iFILM, WAT, MYSPACE :', '');
	if (!videoURL) {
		alert('D&eacute;sol&eacute; nous n\'avons pas r&eacute;ussi &agrave; identifier le code ni &agrave; en extraire l\'ID');
		//$(myInput).value = '';
		$(myInput).blur();
		return;
	}
	
	// SITES CONNUS // OK SWF // Regex pattern code  from "anarchy media player" : http://an-archos.com/anarchy-media-player
	var youtubevid = new RegExp("youtube\.com\/watch") // http://fr.youtube.com/watch?v=t8LjgMDDkww
	var googlevid = new RegExp("video\.google\.(.*)?\/videoplay") // http://video.google.fr/videoplay?docid=-3174756981167162325&q=molokoloco&pr=goog-sl
	var metacafevid = new RegExp("metacafe\.com\/watch\/") //http://www.metacafe.com/watch/756433/laser_flashlight_hack/
	var dailymotionvid = new RegExp("dailymotion\.com\/swf") // <div><object width="425" height="335"><param name="movie" value="http://www.dailymotion.com/swf/2AgQKpcyrFgPthnAl"></param><param name="allowfullscreen" value="true"></param><embed src="http://www.dailymotion.com/swf/2AgQKpcyrFgPthnAl" type="application/x-shockwave-flash" width="425" height="335" allowfullscreen="true"></embed></object><br /><b><a href="http://www.dailymotion.com/video/x2gs69_bboying_extreme">Bboying</a></b><br /><i>envoy&eacute; par <a href="http://www.dailymotion.com/KeMp-S2O">KeMp-S2O</a></i></div>
	var watvid = new RegExp("wat\.tv\/swf") // <div><object width='456' height='390'><param name='movie' value='http://www.wat.tv/swf/227165okYC41n609109'></param><param name='autoplay' value='false'></param><param name='allowScriptAccess' value='always' /><embed src='http://www.wat.tv/swf/227165okYC41n609109' type='application/x-shockwave-flash' width='456' height='390' allowScriptAccess='always'></embed></object><br /><b><a href='http://www.wat.tv/video/609109/victoria-Victoria--Avant-monter-sur-scene-episode-4.html'>Victoria - Avant de monter sur scène : épisode 4</a></b><br /><a href='http://www.wat.tv/videos' title='Toutes les videos'>Video</a><br /><i>Envoyé par <a href='http://www.wat.tv/victoria'>victoria</a> sur <a href='http://www.wat.tv'>wat.tv</a></i></div>
	
	// PAS OK
	var ifilmvid = new RegExp("ifilm\.com\/video\/") // http://www.ifilm.com/video/2667390
	var myspacevid = new RegExp("vids\.myspace\.com\/index\.cfm") // http://myspacetv.com/index.cfm?fuseaction=vids.individual&videoid=14887635
	
	// Let's find the path to direct swf player of each site
	var videoSITE = '';
	var videoID = '';
	
	if (googlevid.test(videoURL)) {
		videoSITE = 'google';
		videoID1 = videoURL.replace(/\.(.*)\/videoplay/, '.google.com/googleplayer.swf')
		videoID = videoID1.replace(/&(.*)?/, '')		
	}
	else if (youtubevid.test(videoURL)) { // /youtube\.com\/v\/([^\"]+)\">/  ---  /watch\?v=(.*)$/
		videoSITE = 'youtube';
		videoID1 = videoURL.replace(/watch\?v\=/, 'v/')
		videoID1 = videoID1.replace(/fr./, 'www.')
		videoID = videoID1.replace(/&(.*)?/, '')		
	}
	else if (dailymotionvid.test(videoURL)) {
		videoSITE = 'dailymotion';
		videoID = videoURL.replace(/^(.*)src=\"(.*)\" type=\"(.*)$/i, "$2")
	}
	else if (ifilmvid.test(videoURL)) { // !!!
		videoID1 = videoURL.replace(/http:\/\/www\.ifilm\.com\/video\/([a-z0-9])/i, "$1") 
		videoID = videoID1.replace(/\?(.*)?/, '')
		// movie="http://www.ifilm.com/efp" fvars="flvbaseclip=' + videoID + '"
	}
	else if (metacafevid.test(videoURL)) {
		videoSITE = 'metacafe';
		videoID1 = videoURL.replace(/watch/i, "fplayer") 
		videoID = videoID1.replace(/\/$/, ".swf")
	}
	else if (myspacevid.test(videoURL)) { //  !!!
		videoSITE = 'myspace';
		videoID = videoURL.replace(/http:\/\/vids\.myspace\.com\/index\.cfm\?fuseaction=vids\.individual&videoid=([a-z0-9])/i, "$1") 
		// movie="http://lads.myspace.com/videos/vplayer.swf" fvars="m=' + videoID + ';type=video
	}
	else if (watvid.test(videoURL)) {
		videoSITE = 'wat';
		videoID = videoURL.replace(/^(.*)src=\'(.*)\' type=\'(.*)$/i, "$2")
	}

	if (videoID != '') $(myInput).value = videoID;
	else {
		alert('D&eacute;sol&eacute; nous n\'avons pas r&eacute;ussi &agrave; identifier le code ni &agrave; en extraire l\'ID');
		//$(myInput).value = '';
		promptVideo(myInput); // Re ?
	}
};

// -------------------- MOVE A DIV RIGHT TO LEFT AND BLIND ELEMENT -------------------- //
var moovIt = function(evt) {
	if (evt) Event.stop(evt);
	if(!isId('a_reduire')) return;
	window.isMoving = false;
	Event.observe('a_reduire', 'mouseover', function(evt) { 
		if (!window.isMoving) {
			window.isMoving = true;
			Element.setLeft('a_reduire', 0);
			new Effect.MoveBy('a_reduire', 0, -103, { duration:0.3, afterFinish: function() { window.isMoving = false; } });
		}
	});
	Event.observe('a_reduire', 'mouseout', function(evt) {
		if (!window.isMoving) {
			window.isMoving = true;
			Element.setLeft('a_reduire', -103);
			new Effect.MoveBy('a_reduire', 0, 103, { duration:0.3, afterFinish: function() { window.isMoving = false; } });
		}
	});
	Event.observe('a_reduire', 'click', function(evt) { 
		visuelBandeau('off');
	});
};

/* ------------------------- INSERT NEW ROW ---------------------------------- */
var totalMail = 1;
var addEmailDest = function (contentId) {
	if (!isSet(contentId)) return false;
	totalMail++;
	if (totalMail > 5) {
		if ($(contentId+'_info')) {
			$(contentId+'_info').update('<br />D&eacute;sol&eacute;, vous ne pouvez pas ajouter plus de 5 destinataires<br />&nbsp;');
			$(contentId+'_info').show();
		}
		else printInfo('D&eacute;sol&eacute;, vous ne pouvez pas ajouter plus de 5 destinataires');
	}
	else {
		$(contentId).show();
		var inputId = 'email_ami'+totalMail;
		if (isId(contentId+'_info')) $(contentId+'_info').hide();
		new Insertion.Bottom($(contentId), '<div class="divRow"><label for="'+inputId+'">Email destinataire '+totalMail+' :</label><input name="'+inputId+'" id="'+inputId+'" type="text" /></div>');
		$(inputId).focus();
	}
};

/* ------------------------- AFTER FINISH : ACTION IF IS CONNECT ---------------------------------- */
var checkCommentaire = function () {
	new Ajax.Request('_clientIsConnect.php', {
		method: 'get',
		onSuccess: function(requete) {
			if (requete.responseText == '1') envoyer_commentaire();
			else printPage('_popup_login.php?from=commenter', {afterFinish:checkCommentaire});
		}
	});
};

/* ------------------------- MOUSEOVER > HIDE/SHOW DIV ---------------------------------- */
// rss_js > bouton lien
// aide_rss > div a masquer/afficher

var maxDist = 130;
var mX = ''; var mY = '';
var hideIfFar = function(evt) {
	mXdoc = Event.pointerX(evt);
	mYdoc = Event.pointerY(evt);
	if (mXdoc - mX > maxDist || mX - mXdoc > maxDist || mYdoc - mY > maxDist || mY - mYdoc > maxDist) {
		Event.stop(evt);
		Effect.Fade('aide_rss', {duration:0.5});
	}
};
var toolHelp = function(evt) {
	if (evt) Event.stop(evt);
	if (!isId('rss_js')) return;
	Event.observe('rss_js', 'mouseover', function(evt) { 
		Effect.Appear('aide_rss', {duration:0.5});
		mX = Event.pointerX(evt);
		mY = Event.pointerY(evt);
		Event.observe(document, 'mouseover', hideIfFar, false);
	});
};
Event.observe(window, 'load', toolHelp);


/* ------------------------- DEFILEMENT DE DIV ---------------------------------- */
/*
<div class="colonne3">
	<div class="h_actu">
		<h1><img width="86" height="27" alt="Actualités" src="images/fr/t_actu.gif"/></h1>
		<a onclick="defil('+');" class="bt_bas" href="#"><img width="16" height="15" border="0" alt="" src="images/commun/bt_bas.gif"/></a>
		<a onclick="defil('-');" class="bt_haut" href="#"><img width="16" height="15" border="0" alt="" src="images/commun/bt_haut.gif"/></a>
	</div>
	<div id="b_actu">
		<div style="top: 10px;" id="contenu_actu">
			<div id="actu3" class="actu" style="position: relative; left: 0px; top: 0px;">
					<h2><a href="#">actu 3</a></h2>
					<p>Au village des artisans journée thématique sur la mquette</p>
			</div>
			<div id="actu1" class="actu" style="position: relative; left: 0px; top: 0px;">
					<h2><a href="#">Actu 1</a></h2>
					<p>Nouveauté cette année, ce village sera animé selon 3 thèmes. Nouveauté cette année, ce village sera animé selon 3 thèmes. Nouveauté cette année, ce village sera animé selon 3 thèmes.</p>
			</div>
			<div id="actu1" class="actu" style="position: relative; left: 0px; top: 0px;">
					<h2><a href="#">Actu 1</a></h2>
					<p>Nouveauté cette année, ce village sera animé selon 3 thèmes. Nouveauté cette année, ce village sera animé selon 3 thèmes. Nouveauté cette année, ce village sera animé selon 3 thèmes.</p>
			</div>
			<div id="actu2" class="actu">
					<h2><a href="#">actu 2</a></h2>
					<p>Au village des artisans journée thématique sur la mquette Nouveauté cette année, ce village sera animé selon 3 thèmes.</p>
			</div>
		</div>
	</div>
</div>
*/
var work = true;
var defil = function(sens){
	// PARAMETRAGE //
	var conteneur ='#contenu_actu div';
	var cible ='contenu_actu';
	var vitesse = 0.7;
	
	var list_actu = $$(conteneur);
	var nb_actu = list_actu.length;
	var pas = 0;
	var actu_val = '';
	var dernier = '';
	
	if(work == true){
		if (sens=='+') {
			work = false; // Désactive le clic qd le script est en train de tourner
			pas = '-'+list_actu[0].offsetHeight;
			actu_val = '<div class="actu" id="'+list_actu[0].id+'">'+list_actu[0].innerHTML+'</div>';
			//dernier = list_actu[nb_actu-1];
			
			// DECALAGE DU PREMIER
			Effect.MoveBy(list_actu[0], pas, 0,{duration:vitesse,
				afterFinish:function(){
					var list_actu = $$(conteneur);
					new Insertion.Bottom(cible, actu_val);
					list_actu[0].remove();
				}
			});
			
			// DECALAGE DES AUTRES
			for(i=1;i<nb_actu;i++){
				new Effect.MoveBy(list_actu[i], pas, 0,{duration:vitesse,
					afterFinish:function(effect){
						$(effect.element).style.position = 'relative';
						$(effect.element).style.top = '0px';
						work = true; // Rétabli le clic qd le script a fini de tourner
					}
				});
			}
		
		}else{
			work = false; // Désactive le clic qd le script est en train de tourner
			pas = list_actu[nb_actu-1].offsetHeight;
			actu_val = '<div class="actu" id="'+list_actu[nb_actu-1].id+'">'+list_actu[nb_actu-1].innerHTML+'</div>';
			new Insertion.Top(cible, actu_val);
			$(list_actu[nb_actu-1].id).style.position = 'absolute';
			$(list_actu[nb_actu-1].id).style.top = '-'+pas+'px';
			list_actu[nb_actu-1].remove();
			
			var list_actu = $$(conteneur);
			// DECALAGE DU PREMIER
			Effect.MoveBy(list_actu[0], pas, 0,{duration:vitesse});
			
			// DECALAGE DES AUTRES
			for(i=0;i<nb_actu;i++){
				new Effect.MoveBy(list_actu[i], pas, 0,{duration:vitesse,
					afterFinish:function(effect){
						work = true; // Rétabli le clic qd le script a fini de tourner
					}				
				});
			}
		}
	}
};


// ------------------------- Classe Lister ---------------------------------- //
Event.observe(window, 'load', lst_init, false);

var lst_struct_template = '<div class="element" id="element#{cpt}" style="display:none;">';
lst_struct_template += '<p><input type="text" name="produit[]" id="produit#{cpt}" class="produit" value="Ligne #{cpt}" /></p>';
lst_struct_template += '<p><input type="text" name="categorie[]" id="categorie#{cpt}" class="categorie" /></p>';
lst_struct_template += '<p><input type="text" name="quantite[]" id="quantite#{cpt}" class="quantite" /></p>';
lst_struct_template += '<p><a href="javascript:void(0);" class="bt_remove" id="bt_remove#{cpt}">Supprimer un produit</a></p>';
lst_struct_template += '</div>';

function lst_init(){
	var lst_ligne = new Lister('contenu', 'bt_add', 'bt_remove', 'element', lst_struct_template, 'bottom', true, {}, 5);
	}
};

/********************************************************
*	Counts the characters currently entered into a form field
*	@author      Ava Hristova
********************************************************/
var charCount = 1;
var maxCharCount = 2000;
var displayRemLength = function (fieldName) {
	remField = document.getElementById(fieldName);
	remField.innerHTML = (maxCharCount - charCount > 0) ? maxCharCount - charCount : 0;
};

var evalEntryLength = function(curField, maxLimit, discardXtra, errClass, normalClass) {
	maxCharCount = maxLimit;
	var fieldLength = getCharCount(curField);
	if (fieldLength > maxLimit) {
		if (errClass != '') curField.className = errClass;
		if (discardXtra) showAllowedLength(curField, maxLimit);
	}
	else if (normalClass != '') curField.className = normalClass;
};

var getCharCount = function(curField) {
	charCount = curField.value.length;
	return charCount;
};

var showAllowedLength = function(curField, maxLimit) {
	curField.value = curField.value.substr(0, maxLimit);
};


var redirToParticipateIfConnect = function () {
	new Ajax.Request('_clientIsConnect.php', {
		method: 'get',
		onSuccess: function(requete) {
			if (requete.responseText == '1') redir('index.php?goto=participer');
		}
	});
};

var fetchTags = function(theme) {
	if (theme < 1) return;
	return new Ajax.Request('participer/_fetchTags.php', {
		method: 'get',
		parameters: 'cat_id='+theme,
		onSuccess: function(requete) {
			$('printTags').update(requete.responseText);
		}
	});
};

var checkCommentaire = function () {
	new Ajax.Request('_clientIsConnect.php', {
		method: 'get',
		onSuccess: function(requete) {
			if (requete.responseText == '1') envoyer_commentaire();
			else printPage('_popup_login.php?from=commenter', {afterFinish:checkCommentaire});
		}
	});
};

var hideIdeeDiv = function(hideElmts) {
	for (var e in hideElmts) {
		if (isId((hideElmts[e])) && $(hideElmts[e]).style.display != 'none') Effect.BlindUp(hideElmts[e] ,{duration:0.1});
	}
}			
				