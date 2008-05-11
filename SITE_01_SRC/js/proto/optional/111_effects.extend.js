/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Borntobeweb.fr... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

Functions :

	Element.getWidt(element)
	Element.setWidt(element,w)
	Element.setHeight(element,h)
	Element.setTop(element,t)
	Element.setLeft(element,l)
	Element.setSrc(element,src)
	Element.setHref(element,href)
	Element.setInnerHTML(element,content)
	
	removeDuplicates()
	empty()
	Element.isBlockLevel(element)
	Element.show()
	Element.getDimensions(element)
	
	new Effect.Center('myDivId')
	new Effect.divSwap = function(element,container)
	new Effect.ScrollFromLeft = function(element)
	new Effect.ScrollToRight = function(element)
	new Effect.OpenAndCloseModule = function(detectId,openId,expandId,closeId)
	new Effect.Chain('Fade', $$('div.test'),  { duration: 0.5 } );
	new Effect.DelayedChain('Appear', $$('ul#test li'), { duration: 0.5 }, 100);

*/

// Check lighboxV2.js ///////////////////////////////////////////////////
Object.extend(Element, {
	setWidth: function(element,w) {
	   	element = $(element);
    	element.style.width = w +"px";
	},
	setHeight: function(element,h) {
   		element = $(element);
    	element.style.height = h +"px";
	},
	setTop: function(element,t) {
	   	element = $(element);
    	element.style.top = t +"px";
	},
	setLeft: function(element,l) {
	   	element = $(element);
    	element.style.left = l +"px";
	},
	getTop: function(element) {
	   	element = $(element);
    	return element.style.top;
	},
	getLeft: function(element) {
	   	element = $(element);
    	return element.style.left;
	},
	setSrc: function(element,src) {
    	element = $(element);
    	element.src = src; 
	},
	setHref: function(element,href) {
    	element = $(element);
    	element.href = href; 
	},
	setInnerHTML: function(element,content) {
		$(element).update(content);
	}
});

var getClassNames = function(id){
	return $(id).className.split(' ');
};

Array.prototype.removeDuplicates = function () {
	for (i = 1; i < this.length; i++) {
		if (this[i][0] == this[i-1][0]) this.splice(i,1);
	}
}

Array.prototype.empty = function () {
	for(i = 0; i <= this.length; i++) this.shift();
}

/*Element.getDimensions = function(element) {
    element = $(element);
    var display = $(element).getStyle('display');
	if (display != 'none' && display != null)
		return {
		width: element.offsetWidth,
		height: element.offsetHeight
	};
    var els = element.style;
    var originalVisibility = els.visibility;
    var originalPosition = els.position;
    els.visibility = "hidden";
    els.position = "absolute";
    els.display = Element.isBlockLevel(element) ? "block": "";
    var originalWidth = element.clientWidth;
    var originalHeight = element.clientHeight;
    els.display = "none";
    els.position = originalPosition;
    els.visibility = originalVisibility;
    return {
        width: originalWidth,
        height: originalHeight
    };
};
*/
/* ------------------------- CENTER DIV ---------------------------------- */
// Effect.Center('loadertag');
// Effect.Appear('loadertag');
Effect.Center = function(element) { // TO check
    try { element = $(element); }
    catch(e) { return; }

	var arraySize = getPageSize();
    var arrayScroll = getPageScroll();

	var my_width  = arraySize[2];
	var my_height = arraySize[3];
	var scrollY = arrayScroll[1];
	
	element.style.position = 'absolute';
	element.style.display  = 'block';
	element.style.zIndex   = 99;
	
	var elementDimensions = Element.getDimensions(element);
	
	var offsetX = ( my_width  - elementDimensions.width  ) / 2;
	var offsetY = ( my_height - elementDimensions.height ) / 2 + scrollY;
	
	offsetX = ( offsetX < 0 ) ? 0 : offsetX;
	offsetY = ( offsetY < 0 ) ? 0 : offsetY;
	
	Element.setTop(element, offsetY);
	Element.setLeft(element, offsetX);
}

/* ------------------------- HORIZONTALE SCROLL FROM LEFT TO RIGHT ---------------------------------- */
Effect.ScrollFromLeft = function(element) {
	Zelement = $(element);
	Element.cleanWhitespace(Zelement);
	var elementDimensions = Element.getDimensions(element);
	
	var PelementDimensions = Element.getDimensions($(element).parentNode); // Parent
	var centerY = (PelementDimensions.width - elementDimensions.width) / 2;
	
	var exLeft = parseInt(elementDimensions.width) ;
	var newLeft = exLeft * -1 + centerY;
	
	Element.setStyle(Zelement, {left: newLeft+'px' });
	
	return new Effect.MoveBy(element, 0, exLeft,
	Object.extend({ 
	restoreAfterFinish: true,
	beforeStartInternal: function(effect) { with(Element) {
		makePositioned(effect.element);
		makeClipping(effect.element);
		show(element); }}, 
	afterFinishInternal: function(effect) { with(Element) {
	   [undoClipping].call(effect.element);
		undoPositioned(effect.element.firstChild);
		undoPositioned(effect.element);
		/*setStyle(effect.Zelement, {left: 0});*/ }}
	}, arguments[1] || {})
	);
}

Effect.ScrollToRight = function(element) {
  Zelement = $(element);
  Element.cleanWhitespace(Zelement);
  var newRight = parseInt(Zelement.up().getStyle('width'));
  return new Effect.MoveBy(element, 0, newRight,
   Object.extend({ 
    restoreAfterFinish: true,
    beforeStartInternal: function(effect) { with(Element) {
		makePositioned(effect.element);
		makeClipping(effect.element);
		show(Zelement); }}, 
    afterFinishInternal: function(effect) { with(Element) {
       [hide, undoClipping].call(effect.element);
        undoPositioned(effect.element.firstChild);
        undoPositioned(effect.element);
        /*setStyle(effect.element, {left: 0}); */}}
   }, arguments[1] || {})
  );
}

