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
///// Code mixing by Molokoloco for Agence Clark... [BETA TESTING FOR EVER] ........... (o_O)  /////////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

//  rel="popimg"
//  rel="popimg[titreGal]"
//  rel="popurl[800|400]"
//  rel="popurl[Multi]"
//  onClick="return myLightbox.getImage('membres/eole/figue.jpg','Test');"
//	onClick="return myLightbox.getUrl('membres/eole/sommaire.htm','Test',720,300);"
//  onClick="return myLightbox.slidebox('membres/eole/galeries_medias_5.xml',2,'Test',720,300);"

// -----------------------------------------------------------------------------------

if (typeof startOverlay == 'undefined') throw("220_lightbox.js requires 214_modal-dialogue.js");

//	Images navigation
var fileLoadingImage = "images/common/loading.gif";		
var fileBottomNavCloseImage = "images/common/picto_fermer.gif";
var fileBottomNavNextImage = "js/ScriptAculous/lightboximages/next.gif";
var fileBottomNavPrevImage = "js/ScriptAculous/lightboximages/prev.gif";

var sliderImage = "images/action/slider.png";
var pathUrlDiaporama = './medias/galeries/grand/';

//	Configuration
var globalTempo = 6000; // 6 Sec
var dyn_overlayAlpha = 0.4;
var resizeDuration = 0.6;
var borderSize = 20;

//	Global Variables
var mediasArray = [];
var urlArray = []; // Ajax
var ajaxObjects = [];
var activeImage;
var activeUrl;
var setTempoSlider; // Slide tempo

var slideShow = false;
var onceScrollImageData = 0;
var xmlDoc; // XML doc

// -----------------------------------------------------------------------------------

var Lightbox = Class.create();

