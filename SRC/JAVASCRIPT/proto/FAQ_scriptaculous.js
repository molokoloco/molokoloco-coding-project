///////////////////////////////////////////////////////////////////////////////////////////
/// CODE MIXING BY SCRIPT.ACUL.US ............................ (o_O)

// Effects list :

	+-> Effect.Opacity
	+-> Effect.Scale(element, percent, [options]);
	+-> new Effect.MoveBy(element, 100, 0, { sync: true }), 
	+-> new Effect.Highlight('td_element_'+element_id ,{startcolor: '#999999'});
	+-> Effect.Parallel
	
	+-> new Effect.ScrollTo('id_of_element', {offset: -24});
	
	+-> Effect.toggle(element, ['appear' | 'slide' | 'blind'], [options] );
	
	+-> Effect.Appear, Effect.Fade
	+-> Effect.BlindDown, Effect.BlindUp
	+-> Effect.SlideDown, Effect.SlideUp
	
	+-> Effect.Bounce
	+-> Effect.Puff
	+-> Effect.DropOut
	+-> Effect.Shake
	+-> Effect.Highlight
	+-> Effect.SwitchOff
	+-> Effect.Pulsate
	+-> Effect.Squish
	+-> Effect.Fold
	+-> Effect.Grow
	+-> Effect.Shrink
	
	
	$('morph_example').morph('background:#080;color:#fff');
	
	
	new Effect.Morph('error_message',{
	  style:'background:#f00; color:#fff;'+
	  'border: 20px solid #f88; font-size:2em',
	  duration:0.8
	});

// Effects parameters

	+-> duration : Duration of the effect in seconds, given as a float. Defaults to 1.0.
	+-> fps : Target this many frames per second. Default to 25. Can t be higher than 100.
	+-> transition : Sets a function that modifies the current point of the animation, which is between 0 and 1. Following transitions are supplied: Effect.Transitions.sinoidal (default), Effect.Transitions.linear, Effect.Transitions.reverse, Effect.Transitions.wobble and Effect.Transitions.flicker.
	+-> from : Sets the starting point of the transition, a float between 0.0 and 1.0. Defaults to 0.0.
	+-> to : Sets the end point of the transition, a float between 0.0 and 1.0. Defaults to 1.0.
	+-> sync : Sets whether the effect should render new frames automatically (which it does by default). If true, you can render frames manually by calling the render() instance method of an effect. This is used by Effect.Parallel().
	+-> queue : Sets queuing options. When used with a string, can be 'front' or 'end' to queue the effect in the global effects queue at the beginning or end, or a queue parameter object that can have {position:'front/end', scope:'scope', limit:1}. For more info on this, see Effect Queues.
	+-> direction : Sets the direction of the transition. Values can be either 'top-left', 'top-right', 'bottom-left', 'bottom-right' or 'center' (Default). Applicable only on Grow and Shrink effects.
	
// Effects Callback

	+-> beforeStart : Called before the main effects rendering loop is started.
	+-> beforeUpdate : Called on each iteration of the effects rendering loop, before the redraw takes places.
	+-> afterUpdate : Called on each iteration of the effects rendering loop, after the redraw takes places.
	+-> afterFinish : Called after the last redraw of the effect was made.
	
// Effects Callback Variables : afterFinish:function(effect)

	+-> effect.element : The element the effect is applied to.
	+-> effect.options : Holds the options you gave to the effect.
	+-> effect.currentFrame : The number of the last frame rendered.
	+-> effect.startOn, effect.finishOn : The times (in ms) when the effect was started, and when it will be finished.
	+-> effect.effects[] : On an Effect.Parallel effect, there�s an effects[] array containing the individual effects the parallel effect is composed of.
	
