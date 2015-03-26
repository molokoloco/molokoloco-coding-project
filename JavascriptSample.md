
---


**Update**

```
npm search '/^mykeyword/'
```

**Sommaire :**




---


## En vrac ##

```
// TOUTES PETITES... :)
var db = function() { 'console' in window && console.log.call(console, arguments); }; // Usage : db('(Debug) maVar : ', maVar);
var die = function(mess) { throw(( mess ? mess : "JS says that you killing him softly : Oh my god moonWalker is down...")); };
var trim = function(string) { return string.replace(/^\s+|\s+$/g, ''); };
var escapeURI = function(url) { if (encodeURIComponent) return encodeURIComponent(url); else if (encodeURI) return encodeURI(url); else if (escape) return escape(url); else return url; };
var pad = function(n) { return (n < 10 ? '0'+n : n); };
var addslashes = function (str) { return (str+'').replace(/([\\"'])/g, "\\$1").replace(/\u0000/g, "\\0"); };
// index.html?name=foo -> var name = getUrlVars()[name];
var getUrlVars = function() { var vars = {}; var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) { vars[key] = value; }); return vars; };
// sites['link'].sort($.objSortByTitle);
var objSortByTitle = function (a, b) { var x = a.title.toLowerCase(); var y = b.title.toLowerCase(); return ((x < y) ? -1 : ((x > y) ? 1 : 0)); };
var loadCss = function(stylePath) { $('head').append('<link rel="stylesheet" type="text/css" href="'+stylePath+'"/>'); };
```


## Loading Javascript file ##

#### For external domain JS link ####

```
var loadJs = function(jsPath) {
    var s = document.createElement('script');
    s.setAttribute('type', 'text/javascript');
    s.setAttribute('src', jsPath);
    document.getElementsByTagName('head')[0].appendChild(s);
};
loadJs('http://other.com/other.js');
```

#### For same domain JS link (Using jQuery) ####

```
var getScript = function(jsPath, callback) {
    $.ajax({
        dataType:'script',
        async:false,
        cache:true,
        url:jsPath,
        success:function(response) {
            if (callback && typeof callback == 'function') callback();
        }
    });
};
getScript('js/other.js', function() { functionFromOther(); });
```

#### Get an Absolute URL ####
// http://davidwalsh.name/get-absolute-url

```
var getAbsoluteUrl = (function() {
	var a;
	return function(url) {
		if (!a) a = document.createElement('a');
		a.href = url;
		return a.href;
	};
})();
```

# My Javascript Toolbox #
_Library edited from 2005 to 2007..._

## 200\_tools.js ##


All Framework here :
[trunk/SITE\_01\_SRC/js/proto/optional/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/js/proto/optional/) - [200\_tools.js](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/js/proto/optional/200_tools.js)


```

/* ////////////////////////////////////////////////////////////////////////////////////
//    Code mixing by Molokoloco - www.b2bweb.fr - BETA TESTING FOR EVER ! (o_O)     //
////////////////////////////////////////////////////////////////////////////////// */


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

// ------------------------- Sort Array ---------------------------------- //
// myArray.sort(objSortByTitle);
var objSortByTitle = function (a, b) {
        var x = a.title.toLowerCase();
        var y = b.title.toLowerCase();
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
};

// ------------------------- Load new script file  ---------------------------------- //
var _loadJs = function(src) {
        var s = document.createElement('script');
        s.setAttribute('type', 'text/javascript');
        s.setAttribute('src', src);
        document.getElementsByTagName('head')[0].appendChild(s);
};

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

```


## For more information : svn/trunk/trunk/SITE\_01\_SRC/ ##
http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/


# PROTO EXTENDS UTILS #

