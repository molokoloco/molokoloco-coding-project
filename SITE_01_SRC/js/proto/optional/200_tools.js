/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
/////////////////////////////////////////////////////////////////////////////////////////////////////*/


// ------------------------- REQUIRE :) ---------------------------------- //
if (typeof Element == 'undefined') throw("tools.js requires prototype.js library");

var loadingImagePath = 'images/common/loading.gif';

// ------------------------- DEBUG SOMETHING ---------------------------------- //
var db = function(something) {
	if (arguments.length > 1) {
		for (var i=0, length=arguments.length; i<length; i++ ) db(arguments[i]);
		return;
	}
	var info = '';
	//if (db.caller != null) info += '[call by '+db.caller+']\n';
	//else info += '[direct call]\n';
	//if (typeof something == 'array' || typeof something == 'object') {
		//info += ' | CONSTRUCTOR : ['+something.constructor+']';
		//info += ' | INSPECT : ['+Object.inspect(something)+']';
	//}
    if (typeof something == 'string' || typeof something == 'number') info += '\t'+something.valueOf();
	else if (typeof something == 'boolean') info += '\t'+( something ? 'true' : 'false');
    //else if (typeof something == 'object') return vd(something);
    else {
        for (var key in something) {
            if (typeof something[key] != 'function') // (bad prototype noise)
                info += '\t'+key+' <'+typeof something[key]+'> '+something[key]+'\n';
        }
    }
	if (('console' in window) && ('firebug' in console)) {
		console.info('db('+typeof something+')');
		console.log(info);
	}
    else {
		info = 'db('+typeof something+') :\n'+info;
		alert(info);
		//var dbWin = window.open('javascript:void(0)','','resizabe=1,scrollbars=1,width=400,height=300,left=100,top=100');
		//with (dbWin.document) { writeln('<xmp>'+info+'</xmp>'); close(); }
	}
};

var dbNode = function(element) {
	if (('console' in window) && ('firebug' in console)) {
		console.info('dbNode('+element+')');
		console.dirxml($(element));
	}
}

/* ------------------------- DEBUG OBJET ---------------------------------- */
var vd = function(obj, parent) {
	if (typeof obj != 'object') return db(obj);
	for (var attr in obj) {
		if (parent) console.log(parent + "+" + attr + "\n" + obj[attr]);
		else console.log(attr + "\n" + obj[attr]);
		if (typeof obj[attr] == 'object') {
			if (parent) vd(obj[attr], parent + "+" + attr);
			else vd(obj[attr], attr);
		}
	}
}

/* ---------------- AFFICHE TOUTES LES PROPRIETES D'UN OBJET ------------- */
var showKey = function(obj) {
	var props = [];
    for (var prop in obj) props.push(prop);
    db(props.join(', '));
};

// ------------------------- STOP SCRIPT ---------------------------------- //
var die = function(mess) {
    throw(( mess ? mess : "JS says that you killing him softly : Oh my god moonWalker is down..."));
};

// ------------------------- IS SET ? ---------------------------------- //
var isSet = function(myVar) {
	if (typeof(myVar) == 'undefined' || myVar === '' || myVar === null) return false;
	else return true;
};
// ------------------------- ID ELEMENT EXIST ? ---------------------------------- //
var isId = function(element) {
	if (!isSet(element)) return false;
	try { 
		if ($(element)) return true;
		else return false;
	}
   	catch(e) { return false; }
};

// ------------------------- Type of ---------------------------------- //
var isWhat = function(myVar) {
	if (!isSet(myVar)) return '';
	else return typeof myVar; // number | string | object | boolean | function
};

// ------------------------- isFrame ---------------------------------- //
var isFrame = function() {
	return ( window.self == window.parent ? false : true ); // Checks that page is in iframe
};

