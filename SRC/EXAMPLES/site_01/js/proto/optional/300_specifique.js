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