// Exemple...

	<a href="javascript:void(0)" onclick="new Effect.Scale(this.parentNode,120);">Click Demo!</a>
	
	Effect.Fade(idValue, { afterFinish:function(effect) { Element.remove(effect.element); }}); // Efface DIV element
	
	function(element) {
		element.onclick = function() {
			var href = element.getAttribute('href'); // href = #footer...
			fx = new Effect.ScrollTo(href.substr(1));
			return false;
		}
	}
	
	new Effect.Opacity('my_element', {
		duration: 2.0, 
		transition: Effect.Transitions.linear, 
		from: 1.0,
		to: 0.5,
		afterFinish:function(effect){
			$(effect.element).style.position = 'relative';
			$(effect.element).style.top = '0px';
			work = true; // R�tabli le clic qd le script a fini de tourner
		}
	});
	
	new Effect.SlideDown('menu', {queue: {position:'end', scope: 'menuxscope', limit:2} }); 
	
	
	$$('img.divImg').each( function(e) {
		var Ename = e.getAttribute('id');
		Width = e.getAttribute('width');
		Height = e.getAttribute('height');
		new Effect.Scale(e, 60, {scaleMode:{originalWidth:Width,originalHeight:Height},scaleFrom:100});
	});


// Combine effect

	new Effect.Parallel(
		[ 
			new Effect.MoveBy(element, 100, 0, { sync: true }), 
			new Effect.Opacity(element, { sync: true, to: 0.0, from: 1.0 } ) 
		],
		{ duration: 0.5, 
		  afterFinish: function(effect) { Element.hide(effect.effects[0].this.parentNode); } 
		}
	);

	var queue = Effect.Queues.get('lightWindowAnimation').each(function(e) {e.cancel();});
	
	
// Builder Aculous

	Builder.node( elementName )
	Builder.node( elementName, attributes )
	Builder.node( elementName, children )
	Builder.node( elementName, attributes, children )
	
		+-> elementName : string The tag name for the element.
		+-> attributes : object Typical attributes are id, className, style, onclick, etc.
		+-> children  array List of other nodes to be appended as children. OR string Text
	
	+-> Attributes Special cases :
	
		+-> class: className.
		+-> for: htmlFor.
	
		
		
	element = Builder.node('p',{className:'error'},'No error has occurred');
	$('divCat').appendChild(element);
	
	element = Builder.node('input',{type:'checkbox',className:'checklist',name:'cbx_',value:'myvalue',checked:'checked'});
	
	element = Builder.node('div',{id:'testId'},[
		Builder.node('div',{className:'controls'},[
			Builder.node('h1','Text Train'),
			"testtext", " - autre"
		]),
	]);
	$('box_galerie').appendChild(element);


// Attributes

	$('element').setAttribute('src','test.gif');


// CLASS

	Element.addClassName
	Element.classNames
	Element.getStyle
	Element.hasClass
	Element.removeClassName

	<style type="text/css">
	.wild { border-bottom: 5px dashed lime; }
	</style>
	<p id="mypara">This is going to be wild!</p>
	<a href="#" onclick="$('mypara').addClassName('wild'); return false;">make it wild!</a>
	<a href="#" onclick="Element.removeClassName('mypara', 'wild'); return false;">Please tame this wild paragraph!</a>
	<a href="#" onclick="alert('Class name is:\n' + Element.classNames('mypara')); return false;">Find out what the below paragraph class is.</a>
	<a href="#" onclick="alert(Element.hasClassName('mypara', 'wild')); return false;">does the below to wild ?</a>


// SCAN DIV INPUT

	var ArrMedia_id = $$('div#galeries div.divgalSel');
	
	for (var i=0; i<ArrMedia_id.length; i++){
		var idValue = ArrMedia_id[i].id;
		medias_url += '&media_id[]='+idValue; //.getAttribute('id'); // media_id // Build URL
		if (action == 'EFFACER')
			Effect.Fade(idValue, { afterFinish:function(effect) { Element.remove(effect.element); }}); // Efface DIV element
	}