// ------------------------- Execute function ---------------------------------- //
var exec = function(func) {
	if (isSet(func) && typeof(func) == 'string') func = eval(func);
	else if (typeof(func) != 'function') die(func+' n\'est pas une fonction');
	try { func(); return true; }
	catch(e) { func; return true; }
	return die(func+' n\'est pas une fonction connue');
}

var arrayCount = function (arr) {
	i = 0;
	for (var attr in arr) i++;
	return i;
};
// ------------------------- makeClass ---------------------------------- //
// By John Resig (MIT Licensed)
/*
	var User = makeClass();
	User.prototype.init = function(first, last){
		this.name = first + " " + last;
	};
	var user = User("John", "Resig");
	user.name
	// => "John Resig"

function makeClass(){
	return function(args) {
		if (this instanceof arguments.callee) {
			if (typeof this.init == 'function') this.init.apply(this, args);
		}
		else return new arguments.callee(arguments);
	};
}
*/
// ------------------------- Eval JS string ---------------------------------- //
/*
	onComplete: function( transport ) {
		if (transport.status == 200) = {
			var script = transport.responseText.extractScripts();
			var html = transport.responseText.stripScripts();
			$(id_dst).innerHTML = html;
			evalJs( script );
		} 
		else alert('There was a problem with the request.');
	}
*/
function evalJs(script) {
   if (window.execScript) return window.execScript(script);
   else if (navigator.userAgent.indexOf('KHTML') != -1 || navigator.userAgent.indexOf ('Mozilla') != -1) { // safari, konqueror, firefox
     var s = document.createElement('script');
     s.type = 'text/javascript';
     s.innerHTML = script;
     document.getElementsByTagName('head')[0].appendChild(s);
   }
   else return window.eval(script);
}


// ------------------------- ECHO ;) ---------------------------------- //
var echo = function(str) { document.write(str); }

// ------------------------- GOTO LOCATION ---------------------------------- //
var redir = function(myUrl) {
	if (isFrame()) {
		if (!isSet(myUrl)) window.parent.document.location.reload();
		else window.parent.document.location.href = myUrl;
	}
	else {
		if (!isSet(myUrl)) window.document.location.reload();
		else window.document.location.href = myUrl;
	}
};

// ------------------------- TIMER ---------------------------------- //
var startTime = null;
var startClock = function() {
  startTime = (new Date).getTime();
}

var stopClock = function() {
  var delta = (new Date).getTime() - startTime;
  startTime = null;
  $('timing').update('Temps : ' + delta + ' ms');
}

// ------------------------- Convert to string ---------------------------------- //
var parseStr = function(str) {
	return str.toString();
};

// ------------------------- Get Html Att from Array ---------------------------------- //
var getAtt = function(name, value) {
	return ' '+name+'="'+value+'"';
}

// ------------------------- PARSE QUERY ---------------------------------- //
var parseQuery = function(query) {
    if (!query) return {};
    var params = {};
    var pairs = query.split(/[;&]/);
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split("=");
        if (!pair || pair.length != 2) continue;
        params[unescape(pair[0])] = unescape(pair[1]).replace(/\+/g, " ");
    }
    return params;
};

// ------------------------- STRING UNIQUE ---------------------------------- //
var incId = 0;
var getUniqueId = function() {
	incId++;
    return 'id_'+incId;
};

// ------------------------- Create loader IMAGE ---------------------------------- //
// setImg('visuel_1', 'imgs/photo.jpg');
var loadArr = {};
var setSrc = function(element, newSrc) { // DEPRECIATED
    setTimeout( function() { element.src = newSrc; }, 100);
};
var loadImg = function(imgSrc) {
	if (!isSet(imgSrc)) return;
    loadArr[imgSrc]= new Image();
    setSrc(loadArr[imgSrc], imgSrc);
	return loadArr[imgSrc];
};
var setImg = function(imgId,imgSrc) {
    if (!isId(imgId) || !isSet(imgSrc)) return;
	var img = loadImg(imgSrc);
	img.onload = function() {
		$(imgId).src = imgSrc;
		$(imgId).style.width = 'auto';
		$(imgId).style.height = 'auto';
};
};