```
Array.prototype.filter || (Array.prototype.filter = function(e) {
    var t, n, r, i;
    i = [];
    for (n = 0, r = this.length; n < r; n++) {
        t = this[n];
        e(t) && i.push(t)
    }
    return i
});
Array.prototype.map || (Array.prototype.map = function(e, t) {
    var n, r, i, s, o, u, a, f, l, c;
    i = null;
    n = null;
    s = null;
    if (this === null) throw new TypeError(" this is null or not defined");
    r = Object(this);
    u = r.length >>> 0;
    if ({}.toString.call(e) !== "[object Function]") throw new TypeError(e + " is not a function");
    t && (i = t);
    n = new Array(u);
    s = 0;
    while (s < u) {
        o = null;
        a = null;
        if (__indexOf.call(function() {
            c = [];
            for (var e = 0, t = r.length; 0 <= t ? e < t : e > t; 0 <= t ? e++ : e--) c.push(e);
            return c
        }.apply(this), s) >= 0) {
            o = r[s];
            a = e.call(i, o, s, r);
            n[s] = a
        }
        s++
    }
    return n
});
Array.prototype.clone = function() {
    var e, t;
    t = this instanceof Array ? [] : {};
    for (e in this) {
        if (e === "clone") continue;
        this[e] && typeof this[e] == "object" ? t[e] = this[e].clone() : t[e] = this[e]
    }
    return t
};
Array.prototype.toDict = function(e) {
    return this.reduce(function(t, n) {
        n[e] != null && (t[n[e]] = n);
        return t
    }, {})
};
Array.prototype.shuffle = function() {
    return this.sort(function() {
        return .5 - Math.random()
    })
};
Array.prototype.merge = function(e) {
    return Array.prototype.push.apply(this, e)
};
Array.prototype.intersection = function(e) {
    var t, n, r, i, s, o, u;
    t = this;
    n = e;
    t.length > n.length && (o = [n, t], t = o[0], n = o[1]);
    u = [];
    for (i = 0, s = t.length; i < s; i++) {
        r = t[i];
        __indexOf.call(n, r) >= 0 && u.push(r)
    }
    return u
};
Array.prototype.unique = function() {
    var e, t, n, r, i, s;
    t = {};
    for (e = r = 0, i = this.length; 0 <= i ? r < i : r > i; e = 0 <= i ? ++r : --r) t[this[e]] = this[e];
    s = [];
    for (e in t) {
        n = t[e];
        s.push(n)
    }
    return s
};
Array.prototype.near = function(e) {
    var t;
    t = this.clone();
    return t.sort(function(t, n) {
        return distance(e, t) - distance(e, n)
    })
};
Array.prototype.first = function() {
    return $(this)[0]
};
Array.prototype.last = function() {
    return $(this)[this.length - 1]
};
(_ref = Array.prototype.some) != null ? _ref : function(e) {
    var t, n, r;
    for (n = 0, r = this.length; n < r; n++) {
        t = this[n];
        if (e(t)) return !0
    }
    return !1
};
Array.prototype.sortBy = function(e, t) {
    t == null && (t = "asc");
    return this.sort(function(n, r) {
        t = t === "asc" ? 1 : -1;
        return n[e] > r[e] ? -1 * t : n[e] < r[e] ? 1 * t : 0
    })
};
Array.prototype.groupBy = function(e, t) {
    var n, r, i, s, o;
    t == null && (t = null);
    n = {};
    for (s = 0, o = this.length; s < o; s++) {
        i = this[s];
        if (i[e]) {
            r = String(i[e]);
            r = (t != null ? r.substring(0, t) : r).toLowerCase();
            n[r] || (n[r] = []);
            n[r].push(i)
        }
    }
    return n
};
Array.prototype.del = function(e) {
    var t;
    t = this.indexOf(e);
    return this.splice(t, 1)
};
Number.prototype.humanize = function(e) {
    var t, n, r, i, s, o;
    e == null && (e = ".");
    i = String(this);
    if (i.length <= 3) return i;
    r = "";
    for (t = s = 0, o = i.length; 0 <= o ? s <= o : s >= o; t = 0 <= o ? ++s : --s) {
        n = i.length - t;
        i[n] && (r = "" + i[n] + r);
        t % 3 === 0 && t > 0 && (r = "" + e + r)
    }
    r[0] === e && (r = r.slice(1, r.length));
    return r
};
String.prototype.upcase = function() {
    return this.toUpperCase()
};
String.prototype.downcase = function() {
    return this.toLowerCase()
};
String.prototype.trim || (String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, "")
});
String.prototype.strip = function() {
    return String.prototype.trim != null ? this.trim() : this.replace(/^\s+|\s+$/g, "")
};
String.prototype.lstrip = function() {
    return this.replace(/^\s+/g, "")
};
String.prototype.rstrip = function() {
    return this.replace(/\s+$/g, "")
};
String.prototype.capitalize = function() {
    return "" + this.charAt(0).toUpperCase() + this.slice(1)
};
String.prototype.extractDataFrom = function(e) {
    var t, n, r, i, s;
    n = e;
    s = this.split(".");
    for (r = 0, i = s.length; r < i; r++) {
        t = s[r];
        n = n[t]
    }
    return n
};
String.prototype.toPath = function(e) {
    e == null && (e = "path");
    return ("" + app[e] + this).replace(/\/\//g, "/")
};
String.prototype.truncate = function(e, t) {
    var n, r;
    t == null && (t = "...");
    r = this.length > e;
    n = r ? this.substr(0, e - 1 - t.length) : "" + this;
    r && (n = "" + n.substr(0, n.lastIndexOf(" ")) + t);
    return n
};
String.prototype.isEmail = function() {
    var e;
    e = /^[a-zA-Z0-9][a-zA-Z0-9\._-]+@([a-zA-Z0-9\._-]+\.)[a-zA-Z-0-9]{2}/;
    return e.exec(this) ? !0 : !1
};
String.prototype.toSlug = function() {
    var e, t, n, r, i, s;
    n = this.replace(/^\s+|\s+$/g, "");
    n = n.toLowerCase();
    e = "Ã Ã¡Ã¤Ã¢Ã¨Ã©Ã«ÃªÃ¬Ã­Ã¯Ã®Ã²Ã³Ã¶Ã´Ã¹ÃºÃ¼Ã»Ã±Ã§Â·/_,:;";
    r = "aaaaeeeeiiiioooouuuunc------";
    for (t = i = 0, s = e.length; 0 <= s ? i < s : i > s; t = 0 <= s ? ++i : --i) n = n.replace(new RegExp(e.charAt(t), "g"), r.charAt(t));
    return n = n.replace(/[^a-z0-9 -]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-")
};
String.prototype.repeat = function(e) {
    var t, n;
    n = 0;
    t = "";
    while (n < e) {
        t += this;
        n++
    }
    return t
};
String.prototype.ljust = function(e, t) {
    t = t || " ";
    t = t.substr(0, 1);
    return this.length < e ? this + t.repeat(e - this.length) : this
};
String.prototype.rjust = function(e, t) {
    t = t || " ";
    t = t.substr(0, 1);
    return this.length < e ? t.repeat(e - this.length) + this : this
};
```

