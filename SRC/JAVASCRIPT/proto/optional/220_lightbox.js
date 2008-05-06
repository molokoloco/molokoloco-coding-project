// -----------------------------------------------------------------------------------
//	Lightbox v2.02
//	by Lokesh Dhakar - http://www.huddletogether.com
//	3/31/06
//	For more information on this script, visit:
//	http://huddletogether.com/projects/dyn_lightbox2/
//	Licensed under the Creative Commons Attribution 2.5 License - http://creativecommons.org/licenses/by/2.5/
//	
//	Credit also due to those who have helped, inspired, and made their code available to the public.
//	Including: Scott Upton(uptonic.com), Peter-Paul Koch(quirksmode.org), Thomas Fuchs(mir.aculo.us), and others.
//	Et aussi le pitititi molokoloco ! L'créatif du code ! Yeah baby <:p


/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

//  rel="lightwindow"
//  rel="lightwindow[titreGal]"
//  rel="popurl[800|400]"
//  rel="popurl[Multi]"
//  onClick="return myLightbox.getImage('membres/eole/figue.jpg','Test');"
//	onClick="return myLightbox.getUrl('membres/eole/sommaire.htm','Test',720,300);"
//  onClick="return myLightbox.slidebox('membres/eole/galeries_medias_5.xml',2,'Test',720,300);"

// -----------------------------------------------------------------------------------

if (typeof db == 'undefined') die("220_lightbox.js requires 200_tools.js");
if (typeof Effect == 'undefined') die("220_lightbox.js requires 110_effects.js");
if (typeof startOverlay == 'undefined') db("220_lightbox.js requires 214_modal-dialogue.js");

//	Images navigation
var fileLoadingImage = "js/ScriptAculous/lightboximages/loading.gif";		
var fileBottomNavCloseImage = "js/ScriptAculous/lightboximages/picto_fermer.gif";
var fileBottomNavNextImage = "js/ScriptAculous/lightboximages/next.gif";
var fileBottomNavPrevImage = "js/ScriptAculous/lightboximages/prev.gif";

//	Configuration
var globalTempo = 6000; // 6 Sec
var dyn_overlayAlpha = 0.4;
var resizeDuration = 0.6;
var borderSize = 0;

//	Global Variables
var mediasArray = [];
var urlArray = []; // Ajax
var ajaxObjects = [];
var activeImage;
var activeUrl;

// -----------------------------------------------------------------------------------

var Lightbox = Class.create();