// Events
	
	Event.observe(element, name, observer, [useCapture]);
	
		+-> element : an object or the id of the element you want to capture event on
		+-> name : the name of event you want to capture (�load�,�click�,�beforeunload�,...)
		+-> observer : a function to do the job when event is fired
		+-> useCapture : if true handles the event in capture phase else in bubbling phase.
	
	Event.observe('linkName', 'click', function(event) { checkSortable(event); });
	Event.observe(window, 'load', function() { callBack(); });
	
	EVENTS :
	
		* Interface events (blur/focus, contextmenu, load, resize, scroll, unload)
		* Mouse events (click, dblclick, mousedown/up, mouseenter/leave, mousemove, mouseover/out)
		* Form events (change, reset, select, submit)
		* Key events (keydown/press/up)
		* Miscellaneous events (abort, error, subtreemodified)


	function iniCSS3(evt) {
		if (evt) Event.stop(evt);
		//...
	}
	Event.observe(window,'load',iniCSS3,false);
	

// Apply to All elements...

	$$('element.className').each( function(e) { e.visualEffect('highlight',{duration:1.5}) });


// Sortables

	Position.includeScrollOffsets = true; // scroll:window, ?
	
	Sortable.create('box_galerie',{
		tag:'div',
		overlap:'horizontal',
		only:'imageBox',
		constraint:false,
		scroll:window, // 'scroll-container'
		ghosting:true,
		hoverclass:'imageBoxHighlighted',
		onUpdate:update;
		revert:function(element){return ($('shouldrevert2').checked)},
		onStart:function(){$('revertbox2').setStyle({backgroundColor:'#bfb'})},
		onDrag:function(){$('event-info').update('drag!')},
		onEnd:function(){alert('end!')}
		starteffect:function(){
		  new Effect.Highlight('specialbox3',{queue:'end'});
		},
		endeffect:function(){
		  new Effect.Highlight('specialbox3',{queue:'end'});
		}
	});
	
	Sortable.create('galeries', {
		tag:'div',
		overlap:'horizontal',
		constraint:false,
		ghosting:false, /* no update with false.... */
		handle:'move',
		onUpdate:function(element){
			sortableReordered = true;
			if ($('valideReorder').style.display=='none') { Effect.Appear('valideReorder2'); }
		},
		onChange:function(element) $('galeries_serialize').value = Sortable.serialize(element.parentNode,{name:'galerie_id'});
	});
	$('galeries_serialize').value = Sortable.serialize('galeries',{name:'galerie_id'});


// ADD SORTABLES

	element = Builder.node('div',{id:'NewPuzzlePiece',className:'ARTIST',style:'float:left'},[Builder.node('img',{src:'puzzle7.jpg'})]);
	$('puzzle').appendChild(element);
	new Draggable(element);