```
Cookie = function() {
    function e() {}
    e.create = function(e, t, n) {
        var r, i;
        n == null && (n = null);
        r = new Date;
        t = JSON.stringify(t);
        if (n != null) {
            r.setTime(r.getTime() + n * 24 * 60 * 60 * 1e3);
            i = "; expires=" + r.toGMTString()
        } else i = "";
        return document.cookie = e + "=" + t + i + "; path=/"
    };
    e.read = function(e) {
        var t, n, r, i, s;
        n = e + "=";
        t = document.cookie.split(";");
        for (i = 0, s = t.length; i < s; i++) {
            r = t[i];
            while (r.charAt(0) === " ") r = r.substring(1, r.length);
            if (r.indexOf(n) === 0) return JSON.parse(r.substring(n.length, r.length))
        }
        return null
    };
    e.remove = function(t) {
        return e.create(t, "", -1)
    };
    return e
}();
```

```
Ajax = function() {
    function e(e, t, n, r) {
        this.url = e;
        this.callback = t;
        this.callback_error = n;
        this.dataType = r;
        this.dataType == null && (this.dataType = "html");
        this.callback == null && (this.callback = function(e) {
            return puts(e)
        });
        this.callback_error == null && (this.callback_error = function(e) {
            return puts(e)
        })
    }
    e.prototype.ajax_request = function(e, t) {
        return $.ajax({
            url: this.url,
            data: t,
            type: e,
            cache: !0,
            context: document.body,
            dataType: this.dataType,
            success: this.callback,
            error: this.callback_error
        })
    };
    e.prototype.get = function() {
        return this.ajax_request("GET", null)
    };
    e.prototype.post = function(e) {
        return this.ajax_request("POST", e)
    };
    e.prototype.put = function(e) {
        return this.ajax_request("PUT", e)
    };
    e.prototype["delete"] = function(e) {
        return this.ajax_request("DELETE", e)
    };
    return e
}();
```

