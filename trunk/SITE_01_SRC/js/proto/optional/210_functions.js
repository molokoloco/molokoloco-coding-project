/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
///////////////////////////////////////////////////////////////////////////////////////////////////////

Functions :

	getTpl(type)									: getTpl('img', {id:'toto',src:'tutu.jpg',width:'245px'});
	addEmailDest(contentId)
	fadeTableRow(rowid,opts)
	iniRoll(evt)
	fixPng(evt)
	creatDiv(parentElement, attr, contentHtm) 		: attr is "id" || {id:'divId',style:'styleDiv',className:'lassDiv'}
	showHideBoxes(visibility) // visibility = hidden|visible
	ajaxCheck(input,valeur,divInfo)

/////////////////////////////////////////////////////////////////////////////////////////////////////// */

if (typeof db == 'undefined') throw("function.js requires tools.js");
else if(typeof Effect == 'undefined') throw("function.js requires including script.aculo.us effects.js library");

/* ------------------------- BUILD HTML TEMPLATE ---------------------------------- */
// echo(getTpl('img', {src:'toto.jpg'} ));
// var myDiv = getTpl('div', {id:'myDiv', content:'Mr Dupont'});

var getTpl = function (tagFragment) {
	var options = Object.extend( {tagFragment: tagFragment}, arguments[1] || {});
	var tpl = '';
	var customAtt = '';
	var defaultAttributes =  {
		id: 		getAtt('id', ( options.id || tagFragment+getUniqueId() )),
		alt: 		( (options.alt || options.src) ? getAtt('alt', ( options.alt || options.src)) : '' ),
		name: 		getAtt('name', ( options.name || options.id )),
		href: 		getAtt('href', ( options.href || 'javascript:void(0);' )),
		border: 	getAtt('border', ( options.border | 0 )),
		inner: 		( options.inner ? options.inner : '')
	}
	for( var key in options) {
		if (key != 'tagFragment' && !keyInArray(key, defaultAttributes)) {
			defaultAttributes[key] = getAtt(key, options[key]);
			customAtt += '#{'+key+'}';
		}
	}
	switch(options.tagFragment) {
		case 'a' : 			tpl = new Template('<a#{id}#{href}'+customAtt+'/>#{inner}</a>'); break;
		case 'img' : 		tpl = new Template('<img#{id}#{alt}#{name}#{border}'+customAtt+'/>'); break;
		case 'div' : 		tpl = new Template('<div#{id}'+customAtt+'/>#{inner}</div>'); break;
		case 'input' : 		tpl = new Template('<input#{id}#{name}'+customAtt+'/>'); break;
		case 'textarea' : 	tpl = new Template('<textarea#{id}#{name}'+customAtt+'/>#{inner}</textarea>'); break;
		case 'select' : 	tpl = new Template('<select#{id}#{name}'+customAtt+'/>#{inner}</select>'); break;
		default : 			tpl = new Template('<'+options.tagFragment+'#{id}'+customAtt+'/>#{inner}</'+options.tagFragment+'>'); break;
	}
	return tpl.evaluate(defaultAttributes);	
};

/* ------------------------- FADE TABLE ROW ---------------------------------- */
function fadeTableRow(rowid,opts) {
    if(!opts) opts = {};
    var row = $(rowid);
    var cells = row.childNodes;
    for(i=0; i<cells.length; i++){
        if (cells[i].tagName == 'TD') new Effect.Fade(cells[i],opts);
    }
    new Effect.Fade(row,opts);
};

// ------------------------- ADD ROLL OVER IMAGE ---------------------------------- // DEPRECIATED > CSS:hover
var srcBak = {}; // Stock ex Src
var srcLoad = {}; // preLoad hover
var iniRoll = function(evt) {
    if (evt) Event.stop(evt);
    $$('img[roll]').each( function(picture) { // loop through all images tags with attr "roll"
        if ($(picture).getAttribute('roll') != '' ) {
			if (getExt($(picture).getAttribute('roll')).toLowerCase()=='png' && ( client.isIe && !client.isIe7 )) { void (0); }
			else {
				var rid = $(picture).id;
				if (!rid) { // generate unik Id
					rid = getUniqueId();
					$(picture).id = rid;
				}
				srcBak[rid] = $(picture).src; // Stock it
				loadImg($(picture).getAttribute('roll')); // Preload Hover src
				Event.observe(picture, 'mouseover', function(evt) {
					if (this.src) this.src = this.getAttribute('roll');
					else if (evt.srcElement.src) evt.srcElement.src = evt.srcElement.roll; // IE
				});
				Event.observe(picture, 'mouseout', function(evt) {
					if (this.id) {
						var rid = this.id;
						this.src = srcBak[rid];
					}
					else if (evt.srcElement.id) {
						var rid = evt.srcElement.id;
						evt.srcElement.src = srcBak[rid];
					}
				});
			}
        }
    });
};
//Event.observe(window, 'load', iniRoll, true);

// ------------------------- FIX PNG ---------------------------------- //
var fixPng = function(evt) {
    if (evt) Event.stop(evt);
	for(var i=0; i<document.images.length; i++) {
		var img = document.images[i];
		var imgSrc = img.src;
		if (getExt(baseName(imgSrc)).toLowerCase()=="png") {
			img.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+imgSrc+"', sizingMethod='scale');";
			img.src = 'images/pixel.gif';
		}
   }
};
var detectedBrowser = client.browser();
if (detectedBrowser == 'msie 6' || detectedBrowser == 'msieOld') Event.observe(window,'load',fixPng,false);