// REORDER

	Sortable.setSequence ('sortme',Sortable.sequence('sortme').sort());
	
	// FIRE LINK
	var sortableReordered = false;
	// onUpdate: sortableReordered = true; // a mettre dans Sortable.create(
	Event.observe('linkName', 'click', function(event) { checkSortable(event); });
	function checkSortable(event) {
		if (sortableReordered) {
			 Event.stop(event);
			 sortableReordered = false;
		}
	}

	new Ajax.Autocompleter(id_of_text_field, id_of_div_to_populate, url, options);
	
	Options (inherited from Autocompleter.Base)
	Option 	Default value 	Description
	paramName 	the �name� of the element 	Name of the parameter for the string typed by the user on the autocompletion field
	tokens 	[] 	See Autocompleter.Base
	frequency 	0.4
	minChars 	1
	indicator 	null 	When sending the Ajax request Autocompleter shows this element with Element.show. You can use this to e.g. display an animated �please-wait-while-we-are-working� gif. See here for examples. When the request has been completed it will be hidden with Element.hide.
	updateElement 	null 	Hook for a custom function called after the element has been updated (i.e. when the user has selected an entry). This function is called instead of the built-in function that adds the list item text to the input field. The function receives one parameter only, the selected item (the <li> item selected)
	afterUpdateElement 	null 	Hook for a custom function called after the element has been updated (i.e. when the user has selected an entry). This function is called after the built-in function that adds the list item text to the input field (note differeence from above). The function receives two parameters, the autocompletion input field and the selected item (the <li> item selected)
	
	new Ajax.Autocompleter("autocomplete", "autocomplete_choices", "/url/on/server", {paramName: "value", minChars: 2, updateElement: addItemToList, indicator: 'indicator1'});

	Look through your POST environment variable for the current entry in the text-box.

	The server-side will receive the typed string as a parameter with the same name as the name of the element of the autocompletion (name attribute). You can override it setting the option paramName.
	
	The server must return an unordered list.
	For instance this list might be returned after the user typed the letter �y�
	
	
	<ul>
		<li>your mom</li>
		<li>yodel</li>
	</ul>

	If you wish to display additional information in the autocomplete dropdown that you don�t want inserted into the field when you choose an item, surround it in a <span> (could work with others but not tested) with the class of �informal�.
	
	For instance the following shows a list of companies and their addresses, but only the company name will get inserted:

	<ul>
		<li>ACME Inc <span class="informal"> 5012 East 5th Street</span></li>
		<li>Scriptaculous <span class="informal"> 1066 West Redlands Parkway</span></li>
	</ul>
	
	Another way to get aditional information, just send and ID in every list, and redefine afterUpdate Element?
	
	
	<ul>
		<li id="1">your mom</li>
		<li id="2">yodel</li>
	</ul>


// SLIDER /////////////////////////////

	new Control.Slider('id_of_slider_handle','id_of_slider_track', [options]); 


		+-> axis				horizontal 	Sets the direction that the slider will move in. It should either be horizontal or vertical.
		+-> increment			Defines the relationship of value to pixels. Setting this to 1 will mean each movement of 1 pixel equates to 1 value.
		+-> maximum				length of track in pixels adjusted by increment 	The maximum value that the slider will move to. For horizontal this is to the right while vertical it is down.
		+-> minimum				The minimum value that the slider can move to. For horizontal this is to the left while vertical it is up. Note: this also sets the beginning of the slider (zeroes it out).
		+-> range				Use the $R(min,max)
		+-> alignX				This will move the starting point on the x-axis for the handle in relation to the track. It is often used to move the �point� of the handle to where 0 should be. It can also be used to set a different starting point on the track.
		+-> alignY				This will move the starting point on the y-axis for the handle in relation to the track. It is often used to move the �point� of the handle to where 0 should be. It can also be used to set a different starting point on the track.
		+-> sliderValue			Will set the initial slider value. The handle will be set to this value, assuming it is within the minimum and maxium values.
		+-> disabled			This will lock the slider so that it will not move and thus is disabled.
		+-> handleImage			The id of the image that represents the handle. This is used to swap out the image src with disabled image src when the slider is enabled.
		+-> handleDisabled		The id of the image that represents the disabled handle. This is used to change the image src when the slider is disabled.
		+-> values				Accepts an array of integers. If set these will be the only legal values for the slider to be at. Thus you can set specific slider values that the user can move the slider to.
	
	The slider control offers some functions to let javascript update its state:
	
		+-> setValue			Will update the slider�s value and thus move the slider handle to the appropriate position. NOTE: when using setValue, the callback functions (below) are called.
		+-> setDisabled			Will set the slider to the disabled state (disabled = true).
		+-> setEnabled		Will set the slider to the enabled state (disabled = false).
	
	Additionally, the options parameter can take the following callback function:
	
		+-> onSlide 	Called whenever the Slider is moved by dragging. The called function gets the slider value as its parameter.
		+-> onChange 	Called whenever the Slider has finished moving or has had its value changed via the setSlider Value? function. The called function gets the slider value as its parameter.
		
		
		
		/*
		<div id="track1" style="width: 200px; height:18px;"><div id="handle1" style="width: 18px; height: 18px;"><img src="/images/content/blog/scaler_slider.gif"/></div></div>
		<div style="border: 1px solid #ddd; width: 424px; overflow: auto;">
		<div class="scale-image" style="width: 190px; padding: 10px; float: left;">
		<img src="/images/content/blog/scaler_1.jpg" width="100%"/>
		</div>
		<div class="scale-image" style="width: 190px; padding: 10px; float: left;">
		<img src="/images/content/blog/scaler_2.jpg" width="100%"/>
		</div>
		</div> 
		*/
		
		function scaleIt(v) {
		  var scalePhotos = document.getElementsByClassName('scale-image');
		  floorSize = .26;
		  ceilingSize = 1.0;
		  v = floorSize + (v * (ceilingSize - floorSize));
		  for (i=0; i < scalePhotos.length; i++) scalePhotos[i].style.width = (v*190)+'px';
		}

		var demoSlider = new Control.Slider('handle1','track1', {axis:'horizontal', minimum: 0, maximum:200, alignX: 2, increment: 2, sliderValue: 1});
		demoSlider.options.onSlide = function(value){  scaleIt(value); }
		demoSlider.options.onChange = function(value){ scaleIt(value); }