```
Browser = function() {
    function e() {}
    e.isIphone = function() {
        return navigator.platform.indexOf("iPhone") !== -1 || navigator.platform.indexOf("iPod") !== -1
    };
    e.isIpad = function() {
        return navigator.userAgent.match(/iPad/i) !== null
    };
    e.isIe = function() {
        return navigator.appVersion.indexOf("MSIE") !== -1 ? !0 : !1
    };
    e.ieHack = function() {
        if ($.browser.msie && $.browser.version === "7.0") return $("body").addClass("ie7");
        if ($.browser.msie && $.browser.version === "8.0") return $("body").addClass("ie8")
    };
    return e
}();
```

```
Facebook = function() {
    function e() {}
    e.locale = $("meta[property=facebook\\:locale]").length > 0 ? $("meta[property=facebook\\:locale]").attr("content") : "en_US";
    e.appId = $("meta[property=facebook\\:appID]").length > 0 ? $("meta[property=facebook\\:appID]").attr("content") : null;
    e.scope = $("meta[property=facebook\\:scope]").length > 0 ? $("meta[property=facebook\\:scope]").attr("content") : null;
    e.hasInitialized = !1;
    e.options = {
        status: !0,
        cookie: !0,
        xfbml: !0
    };
    e.authResponse = null;
    e.loadJS = function() {
        var t, n;
        t = "facebook-jssdk";
        if (document.getElementById(t)) return;
        $("#fb-root").length === 0 && $("body").append('<div id="fb-root"></div>');
        n = document.createElement("script");
        n.id = t;
        n.async = !0;
        n.src = "//connect.facebook.net/" + e.locale + "/all.js";
        return document.getElementsByTagName("head")[0].appendChild(n)
    };
    e.init = function(t) {
        var n;
        t == null && (t = null);
        e.loadJS();
        n = $.extend({
            appId: e.appId
        }, e.options);
        try {
            e.hasInitialized || FB.init(n);
            if (t != null) return t()
        } catch (r) {
            return window.fbAsyncInit = function() {
                FB.init(n);
                e.hasInitialized = !0;
                if (t != null) return t()
            }
        }
    };
    e.login = function(t, n) {
        var r;
        n == null && (n = null);
        if (Browser.isIphone() || Browser.isIpad()) {
            r = encodeURI(location.href);
            return window.top.location = "https://m.facebook.com/dialog/oauth?client_id=" + e.appId + "&redirect_uri=" + r + "&scope=" + e.scope
        }
        return FB.login(function(r) {
            if (r.authResponse) {
                e.authResponse = r.authResponse;
                return t(r.authResponse)
            }
            if (n != null) return n()
        }, {
            scope: e.scope
        })
    };
    e.authorize = function(t, n) {
        n == null && (n = null);
        return e.authResponse != null ? t(e.authResponse) : FB.getLoginStatus(function(r) {
            if (r.status && r.authResponse != null) {
                e.authResponse = r.authResponse;
                return t()
            }
            return e.login(t, n)
        })
    };
    e.share = function(e, t) {
        e == null && (e = {});
        t == null && (t = null);
        e.method = "feed";
        return FB.ui(e, t)
    };
    return e
}();
```

## Other examples ##


// converting times to milliseconds...

var ms = require('milliseconds');

ms.seconds(2); // 2000
ms.minutes(2); // 120000
ms.hours(2);   // 7200000
ms.days(2);    // 172800000
ms.weeks(2);   // 1209600000
ms.months(2);  // 5259600000
ms.years(2);   // 63115200000


// milliseconds.js

function calc(m) {
    return function(n) { return Math.round(n * m); };
};
module.exports = {
    seconds: calc(1e3),
    minutes: calc(6e4),
    hours: calc(36e5),
    days: calc(864e5),
    weeks: calc(6048e5),
    months: calc(26298e5),
    years: calc(315576e5)
};```