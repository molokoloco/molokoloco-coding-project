/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

// ------------------------- REQUIRE :) ---------------------------------- //
if (typeof Element == 'undefined') throw('menu.js requires prototype.js library');
if (typeof db == 'undefined') throw('menu.js requires tools.js library');
if (typeof PeriodicalExecuter == 'undefined') throw('menu.js requires PeriodicalExecuter library');

// ------------------------- MENU UL CLASS CASCADING NAV 0.2 ---------------------------------- //
MenuUl = Class.create();
MenuUl.prototype = {
	initialize: function(id_menu) {
		if (!isId(id_menu)) return;
		var options = Object.extend({id_menu:id_menu}, arguments[1] || {});	
		this.tempo = options.tempo || 0.6;
		this.autoHide = options.autoHide || true;
		this.id_menu = options.id_menu;
		this.menu = $(this.id_menu);
		this._timer = null;
		$$('#'+this.id_menu+' a').each(function(e) {
			Event.observe(e, 'mouseover', this.showSsMenu.bindAsEventListener(this, e), false);
			if (this.autoHide) Event.observe(e, 'mouseout', this.hideSsMenu.bindAsEventListener(this, e), false);
		}.bind(this));
		if (this.autoHide) this.callEffaceMenu();
	},
	showSsMenu: function(event) {
		if (!event || typeof Event.element != 'function') return;
		elm = Event.element(event);
		ul_parent = $($(elm).parentNode).parentNode;
		this.effaceMenu(ul_parent, false);
		ul = $($(elm).parentNode).getElementsByTagName('ul')[0];
		if (ul) {
			a = $($(elm).parentNode).getElementsByTagName('a')[0]; // The <a> is the box, get Dim
			pos = Element.positionedOffset(a);
			if (ul_parent.id != this.id_menu) {
				dim = $(a).getDimensions();
				ul.style.left = (pos.left+dim.width)+'px';
				ul.style.top = pos.top+'px';
			}
			else ul.style.left = pos.left+'px';
			ul.style.display = 'block';
			//if (client.isIe()) showHideBoxes('hidden');
		}
	},
	hideSsMenu: function() {
		this._timer = new PeriodicalExecuter(this.callEffaceMenu.bind(this), this.tempo);
	},
	callEffaceMenu: function() {
		this.effaceMenu(this.menu, true);
		//if (client.isIe()) showHideBoxes('visible');
	},
	effaceMenu: function(ul, recur) {
		if (this._timer) this._timer.stop();
		li = ul.getElementsByTagName('li');
		for (var i=0; i<li.length; i++) {
			ul = li[i].getElementsByTagName('ul')[0];
			if (ul) {
				if (recur) {
					// this.effaceMenu(ul); // why dont work ?
					li2 = ul.getElementsByTagName('li');
					for (var j=0; j<li2.length; j++) {
						ul2 = li2[j].getElementsByTagName('ul')[0];
						if (ul2) ul2.style.display = 'none';
					}
				}
				ul.style.display = 'none';
			}
		}
	}
};

//swfobject.addDomLoadEvent(
Event.observe(window, 'load',function() {
	new MenuUl('menu');
});


// ------------------------- DYN MENU 0.1 ---------------------------------- //
/*var maxDist = 160;
var effectDuration = 0.3;
var activeSmenu = '';
var mX = '';
var mY = '';

var resetMenu = function(evt) {
	if (evt) Event.stop(evt);
	Event.stopObserving(document.body, 'mouseover');
	$$('#menu ul.sousMenu').each( function(f) {
		if (f.getAttribute('id') != activeSmenu && $(f).style.display != 'none') {
			//new Effect.BlindUp(f, {duration:effectDuration});
			$(f).hide();
		}
	});
};
var hideIfFar = function(evt) {
	if (!evt || !isId(activeSmenu)) return;
	var mXdoc = Event.pointerX(evt);
	var mYdoc = Event.pointerY(evt);
	if (mXdoc - mX > maxDist || mX - mXdoc > maxDist || mYdoc - mY > maxDist || mY - mYdoc > maxDist) {
		//new Effect.BlindUp(activeSmenu, {duration:effectDuration});
		$(activeSmenu).hide();
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
				Element.setLeft(activeSmenu, $(parentActiveSmenu).offsetLeft - 10);
				Element.setTop(activeSmenu, $(parentActiveSmenu).offsetTop + 21);
				//new Effect.BlindDown(activeSmenu, {duration:effectDuration});
				$(activeSmenu).show();
				Event.observe(document.body, 'mouseover', hideIfFar, false);
			});
		}
	});
	$$('#menu a.menu').each( function(e) {
		Event.observe(e, 'mouseover', function(evt) { activeSmenu = ''; resetMenu(evt); }, false);
	});
};
Event.observe(window, 'load', makeMenu);*/