// ------------------------- TRIM ---------------------------------- //
var trim = function(string) {
    return string.replace(/^\s+|\s+$/g, '');
};

// ------------------------- ENCODE URL ---------------------------------- //
var escapeURI = function(url) {
    if (encodeURIComponent) return encodeURIComponent(url);
    else if (encodeURI) return encodeURI(url);
    else if (escape) return escape(url);
    else return url;
};

// ------------------------- SLASHES ---------------------------------- //
var addslashes = function(string) {
    return string.replace(/'/g, "\\'");
};
var stripslashes = function(string) {
    return string.replace(/\\'/g, "'");
};

// ------------------------- baseName ---------------------------------- //
var baseName = function(path) {
    var vb;
    for (var i=path.length; i>0; i--) {
        vb = path.substring(i,i+1)
        if (vb == '/' || vb == '\\') return path.substring(i+1, path.length);
    }
	return path; // Si n'est pas-path..
};

// ------------------------- GetExt ---------------------------------- //
var getExt = function(string) {
	var vb;
	for (var i=string.length; i>0; i--) {
		vb = string.substring(i, i+1);
		if (vb == '.') return string.substring(i+1, string.length);
	}
};

// ------------------------- CLEAN FILE NAME ---------------------------------- //
var affCleanName = function(filetitle) { // 070305142221_c-est_aussi_ca.jpg >>> c est aussi ca
    if (filetitle.match('/')) filetitle = baseName(filetitle);
    myregexp = new RegExp(/[0-9]{4,}/gi);
    filetitle = filetitle.replace(myregexp,'');
    myregexp = new RegExp(/.jpg|.gif|.png/gi);
    filetitle = filetitle.replace(myregexp,'');
    myregexp = new RegExp(/[_|-]/gi);
    filetitle = filetitle.replace(myregexp,' ');
    return trim(filetitle);
};

// ------------------------- inArray ---------------------------------- //
var inArray = function(myValue, myArray) {
	if (isWhat(myArray) != 'object') return false;
    for (var k in myArray) { if (myArray[k] == myValue) return true; }
    return false;
};
var keyInArray = function(myKey, myArray) {
	if (isWhat(myArray) != 'object') return false;
    for (var k in myArray) { if (k === myKey) return true; }
    return false;
};

// ------------------------- STRING REPLACE ------------------------------ //
var strRep = function(string, strSearch, strRep) {
    var regEx = new RegExp(strSearch, 'gi');
    return string.replace(regEx,strRep);
};

var nl2br = function(string) {
	return strRep(string, "\n", '<br />');
};

// -------------------- GET CLASS NAMES -------------------- //
var getClassNames = function(id){
	return $(id).className.split(' ');
};

// -------------------- FIND ID IN CLASS -------------------- //
var findId = function(str) {
	var regexp = /_([0-9a-z_-]+)/;
	var mymatch = regexp.exec(str);
	return (mymatch ? mymatch[1] : false);
};

// -------------------- FIND PARAM IN CLASS -------------------- //
var findParamInClass = function(param, el) {
    var regexp = new RegExp(param + '_([A-Za-z0-9/:?&\-\._]+)');
    var mymatch = regexp.exec(el.className);
    if (mymatch)  return mymatch[1];
    else return false;
};

// ------------------------- VALIDATE URL ------------------------------ //
var checkUrl = function(strUrl) {
    var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    return regexp.test(strUrl);
};

// ------------------------- VALIDATE URL ------------------------------ //
var checkMail = function(strMail) {
    var regexp = /^[A-Za-z0-9._-]+@[A-Za-z0-9.\-]{2,}[.][A-Za-z]{2,4}$/;
    return regexp.test(strMail);
};

// ------------------------- VALIDATE DATE : 15/02/78 ------------------------------ //
var checkDate = function(strDate) {
    if (!isSet(strDate) || !strDate.match('/')) return false;
    var date_array = strDate.split('/');
    var day = String(date_array[0]);
    var month = String(date_array[1]);
    var year = String(date_array[2]);
    if (day.length < 2 || month.length < 2 || year.length < 2) return false;
    if (parseInt(year) > 78) year = '19'+year;
    else year = '20'+year;
    month = parseInt(month - 1);
    var source_date = new Date(year,month,day);
    if (year != source_date.getFullYear() || month != source_date.getMonth() || day != source_date.getDate()) return false;
    else  return true;
};

// -------------------- Format Time -------------------- //
var formatTime = function(seconds) {
	var printDate = false;
	var timeFormat = '';
	var myHtml = '';
	var Stamp = new Date();
	switch(timeFormat) {
		case 'locale' : Stamp.toLocaleString(); break;
		case 'gtmdiff' : Stamp.getTimezoneOffset(); break;
		case 'gtm' : Stamp.toGMTString(); break;
	}
	// DATE
	if (printDate) { 
		var y = Stamp.getFullYear();
		var m = (Stamp.getMonth() + 1).toPaddedString(2);
		var d = Stamp.getDate();
		myHtml += d+'/'+m+'/'+y;
	}
	// TEMPS
	var he = Stamp.getHours().toPaddedString(2);
	var mi = Stamp.getMinutes().toPaddedString(2);
	var se = Stamp.getSeconds().toPaddedString(2);
	myHtml += he+'h'+mi+'min'+se+'sec';
    return myHtml;
};

// -------------------- Listen and Get KEY -------------------- //
// Also see // Event.observe('myInput','keypress', functions);

var getKey = function(e) { // Call it with : getKey(event) ! // 13 = Enter
	var keycode = ( window.event ? window.event.keyCode : e.which );
	switch(keycode) { case '' : return ''; case 0 : return ''; case 8 : return 'backspace'; case 9 : return 'tab'; case 13 : return 'return'; case 27 : return 'esc'; case 33 : return 'pageup'; case 34 : return 'pagedown'; case 35 : return 'end'; case 36 : return 'home'; case 37 : return 'left'; case 38 : return 'up'; case 39 : return 'right'; case 40 : return 'down'; case 46 : return 'del'; default : return String.fromCharCode(keycode).toLowerCase(); }
};
// Event.KEY_RETURN
var inputListen = {};
var enterEvent = function(element, func) { // onFocus="enterEvent('password',submitForm);"
	if (isSet(inputListen[element])) Event.stopObserving(element, 'keypress');
	else inputListen[element] = 1;
	var makeAction = function(e) {
		if (getKey(e) == 'return') { //Event.KEY_RETURN
				Event.stopObserving(element, 'keypress');
			return exec(func);
			}
};
	Event.observe(element, 'keypress', makeAction);
};
var listenKey = function(makeAction) { document.onkeypress = makeAction(getKey(event)); };


// ------------------------- SHOW HIDE BLOCK ELEMENTS FOR IE ------------------------------ //
var showHideBoxes = function(v) {
	if (!isSet(detectedBrowser)) var detectedBrowser = client.browser();
	if (detectedBrowser == 'msie 6' || detectedBrowser == 'msieOld') $$('select', 'iframe', 'embed', 'object').invoke((v == 'hidden' || v == 'hide' ? 'hide' : 'show'));
};

// -------------------- FAVORIS -------------------- //
var addFav = function(){
   if (window.sidebar) window.sidebar.addPanel(document.title, window.location.href, '');
   else if (window.external) window.external.AddFavorite(window.location.href, document.title);
   else alert('Pour ajouter cette page &agrave; vos favoris : Ctrl + D');
}

// -------------------- PRINT -------------------- //
var printPage = function(){
   if (window.print) window.print();
   else alert('Pour imprimer cette page : Ctrl + P');
}