/* -------------------------  HORIZONTALE SLIDE LEFT TO LEFT ---------------------------------- */

/*
Effect.SlideRight = function(element) {
	element = $(element);
	Element.cleanWhitespace(element);
	// SlideDown need to have the content of the element wrapped in a container element with fixed height!
	var oldInnerRight = Element.getStyle(element.firstChild, 'right');
	var elementDimensions = Element.getDimensions(element);
	return new Effect.Scale(element, 100, Object.extend({ 
		scaleContent: false,
		scaleY: false, 
		scaleFrom: 0,
		scaleMode: {originalHeight: elementDimensions.height, originalWidth: elementDimensions.width},
		restoreAfterFinish: true,
		afterSetup: function(effect) { with(Element) {
			makePositioned(effect.element);
			makePositioned(effect.element.firstChild);
			if(window.opera) setStyle(effect.element, {top: ''});
			makeClipping(effect.element);
			setStyle(effect.element, {width: '0px'});
			show(element); }},
		afterUpdateInternal: function(effect) { with(Element) {
			setStyle(effect.element.firstChild, {right: (effect.dims[0] - effect.element.clientWidth) + 'px' }); }},
		afterFinishInternal: function(effect) { with(Element) {
			undoClipping(effect.element); 
			undoPositioned(effect.element.firstChild);
			undoPositioned(effect.element);
			setStyle(effect.element.firstChild, {right: oldInnerRight}); }}
		}, arguments[1] || {})
  );
}

Effect.SlideLeft = function(element) {
  element = $(element);
  Element.cleanWhitespace(element);
  var oldInnerRight = Element.getStyle(element.firstChild, 'right');
  return new Effect.Scale(element, 0, 
   Object.extend({ scaleContent: false, 
    scaleY: false, 
    scaleMode: 'box',
    scaleFrom: 100,
    restoreAfterFinish: true,
    beforeStartInternal: function(effect) { with(Element) {
      makePositioned(effect.element);
      makePositioned(effect.element.firstChild);
      if(window.opera) setStyle(effect.element, {top: ''});
      makeClipping(effect.element);
      show(element); }},  
    afterUpdateInternal: function(effect) { with(Element) {
      setStyle(effect.element.firstChild, {right:
        (effect.dims[0] - effect.element.clientWidth) + 'px' }); }},
    afterFinishInternal: function(effect) { with(Element) {
        [hide, undoClipping].call(effect.element); 
        undoPositioned(effect.element.firstChild);
        undoPositioned(effect.element);
        setStyle(effect.element.firstChild, {right: oldInnerRight}); }}
   }, arguments[1] || {})
  );
}
*/

/* ------------------------- ACCORDEON MENU ---------------------------------- */
var fadeDur = 0.15 // Fade out duration
var AppearDur = 0.15 // Fade in duration
var SlideDur = 0.3 // Panel slide duration

Effect.OpenAndCloseModule = function(detectId,openId,expandId,closeId) {
	detectId = $(detectId); // So we can detect if the panel is currently visible or not before we run the condition
	openId = $(openId); // The element we click on to open a panel, in this case the plus icon
	expandId = $(expandId); // The panel we want to expand
	closeId = $(closeId); // The element we click on to close a panel, in this case the plus icon
	
	if(detectId.visible(detectId))
    	Effect.Fade(closeId,{duration:fadeDur,afterFinish: function(){Effect.SlideUp(expandId,{duration:SlideDur, afterFinish: function(){Effect.Appear(openId, {duration:AppearDur})}})}});
    else
    	new Effect.Fade(openId,{duration:fadeDur,afterFinish: function(){new Effect.SlideDown(expandId,{duration:SlideDur, afterFinish: function(){new Effect.Appear(closeId, {duration:AppearDur})}})}});
}

/* ------------------------- MULTI ELEMENT EFFECT ---------------------------------- */
/*
	new Effect.Chain(
	   'Fade', // The effect name
	   $$('div.test'),  // an array of elements
	   { duration: 0.5 } // options for the effect itself
	);
	
	// Fires an Effect.Appear for each element with a delay of 100 m
	new Effect.DelayedChain('Appear', $$('ul#test li'), { duration: 0.5 }, 100);
*/

Effect.DelayedChain = Class.create();
Object.extend(Effect.DelayedChain.prototype, {
    initialize: function(effect, elements, options, timeout){
        this.elements = elements;
        this.effect = effect;
        this.timeout = timeout || 100;
        this.options = Object.extend({}, options || {});

        this.afterFinish = this.options.afterFinish || Prototype.emptyFunction;
        this.options.afterFinish = Prototype.emptyFunction;
        setTimeout(this.action.bind(this),1);
    },
    action: function() {
        if(this.elements.length){ 
            new Effect[this.effect](this.elements.shift(), this.options);
            setTimeout(this.action.bind(this), this.timeout);
        } else {
            if(this.afterFinish) this.afterFinish();
        }
    }
});

Effect.Chain = Class.create();
Object.extend(Effect.Chain.prototype, {
    initialize: function(effect, elements, options){
        this.elements = elements || [];
        this.effect = effect;
        this.options = options || {};
        this.afterFinish = this.options.afterFinish || Prototype.emptyFunction;
        this.options.afterFinish = this.nextEffect.bind(this);
        setTimeout(this.nextEffect.bind(this), 1);
    },
    nextEffect: function(){
        if(this.elements.length)
            new Effect[this.effect](this.elements.shift(), this.options);
        else
            this.afterFinish();
    }
});