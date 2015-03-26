_Library edited from 2005 to 2007..._ Framework here : [trunk/SITE\_01\_SRC/js/proto/optional/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fjs%2Fproto%2Foptional)

# JAVASCRIPT window.document extended #

### getScroll / getViewport / getWindow / getMaxScroll / getScreen / ... ###

```

/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/
/*

Functions :

	client.getPage()  				: return ( scrollX, scrollY, viewW, viewH, pageW, pageY )
	client.getScroll()	 			: return ( left, top )
	client.getViewport() 				: return ( width, height )
	client.getWindow() 				: return ( width, height )
	client.getMaxScroll() 				: return ( width, height )
	client.getScreen() 				: return ( width, height )
	
	client.scrollLeft()	 			: return ( left )
	client.scrollTop()	 			: return ( top )
	client.viewportWidth() 				: return ( width)
	client.viewportHeight() 			: return ( height )
	client.pageMaxScrollWidth() 			: return ( width )
	client.pageMaxScrollHeight() 			: return ( height )
	client.winWidth() 				: return ( width )
	client.winHeight() 				: return ( height )
	client.screenWidth() 				: return ( width )
	client.screenHeight() 				: return ( height )

	client.browser() 				: return ( msieOld | msie 6 | msie 7 | opera | safari | firefox | camino | konqueror | mozilla )
	client.isIe()					: return true | false
	client.isIe7()					: return true | false
	client.isMac()					: return true | false

	client.frameResize(element) 			: onload="resizeIframe('popIframe');" 
	client.windowResize(Wwide,Whigh)		: Resize window and center
	
*/

if (typeof db == 'undefined') throw("client.js requires tools.js");

/* ------------------------- WINDOW PAGE SIZE SCROLL SELF RESIZE WINDOW BROWSER ------------------------------ */
var browser = '';
var client = { // Testé FF/IE
	
	getPage: function() {
		var scrollArr = this.getScroll();
		var viewArr = this.getViewport();
		var pageArr = this.getMaxScroll();
		var pageWidth = (pageArr.width > viewArr.width ? pageArr.width : viewArr.width);
		var pageHeight = (pageArr.height > viewArr.height ? pageArr.height : viewArr.height);
		if (this.browser() == 'firefox' && viewArr.height < pageArr.height) pageWidth -= 17; // Scrollbar
		return {
			scrollX: 	scrollArr.left,
			scrollY: 	scrollArr.top,
			viewW: 		viewArr.width,
			viewH: 		viewArr.height,
			pageW: 		pageWidth,
			pageH: 		pageHeight
		};
	},
	
	getScroll: function() {
		return { left: parseInt(this.scrollLeft()), top: parseInt(this.scrollTop()) };
	},
	
	getViewport: function() {
		return { width: parseInt(this.viewportWidth()), height: parseInt(this.viewportHeight()) };
	},
	
	getWindow: function() {
		return { width: parseInt(this.winWidth()), height: parseInt(this.winHeight()) };
	},
	
	getMaxScroll: function() {
		return { width: parseInt(this.pageMaxScrollWidth()), height: parseInt(this.pageMaxScrollHeight()) };
	},
	
	getScreen: function() {
		return { width: parseInt(this.screenWidth()), height: parseInt(this.screenHeight()) };
	},

	scrollLeft: function() {
		var xScroll = 0;
		if (self.pageXOffset) xScroll = self.pageXOffset;
		else if (document.documentElement && document.documentElement.scrollLeft) xScroll = document.documentElement.scrollLeft;
		else if (document.body) xScroll = document.body.scrollLeft;
		return xScroll;
	},
	
	scrollTop: function() {
		var yScroll = 0;
		if (self.pageYOffset) yScroll = self.pageYOffset;
		else if (document.documentElement && document.documentElement.scrollTop) yScroll = document.documentElement.scrollTop;
		else if (document.body) yScroll = document.body.scrollTop;
		return yScroll;
	},
	
	viewportWidth: function() {
		var wView = 720;
		if (self.innerWidth) wView = self.innerWidth;
		else if (document.documentElement && document.documentElement.clientWidth) wView = document.documentElement.clientWidth;
		else if (document.body) wView = document.body.clientWidth;
		return wView;
	},
	
	viewportHeight: function() {
		var hView = 576;
		if (self.innerHeight) hView = self.innerHeight;
		else if (document.documentElement && document.documentElement.clientHeight) hView = document.documentElement.clientHeight;
		else if (document.body) hView = document.body.clientHeight;
		return hView;
	},

	pageMaxScrollWidth: function() {
		var wMaxScroll = 720;
		if (window.innerWidth && window.scrollMaxX) wMaxScroll = window.innerWidth + window.scrollMaxX;
		else if (document.body.scrollWidth > document.body.offsetWidth) wMaxScroll = document.body.scrollWidth;
		else wMaxScroll = document.body.offsetWidth;
		return wMaxScroll;
	},
	
	pageMaxScrollHeight: function() {
		var hMaxScroll = 576;
		if (window.innerHeight && window.scrollMaxY) hMaxScroll = window.innerHeight + window.scrollMaxY;
		else if (document.body.scrollHeight > document.body.offsetHeight) hMaxScroll = document.body.scrollHeight;
		else hMaxScroll = document.body.offsetHeight;
		return hMaxScroll;
	},
	
	winWidth: function() {
		var wWin = 720;
		if (self.outerWidth) wWin = self.outerWidth;
		else if (document.documentElement && document.documentElement.offsetWidth) wWin = document.documentElement.offsetWidth;
		else if (document.body) wWin = document.body.offsetWidth;
		return wWin;
	},
	
	winHeight: function() {
		var hWin = 720;
		if (self.outerHeight) hWin = self.outerHeight;
		else if (document.documentElement && document.documentElement.offsetHeight) hWin = document.documentElement.offsetHeight;
		else if (document.body) hWin = document.body.offsetHeight;
		return hWin;
	},
	
	screenWidth: function() {
		return window.screen.availWidth;
	},
	
	screenHeight: function() {
		return window.screen.availHeight;
	},

	browser: function() {
		if (isSet(browser)) return browser;
		var userAgentStr = navigator.userAgent.toLowerCase();
		var browsers = new Array('opera','safari','firefox','gecko','camino','konqueror','applewebkit','msie 7','msie 6','msie 5','msie 4','msie 3','mozilla');
		for (var index = 0, len = browsers.length; index < len; ++index) {
			if (userAgentStr.indexOf(browsers[index])!=-1) {
				browser = browsers[index];
				break;
			}
		}
		if (browser == 'applewebkit') browser = 'safari';
		if (browser == 'gecko') browser = 'firefox';
		if (browser == 'msie 3' || browser == 'msie 4' || browser == 'msie 5') browser = 'msieOld';
		return browser; // return ( msieOld | msie 6 | msie 7 | opera | safari | firefox | camino | konqueror | mozilla )
	},
	
	isIe: function() {
		var browser = this.browser();
		if (browser == 'msieOld' || browser == 'msie 6' || browser == 'msie 7') return true;
		else return false;
	},
	
	isIe7: function() {
		var browser = this.browser();
		if (browser == 'msie 7') return true;
		else return false;
	},
	
	isMac: function() {
		var userAgentStr = navigator.userAgent.toLowerCase();
		if (userAgentStr.indexOf('mac') != -1) return true;
		else return false;
	},
	
	frameResize: function(element) { // onload="client.frameResize(this);"
		if (!isId(element)) {
			alert('Can\'t find iframe : '+element);
			return false;
		}
		var currentfr = $(element);
		var yScroll = 400; // par defaut si echec fonction
		try {
			if (currentfr.contentDocument && currentfr.contentDocument.body.scrollHeight) yScroll = currentfr.contentDocument.body.offsetHeight + 30;
			else if (currentfr.contentDocument && currentfr.Document.body.scrollHeight) yScroll = currentfr.document.body.scrollHeight + 30;
			else if(window.frames[element]) yScroll = window.frames[element].document.offsetHeight + 30;
			else yScroll = currentfr.offsetHeight + 30;
		}
		catch(e) {return;}
		currentfr.height = yScroll+'px';
		currentfr.style.display = 'block';
	},
	
	windowResize: function(Wwide,Whigh) {
		if (Wwide < 60) Wwide = 1024;
		if (Whigh < 60) Whigh = 768;
		if (this.isIe) Whigh = Whigh + 150; // ToolBar Explorer ?
		var wide = this.screenWidth();
		var high = this.screenHeight();
		var left = 0;
		var top = 0;
		if (wide > Wwide) left = (wide-Wwide)/2;
		else Wwide = wide;
		if (high > Whigh) top = (high-Whigh)/2;
		else Whigh = high; // Max Size
		window.moveTo(left,top);
		window.resizeTo(Wwide,Whigh);
	}
};

// Old style
var DOM = (document.getElementById ? true : false); // FF / IE / NS...
var IE = client.isIe;
var NS = (document.layers ? true : false);

```
<br />
# JAVASCRIPT window.open() extended #

