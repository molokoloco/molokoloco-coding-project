#### UPDATE ! **Sources are now on Github :** https://github.com/molokoloco/FRAMEWORK/tree/master/jquery.plugins ####
#### UPDATE 2 ! **Sources are also on jsFiddle :** http://jsfiddle.net/user/molokoloco/ ####
#### UPDATE 3 ! _But you can found everything here :_ http://www.b2bweb.fr/category/coding-project/ ####


---


<a href='https://github.com/molokoloco/FRAMEWORK/' title='See source code on GITHUB...'><img src='http://www.b2bweb.fr/wall/img/github-ribbon.png' alt='Fork me on GitHub' width='100' align='right' height='100'><b>Au sommaire :</b>

<br>
<br>
<hr />

<h1>Center element into an other</h1>

<h3>USE CASE</h3>

<pre><code>$('#mainDiv').center();<br>
$(window).bind('resize', function() {<br>
    $('#mainDiv').center({transition:300});<br>
});<br>
</code></pre>

<h3>JQUERY PLUGIN</h3>

Source here : <a href='http://plugins.jquery.com/project/autocenter'>http://plugins.jquery.com/project/autocenter</a>

<pre><code>(function($){<br>
     $.fn.extend({<br>
          center: function (options) {<br>
               var options =  $.extend({ // Default values<br>
                    inside:window, // element, center into window<br>
                    transition: 0, // millisecond, transition time<br>
                    minX:0, // pixel, minimum left element value<br>
                    minY:0, // pixel, minimum top element value<br>
                    withScrolling:true, // booleen, take care of the scrollbar (scrollTop)<br>
                    vertical:true, // booleen, center vertical<br>
                    horizontal:true // booleen, center horizontal<br>
               }, options);<br>
               return this.each(function() {<br>
                    var props = {position:'absolute'};<br>
                    if (options.vertical) {<br>
                         var top = ($(options.inside).height() - $(this).outerHeight()) / 2;<br>
                         if (options.withScrolling) top += $(options.inside).scrollTop() || 0;<br>
                         top = (top &gt; options.minY ? top : options.minY);<br>
                         $.extend(props, {top: top+'px'});<br>
                    }<br>
                    if (options.horizontal) {<br>
                          var left = ($(options.inside).width() - $(this).outerWidth()) / 2;<br>
                          if (options.withScrolling) left += $(options.inside).scrollLeft() || 0;<br>
                          left = (left &gt; options.minX ? left : options.minX);<br>
                          $.extend(props, {left: left+'px'});<br>
                    }<br>
                    if (options.transition &gt; 0) $(this).animate(props, options.transition);<br>
                    else $(this).css(props);<br>
                    return $(this);<br>
               });<br>
          }<br>
     });<br>
})(jQuery);<br>
<br>
$('#myDiv').center();<br>
<br>
</code></pre>

<h3>SHORT VERSION</h3>

<pre><code>(function($){<br>
     $.fn.extend({<br>
          center: function () {<br>
		return this.each(function() {<br>
			var top = ($(window).height() - $(this).outerHeight()) / 2;<br>
			var left = ($(window).width() - $(this).outerWidth()) / 2;<br>
			$(this).css({position:'absolute', margin:0, top: (top &gt; 0 ? top : 0)+'px', left: (left &gt; 0 ? left : 0)+'px'});<br>
		});<br>
	}<br>
     });<br>
})(jQuery);<br>
<br>
$('#myDiv').center();<br>
</code></pre>


<h3>VERY SHORT VERSION</h3>

<pre><code>$('#myDiv').css({top:'50%',left:'50%',margin:'-'+($('#myDiv').height() / 2)+'px 0 0 -'+($('#myDiv').width() / 2)+'px'});<br>
</code></pre>

<hr />

<h1>Add Or Switch Stylesheet</h1>

Ressources :<br>
<ul><li>Source here : <a href='http://home.b2bweb.fr/js/jquery.style.js'>http://home.b2bweb.fr/js/jquery.style.js</a>
</li><li>Official : <a href='http://plugins.jquery.com/project/AddOrSwitchStylesheet'>http://plugins.jquery.com/project/AddOrSwitchStylesheet</a>
</li><li>Use cookie manager from : <a href='http://plugins.jquery.com/project/cookie'>http://plugins.jquery.com/project/cookie</a></li></ul>

