/////////// DATE ET HEURE ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
	// SIMPLE :) 
	myHorloge = new horloge('horlogeBox', {});
	
	// WITH PARAMS // Si images de 0 à 9 = path/x.png | h.png = sep heure | m.png = sep min. | s.png = sep seconde
	myHorloge = new horloge('horlogeBox', {
		affichage: 'image',
		imgPath: 'images/chiffres_lettres/', 
		imgExt: 'png',
		timeFormat: 'gtm',
		dateStyle: 'font: bold 18px Arial, Helvetica, sans-serif;',
		heureStyle: 'font: 14px Arial, Helvetica, sans-serif;'
	});
*/
if (typeof db == 'undefined') throw("horloge.js requires tools.js");

var horloge = Class.create();
horloge.prototype = {

	initialize: function(boxElement, arrParam) {
		if (!isId(boxElement)) return printInfo('Pas de div containeur');
		
		this.boxElement = $(boxElement);
		
		this.objetTimeOut = null; // Stock timeOut event
		
		this.affichage = arrParam.affichage ? arrParam.affichage : 'chiffre'; // chiffre || image
		this.timeFormat = arrParam.timeFormat ? arrParam.timeFormat : ''; // 'locale' || 'gtmdiff' || 'gtm'
		this.dateStyle = arrParam.dateStyle ? arrParam.dateStyle : 'font: bold 14px Arial, Helvetica, sans-serif;'; // Style de la date
		this.heureStyle = arrParam.heureStyle ? arrParam.heureStyle : 'font: 10px Arial, Helvetica, sans-serif;'; // Style si chiffre
		this.imgStyle = arrParam.imgStyle ? 'style="'+arrParam.imgStyle+'"' : ''; // Style si chiffre
		this.printDate = arrParam.printDate ? arrParam.printDate : false; // chiffre || image
		
		this.imgPath = arrParam.imgPath ? arrParam.imgPath : '';
		this.imgExt = arrParam.imgExt ? arrParam.imgExt : '';
		if (this.imgPath == '' && this.affichage == 'image') return printInfo('Il manque le chemin des images : imgPath')
		if (this.imgExt == '' && this.affichage == 'image') return printInfo('Il manque les extensions des images : imgExt')

		this.dateTpl = new Template('<div style="'+this.dateStyle+'" id="date_'+boxElement+'">#{d}/#{m}/#{y}</div>');
		
		switch(this.affichage) {
			case 'image' :
				this.tempsTpl = new Template('<img src="'+this.imgPath+'#{imgNumber}.'+this.imgExt+'" border="0" id="#{imgId}" '+this.imgStyle+'/>');
				for (var i=0; i < 10; i++) loadImg(this.imgPath+i+'.'+this.imgExt); // Preload image
			break;
			default :
				this.tempsTpl = new Template('<div style="'+this.heureStyle+';" id="heure_'+boxElement+'">#{imgNumber}</div>');
			break;
		}

		this.makeTime();
		this.setTimed();
	},
	
	setTimed: function() {
		this.objetTimeOut = new PeriodicalExecuter(this.makeTime.bind(this), 1);
	},
	
	makeTime: function() {
		
		var Stamp = new Date();
		
		switch(this.timeFormat) {
			case 'locale' : Stamp.toLocaleString(); break;
			case 'gtmdiff' : Stamp.getTimezoneOffset(); break;
			case 'gtm' : Stamp.toGMTString(); break;
		}
		
		var myHtml = '';
		
		if (this.printDate) { // DATE
			var y = Stamp.getFullYear();
			var m = (Stamp.getMonth() + 1).toPaddedString(2);
			var d = Stamp.getDate();
			
			var dateArr = { d: d, m: m, y: y };
			myHtml += this.dateTpl.evaluate(dateArr);
		}
		
		// TEMPS
		var he = Stamp.getHours().toPaddedString(2);
		var mi = Stamp.getMinutes().toPaddedString(2);
		var se = Stamp.getSeconds().toPaddedString(2);
		
		if (!$('he0')) { // Create image sequence
			
			// heures
			var he0 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'he0', imgNumber: he.charAt(0) };
			myHtml += this.tempsTpl.evaluate(he0);
			var he1 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'he1', imgNumber: he.charAt(1) };
			myHtml += this.tempsTpl.evaluate(he1);
			
			var hh = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'h', imgNumber: 'h' };
			myHtml += this.tempsTpl.evaluate(hh);
			
			// minutes
			var mi0 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'mi0', imgNumber: mi.charAt(0) };
			myHtml += this.tempsTpl.evaluate(mi0);
			var mi1 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'mi1', imgNumber: mi.charAt(1) };
			myHtml += this.tempsTpl.evaluate(mi1);
			
			var mm = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'m', imgNumber: 'm' };
			myHtml += this.tempsTpl.evaluate(mm);
			
			// secondes
			var se0 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'se0', imgNumber: 's'+se.toString().charAt(0) }; // Cas particulier image Sec. plus petite "s1.png"
			myHtml += this.tempsTpl.evaluate(se0);
			var se1 = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'se1', imgNumber: 's'+se.toString().charAt(1) };
			myHtml += this.tempsTpl.evaluate(se1);
			
			var ss = { imgPath: this.imgPath, imgExt: this.imgExt, imgId:'s', imgNumber: 's' };
			myHtml += this.tempsTpl.evaluate(ss);
			
			$('horlogeBox').update(myHtml);	

		}
		else { // update only if change
			if ($('he0').src != this.imgPath+he.charAt(0)+'.'+this.imgExt ) $('he0').src = this.imgPath+he.charAt(0)+'.'+this.imgExt;
			if ($('he1').src != this.imgPath+he.charAt(1)+'.'+this.imgExt ) $('he1').src = this.imgPath+he.charAt(1)+'.'+this.imgExt;
			
			if ($('mi0').src != this.imgPath+mi.charAt(0)+'.'+this.imgExt ) $('mi0').src = this.imgPath+mi.charAt(0)+'.'+this.imgExt;
			if ($('mi1').src != this.imgPath+mi.charAt(1)+'.'+this.imgExt ) $('mi1').src = this.imgPath+mi.charAt(1)+'.'+this.imgExt;
			
			if ($('se0').src != this.imgPath+se.charAt(0)+'.'+this.imgExt ) $('se0').src = this.imgPath+'s'+se.charAt(0)+'.'+this.imgExt;
			if ($('se1').src != this.imgPath+se.charAt(1)+'.'+this.imgExt ) $('se1').src = this.imgPath+'s'+se.charAt(1)+'.'+this.imgExt;
		}
	}
}
