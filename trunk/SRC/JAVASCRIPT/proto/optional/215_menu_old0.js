/////////// DHTML MENU //////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Ici les fonctions spécifiques au site
*/

// ------------------------- REQUIRE :) ---------------------------------- //
if (typeof Element == 'undefined') throw('specifique.js requires prototype.js library');
if (typeof Event == 'undefined') throw('specifique.js requires prototype.js library');

// ------------------------- DYN MENU ---------------------------------- //
var maxDist = 160;
var effectDuration = 0.6;
var activeSmenu = '';
var mX = '';
var mY = '';

var resetMenu = function(evt) {
	if (evt) Event.stop(evt);
	Event.stopObserving(document.body, 'mouseover');
	$$('#menu ul.sousMenu').each( function(f) {
		if (f.getAttribute('id') != activeSmenu && $(f).style.display != 'none')
			new Effect.BlindUp(f, {duration:effectDuration});
	});
};
var hideIfFar = function(evt) {
	if (!evt || !isId(activeSmenu)) return;
	var mXdoc = Event.pointerX(evt);
	var mYdoc = Event.pointerY(evt);
	if (mXdoc - mX > maxDist || mX - mXdoc > maxDist || mYdoc - mY > maxDist || mY - mYdoc > maxDist) {
		new Effect.BlindUp(activeSmenu, {duration:effectDuration});
		resetMenu(evt);
	}
};
var makeMenu = function(evt) {
	if (evt) Event.stop(evt);
	if (!isId('menu')) return;
	$$('#menu a.menuParent').each( function(e) {
		var eId = e.getAttribute('id');
		if (eId) {
			var mymatch = /menu_([0-9a-z_-]+)/.exec(eId);
			var eId_id = mymatch[1];
			Event.observe(eId, 'mouseover', function(evt) { 
				mX = Event.pointerX(evt);
				mY = Event.pointerY(evt);
				parentActiveSmenu = 'menu_'+eId_id;
				activeSmenu = 'smenu_'+eId_id;
				resetMenu(evt);
				if (isId(parentActiveSmenu)) Element.makePositioned(parentActiveSmenu);
				Element.setLeft(activeSmenu, $(parentActiveSmenu).offsetLeft - 25);
				Element.setTop(activeSmenu, $(parentActiveSmenu).offsetTop + 26);
				new Effect.BlindDown(activeSmenu, {duration:effectDuration});
				Event.observe(document.body, 'mouseover', hideIfFar, false);
			});
		}
	});
	$$('#menu a.menu').each( function(e) {
		Event.observe(e, 'mouseover', function(evt) { activeSmenu = ''; resetMenu(evt); }, false);
	});
};
Event.observe(window, 'load', makeMenu);

/* --------------------------- Accordeon Effect------------------------------- */
// --------Exemple d'utilisation :  onclick="accordeonEffect('1','div.accordeon', {a_id:'a', div_id:'div', class_off:'off', class_in:'in', duree:0.3, class_position:'before(after)'});" ------------ //
/**
 * Fonction pour créer un effet d'accordéon au clic sur un lien
 * Qui ouvre le conteneur lié (div)
 * @author Virginie
 * @copyright 22/11/2007
 *
 * @param string $id Chaine contenant le numéro du bloc (lien+div)
 * @param string $all_search Chaine contenant les éléments à cibler
 */
var accordeonEffect = function(id, all_search){
	
	// Récupération des variables optionnelles
	var options = Object.extend({id:id,all_search:all_search}, arguments[2] || {});
	if (!options.a_id) options.a_id = 'a';
	if (!options.div_id) options.div_id = 'div';
	if (!options.class_off) options.class_off = 'off';
	if (!options.class_in) options.class_in = 'in';
	if (!options.duree) options.duree = 0.3;
	if (!options.class_position) options.class_position = 'before';

	var etat = getClassNames(options.a_id+id);
	
	$$(all_search).each( 
		function(e,i) {
			$(options.a_id+i).removeClassName(options.class_in);
			$(options.a_id+i).addClassName(options.class_off);
			
			Effect.BlindUp(
				$(options.div_id+i), {
					duration:options.duree,
					transition : Effect.Transitions.linear,
					afterFinish: function(){
						if(i==id && inArray(options.class_off,etat)){
							Effect.BlindDown(
								$(options.div_id+id),{
									duration:options.duree,
									afterFinish:function(){
										if(options.class_position == 'after'){
											$(options.a_id+i).addClassName(options.class_in);
											$(options.a_id+i).removeClassName(options.class_off);
										}
									}
								}
							);	
							if(options.class_position == 'before'){
								$(options.a_id+i).addClassName(options.class_in);
								$(options.a_id+i).removeClassName(options.class_off);
							}
						}
					}
				}
			);
		}
	);
};