Plugin who load (at the first demand) and switch stylesheet, with cookie :<br>
<ul><li>Manage links to change style<br>
</li><li>Add stylesheet to head if not exist<br>
</li><li>If already exist, switch style with the disabled attribute<br>
</li><li>Prevent changing styles who are not related to theme : No modification on styles without id attribute<br>
</li><li>Prevent changing other styles who are not related to theme<br>
</li><li>Stock and autoload user style preference, with a cookie</li></ul>

<h3>HTML</h3>

<pre><code>&lt;head&gt;<br>
     &lt;link rel="stylesheet" type="text/css" media="all" href="css/styles.css" id="themeDefault"/&gt;<br>
     ...<br>
&lt;/head&gt;<br>
&lt;body&gt;<br>
&lt;div&gt;<br>
     &lt;ul&gt;<br>
          &lt;li&gt;&lt;a href="javascript:void(0);" rel="css/styles.css" class="css"&gt;Original&lt;/a&gt;&lt;/li&gt;<br>
          &lt;li&gt;&lt;a href="javascript:void(0);" rel="css/style_light.css" class="css"&gt;Blanc&lt;/a&gt;&lt;/li&gt;<br>
          &lt;li&gt;&lt;a href="javascript:void(0);" rel="css/style_dark.css" class="css"&gt;Sombre&lt;/a&gt;&lt;/li&gt;<br>
     &lt;/ul&gt;<br>
&lt;/div&gt;<br>
&lt;/body&gt;<br>
</code></pre>

<h3>JQUERY PLUGIN</h3>

<pre><code>(function($){<br>
     $.fn.extend({<br>
          styleDisable: function (disabled) {<br>
               setTimeout(function () {<br>
                    $(disabled).each(function () {<br>
                         $(this).attr('disabled', 'disabled');<br>
                    });<br>
               }, 250);<br>
          },<br>
          styleLoad: function (stylePath) {<br>
               $('head').append('&lt;link rel="stylesheet" type="text/css" href="' + stylePath + '" id="theme' + Math.random() + '"/&gt;');<br>
          },<br>
          styleSwitch: function (stylePath) {<br>
               var exist = false;<br>
               var disabled = [];<br>
               $('link[@rel*=style][id]').each(function () {<br>
                    if (stylePath == $(this).attr('href')) {<br>
                         $(this).removeAttr('disabled');<br>
                         exist = true;<br>
                    }<br>
                    else disabled.push(this);<br>
               });<br>
               if (exist == false) $.fn.styleLoad(stylePath);<br>
               $.fn.styleDisable(disabled);<br>
               $.cookie('css', stylePath, {<br>
                    expires: 365,<br>
                    path: '/'<br>
               });<br>
          },<br>
          styleInit: function () {<br>
               if ($.cookie('css')) {<br>
                    var isSet = false;<br>
                    $('link[rel*=style][id]').each(function () {<br>
                         if ($.cookie('css') == $(this).attr('href')) isSet = true;<br>
                    });<br>
                    if (isSet == false) $.fn.styleSwitch($.cookie('css'));<br>
               }<br>
               return $(this).click(function (event) {<br>
                    event.preventDefault();<br>
                    $.fn.styleSwitch($(this).attr('rel'));<br>
                    $(this).blur();<br>
               });<br>
          }<br>
     });<br>
})(jQuery);<br>
<br>
// $('a.css').styleInit(); // That it's :)<br>
<br>
</code></pre>

<hr />

<h1>Select a text range (input/textarea)</h1>

<h3>USE CASE</h3>

<pre><code>$('#q').selectRange(0, 10);<br>
$('#q').selectRange(searchVal.indexOf('{'), (searchVal.indexOf('}')+1));<br>
</code></pre>

<h3>JQUERY PLUGIN</h3>

Source here : <a href='http://plugins.jquery.com/project/selectRange'>http://plugins.jquery.com/project/selectRange</a>

<pre><code>$.fn.selectRange = function(start, end) {<br>
    var e = document.getElementById($(this).attr('id')); // I don't know why... but $(this) don't want to work today :-/<br>
    if (!e) return;<br>
    else if (e.setSelectionRange) { e.focus(); e.setSelectionRange(start, end); } /* WebKit */ <br>
    else if (e.createTextRange) { var range = e.createTextRange(); range.collapse(true); range.moveEnd('character', end); range.moveStart('character', start); range.select(); } /* IE */<br>
    else if (e.selectionStart) { e.selectionStart = start; e.selectionEnd = end; }<br>
};<br>
</code></pre>

<hr />

<h1>Get browser CSS style name properties (-Moz -Webkit, ... CSS support)</h1>