### Auto sized and centered in the screen ###

```

// ------------------------- POPUP URL ---------------------------------- //
/*
 * Exemple :
 * <a href="pop_media.php" onclick="return windowOpen('pop_media.php',{width:240, height:180});">Test</a>
 * Properties : 
 * width, height, left, top, name, scrollbars, menubar, locationbar, resizable, fullscreen
 */

var windowOpen = function(url) {  
	var options = Object.extend({url:url}, arguments[1] || {});
	var args = '';
	var wide = client.screenWidth();
	var high = client.screenHeight();
	if (options.fullscreen) args += "width="+wide+",height="+high+",screenx=0,screeny=0,left=0,top=0,scrollbars=0,statusbar=0,menubar=0,location=0,resizable=0,";
	else {
		options.x = 0;  
		options.y = 0;  
		if (options.width) {
			options.width = parseInt(options.width);
			if (wide > options.width) options.x = Math.floor((wide - options.width) / 2);
			else options.width = wide;
			args += "width="+options.width+",";  
		}  
		if (options.height) {
			options.height = parseInt(options.height);
			if (high > options.height) options.y = Math.floor((high - options.height) / 2);
			else options.height = high;
			args += "height="+options.height+",";   
		}
		args += "screenx=" + options.x + ",";  
		args += "screeny=" + options.y + ",";  
		args += "left=" + options.x + ",";  
		args += "top=" + options.y + ",";  
		
		args += 'scrollbars='+( !options.scrollbars ? '0' : '1' )+',';
		args += 'statusbar='+( !options.statusbar ? '0' : '1' )+',';
		args += 'menubar='+( !options.menubar ? '0' : '1' )+',';
		args += 'locationbar='+( !options.locationbar ? '0' : '1' )+',';
		args += 'resizable='+( !options.resizable ? '0' : '1' )+',';
	}  
	
	if (!options.name) options.name = '_blank';
	var win = window.open(options.url, options.name, args);
	if (!win) {
		alert('Votre navigateur interdit les popups : réglez vos préférences');
		return true; // Open HREF
	}
	else {
		win.focus();
		return false;
	}
};

// Alias... <a href="pop_media.php" onclick="return pop('pop_media.php','pimpMyPop','260','120');">

var pop = function(url, name, wide, high) {
	return windowOpen(url, {name:name, width:wide, height:high, scrollbars:1, statusbar:1, resizable:1});
};

```