//// AJAX INPLACE SELECT ////////////////////////////////////////////////////////////////////////////////////////

	new Ajax.InPlaceSelect('id', 'url', 'values[]', 'labels[]', { options });
	
	- Example -
	  new Ajax.InPlaceSelect('someId', 'someURL', [1,2], ['first','second'], { paramName: 'asset_type', parameters: "moreinfo=extra info" } );
	
	- Options('default value') -
	  - paramName('selected'): name of the default parameter sent
	  - hoverClassName(null): class added when mouse hovers over the control
	  - hightlightcolor("#FFFF99"): initial color (mouseover)
	  - hightlightendcolor("#FFFFFF"): final color (mouseover)
	  - parameters(null): additional parameters to send with the request
		  (in addition to the data sent by default)



//// AJAX INPLACE EDITOR ////////////////////////////////////////////////////////////////////////////////////////

	new Ajax.InPlaceEditor( element, url, [options]);
	
	
		+-> okButton  	V1.6  	�true�  	If a submit button is shown in edit mode (true,false)
		+-> okText 	V1.5 	�ok� 	The text of the submit button that submits the changed value to the server
		+-> cancelLink 	V1.6 	�true� 	If a cancel link is shown in edit mode (true,false)
		+-> cancelText 	V1.5 	�cancel� 	The text of the link that cancels editing
		+-> savingText 	V1.5 	�Saving�� 	The text shown while the text is sent to the server
		+-> clickToEditText 	V1.6 	�Click to edit� 	The text shown during mouseover the editable text
		+-> formId 	V1.5 	id of the element to edit plus �InPlaceForm� 	The id given to the
		+-> element
		+-> externalControl 	V1.5 	null 	ID of an element that acts as an external control used to enter edit mode. The external control will be hidden when entering edit mode and shown again when leaving edit mode.
		+-> rows 	V1.5 	1 	The row height of the input field (anything greater than 1 uses a multiline textarea for input)
		+-> onComplete 	V1.6 	�function(transport, element) {new Effect.Highlight(element, {startcolor: this.options.highlightcolor});}� 	Code run if update successful with server
		+-> onFailure 	V1.6 	�function(transport) {alert(�Error communicating with the server: � + transport.responseText.stripTags());}� 	Code run if update failed with server
		+-> cols 	V1.5 	none 	The number of columns the text area should span (works for both single line or multi line)
		+-> size 	V1.5 	none 	Synonym for �cols� when using single-line (rows=1) input
		+-> highlightcolor 	? 	Ajax.InPlaceEditor.defaultHighlightColor 	The highlight color
		+-> highlightendcolor 	? 	�#FFFFFF� 	The color which the highlight fades to
		+-> savingClassName 	V1.5 	�inplaceeditor-saving� 	CSS class added to the element while displaying �Saving�� (removed when server responds)
		+-> formClass Name? 	V1.5 	�inplaceeditor-form� 	CSS class used for the in place edit form
		+-> hoverClass Name? 	? 	?
		+-> loadTextURL 	V1.5 	null 	Will cause the text to be loaded from the server (useful if your text is actually textile and formatted on the server)
		+-> loadingText 	V1.5 	�Loading�� 	If the loadText URL option is specified then this text is displayed while the text is being loaded from the server
		+-> callback 	V1.5 	function(form) {Form.serialize(form)} 	A function that will get executed just before the request is sent to the server, should return the parameters to be sent in the URL. Will get two parameters, the entire form and the value of the text control.
		+-> submitOnBlur 	V1.6 	�false� 	This option if true will submit the in_place_edit form when the input tag loses focus.
		+-> ajaxOptions 	V1.5 	{} 	Options specified to all AJAX calls (loading and saving text), these options are passed through to the prototype AJAX classes.
		

	  
	  
		To disable the InPlaceEditor behavior later on, store it in a variable like:
		var editor = new Ajax.InPlaceEditor('product_1',...);
		(... do stuff ..)
		editor.dispose();
		
		This way, you can enable and disable In Place Editing at will.
		You can also arbitrarily force it into edit mode like so:
		editor.enterEditMode('click');

		Add a callback function which is supposed to return the parameters that is sent to the server. Like this:
		new Ajax.InPlaceEditor('id', 'url', { callback: function(form, value) { return 'myparam=' + escape(value) }})
		
		The escape() makes sure values containing special characters like �&� or �=� don�t cause problems. Use encodeURIComponent() instead of escape() to get UTF-8 encoded data. This function can also be used to pass additional parameters, such as what item or field to edit:
		new Ajax.InPlaceEditor('id', 'url', { callback: function(form, value) { return 'item=123&field=description&myparam=' + escape(value) }})

		// CSS
		form.inplaceeditor-form {} /* The form */
		form.inplaceeditor-form input[type="text"] {} /* Input box */
		form.inplaceeditor-form textarea { /* Textarea, if multiple columns */
		form.inplaceeditor-form input[type="submit"] {} /* The submit button */
		form.inplaceeditor-form a {} /* The cancel link */


		<p id="editme2">Click me to edit this nice long text.</p>
		<script type="text/javascript">
		new Ajax.InPlaceEditor('editme2', '/demoajaxreturn.html', {rows:15,cols:40});
		</script>
		
		
		new Ajax.InPlaceEditor('noteDiv', 'update_script.php?totot=tutu', {
			okButton: false,
			cancelLink: false,
			savingText:'Enregistrement...',
			clickToEditText:'Cliquez pour editer',
			loadingText:'Chargement...',
			rows:15,
			cols:40,
			submitOnBlur:true,
			//evalScripts: true,
			ajaxOptions:{ method:'get'},
			callback: function(form, value) {
				return '&noteValue=' + escape(value);
			},
			//onComplete: function(transport, element) {
			//	element.update('noteDiv');
			//	new Effect.Highlight(element, {startcolor: this.options.highlightcolor});
			//}
		});


//////////////////////////////////////////////////////////////////////////////////////////
	// <input id="mots" /> <div id="ac1update"></div>
	function addComa(text,li) { alert(li.id); $('mots').value += ', '; }
	new Ajax.Autocompleter('mots','ac1update','_ajax_get_mots.php',{
		paramName:'mots',
		minChars:1,
		tokens:',',
		fullSearch:true,
		partialSearch:false,
		afterUpdateElement:addComa
	});

///////////////////////////////////////////////////////////////////////////////////////////

	escape() // will not encode: @*/+
	encodeURI() // will not encode: !@#$&*()=:/;?+'
	encodeURIComponent()  // will not encode: !*()'