A function to get browser specific CSS style name properties...<br>
eg. "MozBorderRadius" or "WebkitTransform" ...<br>
<br>
<h3>USE CASE</h3>

<pre><code>var cssTransform = cssPrefix('transform');<br>
if (cssTransform ) {<br>
    var cssProp = {};<br>
    cssProp['border'] = '1px solid rgba(0, 0, 0, .5)';<br>
    cssProp[cssTransform] = 'rotate(20deg)';<br>
    cssProp[cssPrefix('border-radius')] = '5px'; // eg. "MozBorderRadius"<br>
    $('#myDiv').css(cssProp);<br>
}<br>
</code></pre>

<h3>JQUERY "ADD-ON"...</h3>

Playground here : <a href='http://jsfiddle.net/molokoloco/f6Z3D/'>http://jsfiddle.net/molokoloco/f6Z3D/</a>

<pre><code>String.prototype.camelize = function() {<br>
    var tmp = '';<br>
    var words = this.split('-');<br>
    for (var i in words) tmp += words[i].toLowerCase().charAt(0).toUpperCase() + words[i].slice(1);<br>
    return tmp;<br>
};<br>
<br>
var cssPrefixString = null;<br>
var cssPrefix = function(propertie) {<br>
    if (cssPrefixString !== null) return cssPrefixString + propertie.camelize();<br>
    var e = document.createElement('div');<br>
    var prefixes = ['', 'Moz', 'Webkit', 'O', 'ms', 'Khtml']; // Various supports...<br>
    for (var i in prefixes) {<br>
        if ((i == 0 &amp;&amp; typeof e.style[propertie] != 'undefined') || typeof e.style[prefixes[i] + propertie.camelize()] != 'undefined') {<br>
            cssPrefixString = prefixes[i];<br>
            return prefixes[i] + propertie.camelize();<br>
        }<br>
    }<br>
    return false;<br>
};<br>
</code></pre>

<hr />

<h1>Disabling element selection</h1>

<h3>USE CASE</h3>

<pre><code>$('*').disableTextSelect();<br>
</code></pre>

<h3>JQUERY PLUGIN</h3>

<pre><code><br>
$.fn.disableTextSelect = function () {<br>
	return this.each(function () {<br>
		$(this).css({'-webkit-user-select':'none', '-moz-user-select':'none','user-select':'none'});<br>
	})<br>
};<br>
$.fn.enableTextSelect = function () {<br>
	return this.each(function () {<br>
		$(this).css({'-webkit-user-select':'', '-moz-user-select':'','user-select':''});<br>
	})<br>
};<br>
<br>
</code></pre>

<h1>Others</h1>

