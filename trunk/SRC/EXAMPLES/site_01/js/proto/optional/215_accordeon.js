/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/

/*--------------------------- ACCORDEON -------------------------------*/

// <a href="javascript:void(0);" id="a_<?=$i;?>" class="accordeon2 off" onfocus="blur();">Titre</a>
// <div class="accordeon2">Texte cache</div>
// ...
// <?php js(" new Accordeon('a.accordeon2', 'div.accordeon2', {tClassOff:'off', tClassOn:'in', tempo:0.4, observe:'click'}); "); ?>

// ------------------------- A TITRE ET TEXT DIV - CLASS ACCORDEON ---------------------------------- //
Accordeon = Class.create();
Accordeon.prototype = {
	initialize: function(xTitles, xPanes, options) {
		this.xTitles = $$(xTitles);
		this.xPanes = $$(xPanes);
		if (this.xTitles.length < 1 || this.xTitles.length != this.xPanes.length)
			die('No xTitles detected (Ex. a.accordeon) or length mismatch between panes elements');
		
		this.tClassOff 		= options.tClassOff || 'off';
		this.tClassOn 		= options.tClassOn || 'on';
		this.tClassApply 	= options.tClassApply || 'before'; // after
		this.tempo 			= options.tempo || 0.4;
		this.observe 		= options.observe || 'click'; // mouseover
		this._activeTitle 	= -1;
		this._effectAnim 	= false;
		for (var i=0; i<this.xTitles.length; i++) {
			$(this.xPanes[i]).hide();
			Event.observe(this.xTitles[i], this.observe, this.ctrPane.bindAsEventListener(this, i), false);
			linkEvent = 'on'+this.observe;
			$(this.xPanes[i]).linkEvent = '';
		}
	},
	setTitleClassOn: function(i) {
		$(this.xTitles[i]).addClassName(this.tClassOn);
		$(this.xTitles[i]).removeClassName(this.tClassOff);
	},
	setTitleClassOff: function(i) {
		$(this.xTitles[i]).addClassName(this.tClassOff);
		$(this.xTitles[i]).removeClassName(this.tClassOn);
	},
	toggleTitleClass: function(i, display) {
		if (display) {
			if (this._activeTitle >= 0) this.setTitleClassOff(this._activeTitle);
			this.setTitleClassOn(i);
		}
		else {
			this.setTitleClassOff(i);
			if (this._activeTitle >= 0) this.setTitleClassOn(this._activeTitle);
		}
	},
	togglePane: function(i, display) {
		if (!isId(this.xTitles[i]) || this._effectAnim) return;
		this._effectAnim = true;
		this.effectParams = {
			duration: this.tempo,
			transition: Effect.Transitions.linear,
			afterFinish:function(){ 
				if (this.tClassApply != 'before') this.toggleTitleClass(i, display);
				this._effectAnim = false;
				new Effect.ScrollTo(this.xTitles[i], {offset: -90});
				//if (typeof setPageSize == 'function') setPageSize();
			}.bind(this, display)
		};
		if (this._activeTitle >= 0) {
			new Effect.Parallel([				
					new Effect.BlindUp($(this.xPanes[this._activeTitle])),
					(display ? new Effect.BlindDown($(this.xPanes[i])) : new Effect.BlindUp($(this.xPanes[i])) )
			], this.effectParams);
		}
		else ( display ? new Effect.BlindDown($(this.xPanes[i]), this.effectParams) : new Effect.BlindUp($(this.xPanes[i]), this.effectParams) );
		if (this.tClassApply == 'before') this.toggleTitleClass(i, display);
	},
	togglePanes: function(display) {
		for(var i=0; i<this.xTitles.length; i++) this.togglePane(i, display);
	},
	ctrPane: function(event, i) {
		if (!event || typeof Event.element != 'function') return;
		if (i == this._activeTitle) {
			this._activeTitle = -1;
			this.togglePane(i, false);
		}
		else {
			this.togglePane(i, true);
			this._activeTitle = i;
		}
	}
};