Lightbox.prototype = {

	initialize: function() {	
		if (!document.getElementsByTagName) return;
		var relAttribute = '';
		$$('a').each( function(e) {
			relAttribute = String(e.getAttribute('rel'));
			if (e.getAttribute('href') && ( (relAttribute.match('popimg')) || (relAttribute.match('popurl')) ) ) {
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
		
		startLightBox(166, 166);
		$('dyn_lightbox').update(getBoxTpl(false));
		$('bodyInfo').update('<div id="outerImageContainer"><div id="imageContainer"><img id="dyn_image"><div id="caption" style="display:none"></div><div id="dyn_lightboxUrl"></div><div id="loading" style="display:none;"><a href="javascript:void(0)" onClick="myLightbox.end();" id="loadingLink"><img src="'+fileLoadingImage+'" border="0"></a></div></div></div>');
		
		//$('toolNav').update('<a href="javascript:void(0)" id="prevLink" style="display:none;"><img src="'+fileBottomNavPrevImage+'"></a> <a href="javascript:void(0)" id="nextLink" style="display:none;"><img src="'+fileBottomNavNextImage+'"></a> <a href="javascript:void(0);" onClick="myLightbox.end();" id="BoxBtClose">Fermer</a>');
		
		$('toolNav').update('<a href="javascript:void(0);" onClick="myLightbox.end();" id="BoxBtClose">Fermer</a> <a href="javascript:void(0)" id="prevLink" style="display:none;">Pr&eacute;c&eacute;dante</a> <a href="javascript:void(0)" id="nextLink" style="display:none;">Suivante</a>');
		
		// mlklc //////////////////////
		// Controls Tool SlideShow // Gorêt coding by Molokoloco :)
		//$('diapoCtrl').update('<a href="javascript:myLightbox.nav(\'b\');" title="Image précédante" id="back"><img src="images/action/back.png" width="15" height="15" border="0"></a> <a href="javascript:myLightbox.pause();" title="Pause" id="pause"><img src="images/action/pause.png" width="15" height="15" border="0"></a> <a href="javascript:myLightbox.play();" title="Lecture" id="play"><img src="images/action/play.png" width="15" height="15" border="0"></a> <a href="javascript:myLightbox.nav(\'n\');" title="Image suivante" id="next"><img src="images/action/next.png" width="15" height="15" border="0"></a> <a href="javascript:void(0);" onClick="Element.toggle(\'slideTempoSpan\');this.blur();" title="Choisir le tempo" id="tempotoggler"><img src="images/action/tempo.png" width="15" height="15" border="0"></a>');
		
		$('dyn_lightbox').show();
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
		if (relAttribute.match('popimg')) { // image
			if (relAttribute == 'popimg') mediasArray.push({'mediasrc':imageLink.getAttribute('href'), 'titre':imageLink.getAttribute('title')}); // add single image to mediasArray
			else { // if image is part of a set.. loop through anchors, find other images in set, and add them to mediasArray
				$$('a').each( function(e) {					
					if (e.getAttribute('rel') == imageLink.getAttribute('rel')) mediasArray.push({'mediasrc':e.getAttribute('href'), 'titre':(e.getAttribute('title')?e.getAttribute('title'):affCleanName(e.getAttribute('href')))});
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
					if (e.getAttribute('rel') == imageLink.getAttribute('rel'))
						urlArray.push({'mediasrc':e.getAttribute('href'), 'titre':(e.getAttribute('title')?e.getAttribute('title'):affCleanName(e.getAttribute('href')))});
				});
				while (mediasArray[urlNum]['mediasrc'] != imageLink.getAttribute('href')) urlNum++;
			}
		}

		myLightbox.startLightBox();
		
		if (urlArray.length > 0) myLightbox.changeUrl(urlNum);
		else if (mediasArray.length > 0) myLightbox.changeImage(imageNum);
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

	//////////////////////////// mlklc CUSTOM ///////////////////////////////////////////////////////////////////////////////////////////
	slidebox: function(xmlPath,currentIndex) {	// INIT
		slideShow = true;
		mediasArray = [];
		urlArray = [];
		imageNum = 0;
		urlNum = 0;
		activeImage = currentIndex;
		setTempoSlider = null;

		if (document.implementation && document.implementation.createDocument) {
			xmlDoc = document.implementation.createDocument("", "", null);	
			xmlDoc.onload = myLightbox.parseXml;
		}
		else if (window.ActiveXObject) {
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.onreadystatechange = function () {
				if (xmlDoc.readyState == 4) myLightbox.parseXml();
			};
		}
		else {
			printInfo('Votre navigateur ne supporte pas le XML.<br />Le diaporama risque de ne pas fonctionner');
			return;
		}

		myLightbox.startOverlay();
		myLightbox.startLightBox();
		xmlDoc.load(xmlPath);

		return false; // Don't open href
	},
	
	parseXml: function() { //currentIndex,w,h

		/* XML FORMAT
		<medias>
			<media>
				<id><![CDATA[63]]></id>
				<titre><![CDATA[Arbre]]></titre>
				<legende><![CDATA[]]></legende>
				<miniature><![CDATA[070305142303_arbre.jpg]]></miniature>
				<mediasrc><![CDATA[070305142303_arbre.jpg]]></mediasrc>
				<legende_pos><![CDATA[8tl]]></legende_pos>
				<extension><![CDATA[jpg]]></extension>
				<largeur><![CDATA[624]]></largeur>
				<hauteur><![CDATA[420]]></hauteur>
				<medialink><![CDATA[http://localhost/www.saintdesprit.org/medias/galerie/070305142303_arbre.jpg]]></medialink>
			</media>
			...
		</medias>
		db(xmlDoc.getElementsByTagName('id')[0].childNodes[0].nodeValue); */

		// EACH MEDIA
		if (xmlDoc) {
			var nodes = $A(xmlDoc.getElementsByTagName('media'));
			nodes.each( function(e) {
				mediasArray.push({
					'id':			e.getElementsByTagName('id')[0].childNodes[0].nodeValue,
					'titre':		e.getElementsByTagName('titre')[0].childNodes[0].nodeValue,
					'legende':		e.getElementsByTagName('legende')[0].childNodes[0].nodeValue.stripTags(),
					'miniature':	e.getElementsByTagName('miniature')[0].childNodes[0].nodeValue,
					'mediasrc':		e.getElementsByTagName('mediasrc')[0].childNodes[0].nodeValue,
					'legende_pos':	e.getElementsByTagName('legende_pos')[0].childNodes[0].nodeValue,
					'extension': 	e.getElementsByTagName('extension')[0].childNodes[0].nodeValue,
					'largeur':		e.getElementsByTagName('largeur')[0].childNodes[0].nodeValue,
					'hauteur':		e.getElementsByTagName('hauteur')[0].childNodes[0].nodeValue
				});	
			});
		}
		else printInfo('Problème de lecture du fichier XML.<br />Le diaporama risque de ne pas fonctionner');

		if (!mediasArray.length) return printInfo('Le fichier XML associé a ce diaporama semble vide');
		else if (mediasArray.length > 1) myLightbox.play(activeImage); // start slide.... //////////////////////////////////////
		else if (mediasArray[0]['mediasrc']) myLightbox.getImage(pathUrlDiaporama + mediasArray[0]['mediasrc'], mediasArray[0]['titre']);
	},

	// Controler diaporama
	pause: function() {
		if (_myTimer) clearTimeout(_myTimer);
		Element.hide('pause');
		Element.show('play');
		printInfo('Pause');
	},
	
	play: function(imageNum) {
		if (_myTimer) clearTimeout(_myTimer);
		Element.hide('play');
		Element.show('pause');
		myLightbox.changeImage(imageNum);
		printInfo('Lecture (Tempo : '+(globalTempo/1000)+' secondes)');
	},
	
	settempo: function(tempo) {
		if (_myTimer) clearTimeout(_myTimer);
		$('slideTempoSpan').hide();
		tempo = parseInt(tempo);
		if (tempo < 2) tempo = 2;
		globalTempo = tempo * 1000;
		myLightbox.play(activeImage);
	},

	//	Hide most elements and preload image in preparation for resizing image container.
	changeImage: function(imageNum) {

		if (_myTimer) clearTimeout(_myTimer);
		activeImage = imageNum;	// update global var
		
		if (!isSet(mediasArray[activeImage])) return;

		// hide elements during transition
		$('loading').show();
		//$('dyn_image','dyn_lightboxUrl','toolNav','prevLink','nextLink').each(Element.hide);
		//if (!slideShow || (slideShow && onceScrollImageData != 1) ) $('numberDisplay','diapoCtrl','toolNav').each(Element.hide);
		
		// once image is preloaded, resize image container
		imgPreloader = new Image();

		if (!slideShow) {
			imgPreloader.onload = function() {
				Element.setSrc('dyn_image', mediasArray[activeImage]['mediasrc']);
				myLightbox.resizeImageContainer(imgPreloader.width, imgPreloader.height);
			}
			imgPreloader.src = mediasArray[activeImage]['mediasrc'];
		}
		else {
			imgPreloader.onload = function() {
				Element.setSrc('dyn_image', pathUrlDiaporama + mediasArray[activeImage]['mediasrc']);
				myLightbox.resizeImageContainer(imgPreloader.width, imgPreloader.height);
				
				if (globalTempo < 3000) globalTempo = 3000;
				else if (globalTempo > 60000) globalTempo = 60000;
				if (activeImage != (mediasArray.length - 1)) activeNext = activeImage + 1;
				else activeNext = 0;
				_myTimer = setTimeout("myLightbox.changeImage("+activeNext+");", globalTempo);
			}
			imgPreloader.src = pathUrlDiaporama + mediasArray[activeImage]['mediasrc'];
		}
	},

	//	Hide most elements and load AJax Url
	changeUrl: function(urlNum) {	
		
		activeUrl = urlNum;	// update global var

		// hide elements during transition
		Element.show('loading');
		$('diapoCtrl','dyn_image','dyn_lightboxUrl','toolNav','prevLink','nextLink','numberDisplay','toolNav').each(Element.hide);
	 
		// Size
		var ajaxW = 620;
		var ajaxH = 520;
		if (urlArray[activeUrl]['largeur'] && urlArray[activeUrl]['hauteur']) {
			ajaxW = urlArray[activeUrl]['largeur'];
			ajaxH = urlArray[activeUrl]['hauteur'];
		}

		$('outerImageContainer').style.overflow = 'hidden';
		
		// Ajax with Prototype.js
		var lbUrl = urlArray[activeUrl]['mediasrc'];
		var lbSpecs = lbUrl.split('?');
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
		
		imgWidth = parseInt(imgWidth);
		imgHeight = parseInt(imgHeight);
		imgWidth += borderSize * 2;
		imgHeight += borderSize * 2;
		imgWidth = imgWidth < 300 ? 300 : imgWidth; 
		imgHeight = imgHeight < 120 ? 120 : imgHeight; 

		if (urlArray.length > 0) {
			$('outerImageContainer').style.overflow = 'auto';
			// Auto-size to Inner Content : if Ajax Load some king of html with <img id="media_media"/>)
			if ( $('media_media') && $('media_media').getAttribute('src')) {
				var relAttribute = String( $('media_media').getAttribute('src') );
				if (relAttribute.match('.jpg') || relAttribute.match('.gif') || relAttribute.match('.png')) {
					imgWidth = parseInt($('media_media').getAttribute('width'));
					imgHeight = parseInt($('media_media').getAttribute('height'));
					$('outerImageContainer').style.overflow = 'hidden';
				}
			}
		}

		var wCur = Element.getWidth('dyn_lightbox');
		var hCur = Element.getHeight('dyn_lightbox');
		var xScale = (imgWidth / wCur) * 100;
		var yScale = (imgHeight / hCur) * 100;
		wDiff = (wCur - borderSize * 2) - imgWidth;
		hDiff = (hCur - borderSize * 2) - imgHeight;

		centerDivBox('dyn_lightbox', imgWidth, imgHeight);
		if (hDiff != 0) new Effect.Scale('dyn_lightbox', yScale, {scaleX:false, duration:resizeDuration, scaleContent:false});
		if (wDiff != 0) new Effect.Scale('dyn_lightbox', xScale, {scaleY:false, duration:resizeDuration, scaleContent:false });
		
		if (hDiff == 0 && wDiff == 0){
			if (navigator.appVersion.indexOf("MSIE")!=-1) pause(250); else pause(100);
		}

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
			
		// PREPARE CONTROLER TOOL BAR
		if (mediasArray.length > 1 && slideShow) {
			// $('back','pause','play','next').each(Element.hide);
			if (onceScrollImageData != 1) // if not first image in set, display prev image button
				new Effect.Appear('diapoCtrl', { duration: 0.3, from: 0.0, to: 0.9, queue: 'front'});

			if (activeImage != 0) {
				Element.show('back');
				$('back').onclick = function() {
					myLightbox.changeImage(activeImage - 1);
					Element.hide('play');
					Element.show('pause');
					return false;
				};
			}
			else Element.hide('back');
			
			if (activeImage != (mediasArray.length - 1)) activeNext = activeImage + 1;
			else activeNext = 0;
			
			// if not last image in set, display next image button
			if (activeImage != (mediasArray.length - 1)) {
				Element.show('next');
				$('next').onclick = function() { 
					myLightbox.changeImage(activeNext);
					Element.hide('play');
					Element.show('pause');
					return false;
				}
			}
			else Element.hide('next');
			
			// Tempo Slider
			if (!setTempoSlider) {
				setTempoSlider = new Control.Slider('handleTempo','slideTempo', {
					axis:'horizontal',
					minimum: 3,
					maximum: 120,
					alignX: 3,
					alignY: -2,
					increment: 1,
					range: $R(3,120),
					sliderValue: ((globalTempo/1000)*2)
				});
				setTempoSlider.options.onSlide = function(value){
					value = Math.floor(value/2);
					value = Math.max(3,value);
					value = Math.min(value,60);
					printInfo('Tempo <b>'+value+'</b> sec');
				};
				setTempoSlider.options.onChange = function(value){
					value = Math.floor(value/2);
					value = Math.max(3,value);
					value = Math.min(value,60);
					myLightbox.settempo(value);
				};
			}
			
			$('play').href = 'javascript:myLightbox.play('+activeNext+');';
			
			$('diapoCtrl').show();
		}
		
		

		// if image is part of set display 'Image x of x' 
		Element.show('caption');
		if (mediasArray.length > 1) {
			if (mediasArray[activeImage]['legende']) {
				var leg = mediasArray[activeImage]['legende'].substr(0,70) + (mediasArray[activeImage]['legende'].length > 70 ? '...' : '');
				Element.setInnerHTML( 'caption', '['+eval(activeImage+1)+'/'+mediasArray.length+'] <b>'+mediasArray[activeImage]['titre']+'</b> : '+leg);
			}
			else Element.setInnerHTML( 'caption', '['+eval(activeImage+1)+'/'+mediasArray.length+'] <b>'+mediasArray[activeImage]['titre']+'</b>');

		}
		else if (urlArray.length > 1) {
			Element.setInnerHTML( 'caption', '['+eval(activeImage+1)+'/'+mediasArray.length+'] '+urlArray[activeUrl]['titre']);
		}
		if (!slideShow || (slideShow && onceScrollImageData != 1) ) {
			onceScrollImageData = 1;
			$('toolNav').show();
			myLightbox.updateNav();
			/*Element.setWidth('toolNav', Element.getWidth('outerImageContainer'));
			new Effect.Parallel( [ 
				new Effect.SlideDown( 'toolNav', { sync: true, duration: resizeDuration + 0.25, from: 0.0, to: 1.0 }), 
				new Effect.Appear('toolNav', { sync: true, duration: 1.0 })
				], { duration: 0.65, afterFinish: function() { myLightbox.updateNav(); } } 
			);*/
		}
		else if (slideShow) {
			//$('toolNav').show();
			myLightbox.updateNav();
		}
	},

	//	Display appropriate previous and next hover navigation.
	updateNav: function() {

		if (mediasArray.length < 1 && urlArray.length < 1) return;
		
		Element.show('toolNav');	

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