<pre><code>$.fn.extend({<br>
<br>
	selectRange: function(start, end) { // $('#myInput').selectRange(searchVal.indexOf('{'), (searchVal.indexOf('}')+1))<br>
		var e = document.getElementById($(this).attr('id')); // I don't know why... but $(this) don't want to work today :-/<br>
		if (!e) return;<br>
		else if (e.setSelectionRange) { e.focus(); e.setSelectionRange(start, end); } /* WebKit */ <br>
		else if (e.createTextRange) { var range = e.createTextRange(); range.collapse(true); range.moveEnd('character', end); range.moveStart('character', start); range.select(); } /* IE */<br>
		else if (e.selectionStart) { e.selectionStart = start; e.selectionEnd = end; }<br>
	},<br>
	<br>
	center: function (parent) { // I have added the (expanded) source here : http://plugins.jquery.com/project/autocenter<br>
		return this.each(function() {		  <br>
			var top = ( (parent ? $(this).closest(parent).height() : $(window).height()) - $(this).outerHeight()) / 2;<br>
			var left = ( (parent ? $(this).closest(parent).width() : $(window).width()) - $(this).outerWidth()) / 2;<br>
			$(this).css({position:'absolute', margin:0, top: (top &gt; 0 ? top : 0)+'px', left: (left &gt; 0 ? left : 0)+'px'});<br>
		 });<br>
	},<br>
<br>
	styleSwitch: function (stylePath) { // I have added the (expanded) source here : http://plugins.jquery.com/project/AddOrSwitchStylesheet<br>
		var exist = false, disabled = [];<br>
		$('link[@rel*=style][id]').each(function () {<br>
			if (stylePath == $(this).attr('href')) { $(this).removeAttr('disabled'); exist = true; }<br>
			else disabled.push(this);<br>
		});<br>
		if (exist === false) $('head').append('&lt;link rel="stylesheet" type="text/css" href="'+stylePath+'" id="theme'+Math.random()+'"/&gt;');<br>
		setTimeout(function () { $(disabled).each(function () { $(this).attr('disabled', 'disabled'); }); }, 900);<br>
		if ($.cookie) $.cookie('css', stylePath, {expires: 365, path: '/'});<br>
	},<br>
<br>
	styleInit: function () {<br>
		if ($.cookie &amp;&amp; $.cookie('css')) {<br>
			var isSet = false;<br>
			$('link[rel*=style][id]').each(function () { if ($.cookie('css') == $(this).attr('href')) isSet = true; });<br>
			if (isSet === false) $.fn.styleSwitch($.cookie('css'));<br>
		}<br>
		return $(this).click(function (event) {<br>
			event.preventDefault();<br>
			$.fn.styleSwitch($(this).attr('rel'));<br>
			$(this).blur();<br>
		});<br>
	},<br>
<br>
	myToggle: function() {<br>
		return this.each(function(i){<br>
			var src = $(this).attr('src');<br>
			if (/(_on)/.test(src)) $(this).attr({src: src.split('_on.png')[0]+'_off.png'}).toggleClass('selected', true);<br>
			else $(this).attr({src: src.split('_off.png')[0]+'_on.png'}).toggleClass('selected', false);<br>
			//console.log($(this).data('desc'));<br>
		});<br>
	},<br>
<br>
	shuffle: function() {<br>
		var allElems = this.get(),<br>
			getRandom = function(max) {<br>
				return Math.floor(Math.random() * max);<br>
			},<br>
			shuffled = $.map(allElems, function(){<br>
				var random = getRandom(allElems.length),<br>
					randEl = $(allElems[random]).clone(true)[0];<br>
				allElems.splice(random, 1);<br>
				return randEl;<br>
		   });<br>
		this.each(function(i){<br>
			$(this).replaceWith($(shuffled[i]));<br>
		});<br>
		return $(shuffled);<br>
	},<br>
<br>
	smartresize: function(fn) {<br>
		return fn ? this.bind('smartresize', fn ) : this.trigger('smartresize', ['execAsap']);<br>
	},<br>
// Cross browsers CSS rotate<br>
        rotate: function(val) { // $('div').rotate('90deg');<br>
            return this.each(function() {<br>
                var rotate = 'rotate('+val+')';<br>
                return $(this).css({'-moz-transform':rotate, '-webkit-transform':rotate, '-ms-transform':rotate, '-o-transform':rotate, transform:rotate});<br>
            });<br>
        },<br>
        <br>
        // Distribute elements clockwise inside a box<br>
        circalise: function(options) { // $('div').circalise({targets:'div.unit'});<br>
            options = $.extend({<br>
                targets          :'&gt; *', // childs elements to distribute inside this box<br>
                rotateTargets    :false,<br>
                startAngle       :270, // 270deg, start at top center (like a clock)<br>
                xRadius          :null, // default radius to the radius of the box, minus target width<br>
                yRadius          :null<br>
            }, options || {});<br>
            <br>
            return this.each(function() {  <br>
                var $this = $(this),<br>
                    thisW = parseInt($this.innerWidth(), 10),<br>
                    thisH = parseInt($this.innerHeight(), 10),<br>
                    $targets = $this.find(options.targets),<br>
                    increase = (Math.PI * 2) / $targets.length, // Rad cheeseCake  <br>
                    angle = Math.PI * (options.startAngle / 180); // convert from DEG to RAD<br>
                $targets.each(function() {<br>
                    var $target = $(this),<br>
                        xCenter = (thisW - parseInt($target.outerWidth(), 10)) / 2,<br>
                        yCenter = (thisH - parseInt($target.outerHeight(), 10)) / 2,<br>
                        xRadius = (options.xRadius || options.xRadius === 0 ? options.xRadius : xCenter),<br>
                        yRadius = (options.yRadius || options.yRadius === 0 ? options.yRadius : yCenter),<br>
                        params = {<br>
                            left: xRadius * Math.cos(angle) + xCenter,<br>
                            top: yRadius * Math.sin(angle) + yCenter<br>
                        };<br>
                    $target.css(params);<br>
                    if (options.rotateTargets)<br>
                        $target.rotate((Math.atan2(params.top-yCenter, params.left-xCenter)+(Math.PI/2))+'rad'); // (Math.PI/2) == 90deg in rad : rotate to keep tangent<br>
                    angle += increase;<br>
                });<br>
                return $this;<br>
            });<br>
        }<br>
});<br>
<br>
<br>
<br>
$.getRand = function(miin, maax) {<br>
	return parseInt(miin + (Math.random() * (maax - miin)), 10);<br>
};<br>
<br>
// Convert % to (int)px<br>
$.getSize = function(size, ratioWidth) {<br>
	if (size &amp;&amp; /\%/.test(size))<br>
		size = (parseInt(size, 10) / 100) * ratioWidth;<br>
	return parseInt(size, 10);<br>
};<br>
<br>
// Inspired by my old work here http://goo.gl/hL3om<br>
$.getBrowser = (function() { // Closure for putting result in cache<br>
	var userAgentStr = navigator.userAgent.toLowerCase();<br>
	var browsers = { // Various CSS prefix for browsers...<br>
		firefox     :'Moz',<br>
		applewebkit :'Webkit',<br>
		webkit      :'Webkit',<br>
		opera       :'O',<br>
		msie        :'ms', // lower<br>
		Konqueror   :'Khtml'<br>
	};<br>
	for (var prefix in browsers)<br>
		if (userAgentStr.indexOf(prefix) !== -1)<br>
			return browsers[prefix];<br>
	return false;<br>
})();<br>
<br>
// $.cssPrefix('Transform') return 'MozTransform' or 'msTransform' or ...<br>
// See http://jsfiddle.net/molokoloco/f6Z3D/<br>
$.cssPrefixString = {};<br>
$.cssPrefix = function(propertie) {<br>
	if ($.cssPrefixString[propertie] || $.cssPrefixString[propertie] === '') return $.cssPrefixString[propertie] + propertie;<br>
	var e = document.createElement('div');<br>
	var prefixes = ['', 'Moz', 'Webkit', 'O', 'ms', 'Khtml']; // Various browsers...<br>
	for (var i in prefixes) {<br>
		if (typeof e.style[prefixes[i] + propertie] !== 'undefined') {<br>
			$.cssPrefixString[propertie] = prefixes[i];<br>
			return prefixes[i] + propertie;<br>
		}<br>
	}<br>
	return false;<br>
};<br>
<br>
// Fix and apply styles on element with correct browsers prefix<br>
// $(e).crossCss({borderRadius:'10px'}) &gt;&gt;&gt; $(e).css({WebkitBorderRadius:'10px'})<br>
$.fn.crossCss = function(css) {<br>
	return this.each(function() { // I've implemented only the one i need, do yours !<br>
		var $this = $(this);<br>
		if (typeof css != 'object') return $this;<br>
		if (css.transition)<br>
			css[$.cssPrefix('Transition')]      = css.transition; // ANIM<br>
		if (css.borderRadius || css.borderRadius === 0)<br>
			css[$.cssPrefix('borderRadius')]    = css.borderRadius;<br>
		if (css.borderImage)<br>
			css[$.cssPrefix('borderImage')]     = css.borderImage;<br>
		if (css.maskImage)<br>
			css[$.cssPrefix('maskImage')]       = css.maskImage;<br>
		if (css.transform)<br>
			css[$.cssPrefix('Transform')]       = css.transform;<br>
		if (css.boxShadow)<br>
			css[$.cssPrefix('boxShadow')]       = css.boxShadow;<br>
		return $this.css(css);<br>
	 });<br>
};<br>
<br>
</code></pre>

<h2>LighTest TPL</h2>

<ul><li><a href='http://jsfiddle.net/molokoloco/w8xSx/'>http://jsfiddle.net/molokoloco/w8xSx/</a> ;)<br>
</li><li><a href='http://stackoverflow.com/questions/170168/jquery-templating-engines/8057706#8057706'>http://stackoverflow.com/questions/170168/jquery-templating-engines/8057706#8057706</a></li></ul>

<pre><code>Only to be the foolish ^^<br>
<br>
    // LighTest TPL<br>
    $.tpl = function(tpl, val) {<br>
        for (var p in val)<br>
            tpl = tpl.replace(new RegExp('({'+p+'})', 'g'), val[p] || '');<br>
        return tpl;<br>
    };<br>
    // Routine...<br>
    var dataObj = [{id:1, title:'toto'}, {id:2, title:'tutu'}],<br>
        tplHtml = '&lt;div&gt;N°{id} - {title}&lt;/div&gt;',<br>
        newHtml    = '';<br>
    $.each(dataObj, function(i, val) {<br>
         newHtml += $.tpl(tplHtml, val);<br>
    });<br>
    var $newHtml = $(newHtml).appendTo('body');<br>
</code></pre>