Lightbox.prototype = {

	initialize: function() {	
		if (!document.getElementsByTagName) return;
		var relAttribute = '';
		$$('a').each( function(e) {
			relAttribute = String(e.getAttribute('rel'));
			if (e.getAttribute('href') && ( (relAttribute.match('lightwindow')) || (relAttribute.match('popurl')) ) ) {
				e.onclick = function () { myLightbox.start(this); return false; }
			}
		});
	},
	
	startOverlay: function() {
		startOverlay();
		$('dyn_overlay').onclick = function() { myLightbox.end(); }
	},
	
	startLightBox: function() {
		/* Attemp to build :
			<div id="dyn_lightbox" style="width: 1020px; top: 1465px; left: 174.5px; height: 253px;">
			  <div class="hg">
				<div class="hd">
				  <div class="h"> </div>
				</div>
			  </div>
			  <div class="g">
				<div id="bodyInfo" class="d">
				  <div id="outerImageContainer">
					<div id="imageContainer"><img id="dyn_image" src="images/fr/bandeau_visuel_4.jpg" style="opacity: 0.999999;"/>
					  <div style="display: block;" id="caption">[4/6] <b>bandeau visuel 4</b></div>
					  <div id="dyn_lightboxUrl"/>
					  <div style="display: none;" id="loading"><a id="loadingLink" onClick="myLightbox.end();" href="javascript:void(0)"><img border="0" src="images/common/loading.gif"/></a></div>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="bg">
				<div class="bd">
				  <div id="toolNav" class="b" style="display: block;"><a style="" id="prevLink" href="javascript:void(0)">Précédante</a> <a style="" id="nextLink" href="javascript:void(0)">Suivante</a> <a id="BoxBtClose" onClick="myLightbox.end();" href="javascript:void(0);">Fermer</a></div>
				</div>
			  </div>
			</div>
		*/
		
		startLightBox(123, 123);
		$('dyn_lightbox').update(getBoxTpl(false));
		$('bodyInfo').update('<div id="outerImageContainer"><div id="imageContainer"><img id="dyn_image" src="javascript:void(0)" /><div id="caption" style="display:none"></div><div id="dyn_lightboxUrl"></div><div id="loading" style="display:none;"><a href="javascript:void(0)" onClick="myLightbox.end();" id="loadingLink"><img src="'+fileLoadingImage+'" border="0"></a></div></div></div>');
		
		$('toolNav').update('{ <a href="javascript:void(0)" id="prevLink" style="display:none;" title="Pr&eacute;c&eacute;dante">&lt;&lt;</a> <a href="javascript:void(0)" id="nextLink" style="display:none;" title="Suivante">&gt;&gt;</a> <a href="javascript:void(0);" onClick="myLightbox.end();" id="BoxBtClose" title="Fermer">x</a> }');
		
		$('dyn_lightbox').show();
	},
	
	// Direct Call
	getImage: function(imgLink, imgTitle) {	
		myLightbox.startOverlay();
		slideShow = false;
		mediasArray = [];
		urlArray = [];
		imageNum = 0;
		urlNum = 0;
		mediasArray.push({'mediasrc':imgLink, 'titre': imgTitle});
		myLightbox.startLightBox();
		myLightbox.changeImage(imageNum);
		return false; // Don't open href
	},
	
	// Direct Call
	getUrl: function(urlLink, urlTitle, w, h) {	
		myLightbox.startOverlay();
		slideShow = false;
		mediasArray = [];
		urlArray = [];
		imageNum = 0;
		urlNum = 0;
		urlArray.push({'mediasrc':urlLink, 'titre': urlTitle, 'largeur':w, 'hauteur':h});
		myLightbox.startLightBox();
		myLightbox.changeUrl(urlNum); // this. 
		return false; // Don't open href
	},
	
	// Automatik call
	start: function(imageLink) {
		
		myLightbox.startOverlay();

		slideShow = false;
		mediasArray = [];
		urlArray = [];
		imageNum = 0;
		urlNum = 0;

		var relAttribute = String(imageLink.getAttribute('rel'));
		if (relAttribute.match('lightwindow')) { // image
			if (relAttribute == 'lightwindow') mediasArray.push({'mediasrc':imageLink.getAttribute('href'), 'titre':imageLink.getAttribute('title')}); // add single image to mediasArray
			else { // if image is part of a set.. loop through anchors, find other images in set, and add them to mediasArray
				$$('a').each( function(e) {					
					if (e.getAttribute('rel') == relAttribute) mediasArray.push({'mediasrc':e.getAttribute('href'), 'titre':(e.getAttribute('title')?e.getAttribute('title'):affCleanName(e.getAttribute('href')))});
				});
				while (mediasArray[imageNum]['mediasrc'] != imageLink.getAttribute('href')) imageNum++;
			}
		}
		else if (relAttribute.match('popurl')) { // url
			if (relAttribute == 'popurl') // add single url to urlArray
				urlArray.push({'mediasrc':imageLink.getAttribute('href'), 'titre':imageLink.getAttribute('title')});
			else if (relAttribute.match(/\|/)){
				size = relAttribute.split('|');
				contentWidth = parseInt(size[0].split('\[')[1]);
				contentHeight = parseInt(size[1].split('\]')[0]);
				urlArray.push({'mediasrc':imageLink.getAttribute('href'), 'titre':imageLink.getAttribute('title'), 'largeur':contentWidth, 'hauteur':contentHeight});
			}
			else { // if url is part of a set.. loop through anchors, find other images in set, and add them to mediasArray
				$$('a').each( function(e) {					
					if (e.getAttribute('rel') == relAttribute)
						urlArray.push({'mediasrc':e.getAttribute('href'), 'titre':(e.getAttribute('title')?e.getAttribute('title'):affCleanName(e.getAttribute('href')))});
				});
				while (mediasArray[urlNum]['mediasrc'] != imageLink.getAttribute('href')) urlNum++;
			}
		}

		myLightbox.startLightBox();
		
		if (urlArray.length > 0) myLightbox.changeUrl(urlNum);
		else if (mediasArray.length > 0) myLightbox.changeImage(imageNum);
	},

	//	Hide most elements and preload image in preparation for resizing image container.
	changeImage: function(imageNum) {

		if (_myTimer) clearTimeout(_myTimer);
		activeImage = imageNum;	// update global var
	
		if (!isSet(mediasArray[activeImage])) return;

		$('loading').show();
		$('dyn_image','caption','prevLink','nextLink').each(Element.hide);

		var imgPreloader = new Image();
		imgPreloader.onload = function() {
			Element.setSrc('dyn_image', mediasArray[activeImage]['mediasrc']);
			myLightbox.resizeImageContainer(this.width, this.height);
		}
		imgPreloader.src = mediasArray[activeImage]['mediasrc'];
	},

	//	Hide most elements and load AJax Url
	changeUrl: function(urlNum) {	
		
		activeUrl = urlNum;	// update global var

		// hide elements during transition
		Element.show('loading');
		$('dyn_lightboxUrl','caption','prevLink','nextLink').each(Element.hide);

		// Size
		var ajaxW = 620;
		var ajaxH = 520;
		if (urlArray[activeUrl]['largeur'] && urlArray[activeUrl]['hauteur']) {
			ajaxW = urlArray[activeUrl]['largeur'];
			ajaxH = urlArray[activeUrl]['hauteur'];
		}

		$('outerImageContainer').style.overflow = 'hidden';
		
		// Ajax with Prototype.js
		var lbSpecs = urlArray[activeUrl]['mediasrc'].split('?');
		var lbContentUrl = lbSpecs[0];
		var lbParameters = lbSpecs[1];
		ajaxObjects[ajaxObjects.length] = new Ajax.Request(lbContentUrl, {
			method: 'get',
			parameters: lbParameters,
			evalScripts: true,
			contentType: 'text/html',
			encoding: 'iso-8859-1',
			onSuccess: function(transport) {
				$('dyn_lightboxUrl').update(transport.responseText);
				myLightbox.resizeImageContainer(ajaxW,ajaxH);
			}
		});
	},

	// ResizeImageContainer()
	resizeImageContainer: function(imgWidth, imgHeight) {

		imgWidth = parseInt(imgWidth) > 0 ? parseInt(imgWidth) : Element.getWidth('dyn_image');
		imgHeight = parseInt(imgHeight) > 0 ? parseInt(imgHeight) : Element.getWidth('dyn_image');
		imgWidth += borderSize * 2;
		imgHeight += borderSize * 2;

		centerDivBox('dyn_lightbox', imgWidth, imgHeight);
		Element.setWidth('dyn_lightbox', imgWidth);
		Element.setHeight('dyn_lightbox', imgHeight);
		
		/*var wCur = Element.getWidth('dyn_lightbox');
		var hCur = Element.getHeight('dyn_lightbox');
		var xScale = (imgWidth / wCur) * 100;
		var yScale = (imgHeight / hCur) * 100;
		wDiff = (wCur - borderSize * 2) - imgWidth;
		hDiff = (hCur - borderSize * 2) - imgHeight;
		centerDivBox('dyn_lightbox', imgWidth, imgHeight);
		if (hDiff != 0) new Effect.Scale('dyn_lightbox', yScale, {scaleX:false, duration:resizeDuration, scaleContent:false});
		if (wDiff != 0) new Effect.Scale('dyn_lightbox', xScale, {scaleY:false, duration:resizeDuration, scaleContent:false });
		if (hDiff == 0 && wDiff == 0) if (navigator.appVersion.indexOf("MSIE")!=-1) pause(250); else pause(100);
		*/

		myLightbox.showImage();
	},

	//	Display image and begin preloading neighbors. ///////////////////////////////////////////////////////////////
	showImage: function() {
		Element.hide('loading');
		if (mediasArray.length > 0) new Effect.Appear('dyn_image', { duration: resizeDuration, queue: 'end', afterFinish: function() { myLightbox.updateDetails(); } });
		else new Effect.Appear('dyn_lightboxUrl', { duration: resizeDuration, queue: 'end', afterFinish: function() { myLightbox.updateDetails(); } });
		if (mediasArray.length > 0) myLightbox.preloadNeighborImages();
	},

	//	Display caption, image number, and bottom nav.
	updateDetails: function() {

		// if image is part of set display 'Image x of x' 
		$('caption').show();
		
		if (mediasArray.length > 1) {
			if (mediasArray[activeImage]['legende']) {
				var leg = mediasArray[activeImage]['legende'].substr(0, 70) + (mediasArray[activeImage]['legende'].length > 70 ? '...' : '');
				$('caption').update('['+eval(activeImage+1)+'/'+mediasArray.length+'] <b>'+mediasArray[activeImage]['titre']+'</b> : '+leg);
			}
			else $('caption').update('['+eval(activeImage+1)+'/'+mediasArray.length+'] <b>'+mediasArray[activeImage]['titre']+'</b>');
		}
		else if (urlArray.length > 1) $('caption').update('['+eval(activeImage+1)+'/'+mediasArray.length+'] '+urlArray[activeUrl]['titre']);

		//$('toolNav').show();
		myLightbox.updateNav();
	},

	//	Display appropriate previous and next hover navigation.
	updateNav: function() {

		if (mediasArray.length < 1 && urlArray.length < 1) return;
		
		if (mediasArray.length > 1 && !slideShow) {
			// if not first image in set, display prev image button
			if (activeImage != 0) {
				Element.show('prevLink');
				$('prevLink').onclick = function() {
					myLightbox.changeImage(activeImage - 1); return false;
				}
			}
			// if not last image in set, display next image button
			if (activeImage != (mediasArray.length - 1)) {
				Element.show('nextLink');
				$('nextLink').onclick = function() {
					myLightbox.changeImage(activeImage + 1); return false;
				}
			}
		}
		else if (urlArray.length > 1) {
			if (activeUrl != 0) {
				Element.show('prevLink');
				$('prevLink').onclick = function() {
					myLightbox.changeUrl(activeUrl - 1); return false;
				}
			}
			if (activeUrl != (urlArray.length - 1)) {
				Element.show('nextLink');
				$('nextLink').onclick = function() {
					myLightbox.changeUrl(activeUrl + 1); return false;
				}
			}
		}
	},

	//	Preload previous and next images.
	preloadNeighborImages: function() {
		if (mediasArray.length > 0) {
			if ((mediasArray.length - 1) > activeImage) {
				preloadNextImage = new Image();
				preloadNextImage.src = mediasArray[activeImage + 1]['mediasrc'];
			}
			if (activeImage > 0) {
				preloadPrevImage = new Image();
				preloadPrevImage.src = mediasArray[activeImage - 1]['mediasrc'];
			}
		}
	},

	//	Kill Me !
	end: function() {
		if (_myTimer) clearTimeout(_myTimer);
		removeFadeOverlay();
		return false;
	}
}

// -----------------------------------------------------------------------------------
function pause(numberMillis) {
	var now = new Date();
	var exitTime = now.getTime() + numberMillis;
	while (true) {
		now = new Date();
		if (now.getTime() > exitTime) return;
	}
}
// -----------------------------------------------------------------------------------
function initLightbox() { myLightbox = new Lightbox(); }
Event.observe(window, 'load', initLightbox, false);