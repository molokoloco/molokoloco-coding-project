/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco - www.borntobeweb.fr - BETA TESTING FOR EVER !       (o_O)       ///////
///////////////////////////////////////////////////////////////////////////////////////////////////////
/////////// CMS EDITOR ///////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////*/
/*
	TO USE IN VIRTUAL ADMIN ONLY : IT'S NOT FOR THE FRONT.. FOR THE MOMENT
*/

// ------------------------- SOMES VARS ---------------------------------- //
var elementsListeChange = false;
var elementsEditChange = false;
var inprogress = 'inprogress';

var stockObj = [];
var s = 0;

// ------------------------- SOMES FUNCTION ---------------------------------- //

function effClicTous(form, booleen) {
	form = document[form];
	for (i=0; i<form.elements.length; i++) { 
		if (form.elements[i].name.indexOf('eff') != -1) form.elements[i].checked = booleen;
	}
}

var formIsEdited = function(event) {
	 elementsEditChange = true;
	 //$('iframe_element').src = $('iframe_element').src; // have to save form before !
	 Effect.Pulsate('VALIDER_FORM');
	 Effect.Pulsate('VALIDER_FORM_2');
}

var listenFormEdit = function() {
	$('frm_edit_element').getElements().invoke('observe', 'change', formIsEdited);
}

function initElementSort(evt) {
	if (evt) Event.stop(evt);
	if (!isId('element_list')) return;
	// CREATE SORTABLE
	Sortable.create('element_list', {
		tag:'li',
		overlap:'vertical',
		constraint:'vertical',
		ghosting:false, /* no update with false.... */
		handle:'move',
		onUpdate:function(element){},
		onChange:function(element) {
			elementsListeChange = true;
			Effect.Appear('ENREGISTRER');
			Effect.Pulsate('ENREGISTRER');
			$('element_serialize').value = Sortable.serialize('element_list', {name:'element_id'});
		}
	});
	$('element_serialize').value = Sortable.serialize('element_list', {name:'element_id'});
};
Event.observe(window, 'load', initElementSort, false);

function initFormElementSort(evt) {
	if (evt) Event.stop(evt);
	if (!isId('form_list') || !isId('eraseBin')) return;

	Sortable.create('form_list', {
		tag:'li',
		overlap:'vertical',
		constraint:'vertical',
		ghosting:false,
		handle:'move',
		onUpdate:function(element){},
		onChange:function(element){}
	});

	Droppables.add('eraseBin', {
		//accept:'element_list-items',
		hoverclass:'wastebin_active',
		onDrop:function(e) {
			Element.remove(e);
		}
	});
};
Event.observe(window, 'load', initFormElementSort, false);


// ------------------------- AJAX ACTIONS GOODIES ---------------------------------- //

var getElementRow = function(element_type_titre, element_id, rubrique_id) {
	var elementRow = '';
	elementRow += '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #fff;">';
	elementRow += '<tr  id="tr_element_'+element_id+'" class="table-ligne1" onmouseover="this.style.backgroundColor=\'#FFFFFF\';" onmouseout="this.style.backgroundColor=\'\';">';
	elementRow += '<td class="texte" style="border-left:1px solid #fff;"><img src="../images/drag.png" width="30" height="22" align="absmiddle" border="0" title="D&eacute;placer" class="move" /><a href="javascript:editElement('+element_id+','+rubrique_id+');" title="Editer l\'&eacute;l&eacute;ment">'+element_type_titre+'</a></td>';
	elementRow += '<td align="center" class="texte" style="width:70px;_width:66px;border-left:1px solid #fff;"><input type="checkbox" name="actif[]" onChange="setActif(this.checked,\''+element_id+'\');" class="radio" id="actif_'+element_id+'"/></td>';
	elementRow += '<td align="center" style="width:70px;_width:66px;border-left:1px solid #fff;"><input name="eff[]" type="checkbox" class="effelementcheck" value="'+element_id+'" /></td>';
	elementRow += '</tr>';
	elementRow += '</table>';
	return elementRow;
};

var addElement = function(element_type_titre, element_type_id, rubrique_id, element_langue) {
	var targetFile = 'cms_element_ajax_action.php';
	var params = 'action=create_next_element&id='+rubrique_id+'&element_type_id='+element_type_id+'&element_langue='+element_langue;
	var targetPane = 'element_list_view';
	
	// create_next_element
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: params,
		evalScripts: true,
		onSuccess: function(requete) {
			if ($(targetPane).style.display=='none') Effect.Appear(targetPane);
			var element_id = parseInt(requete.responseText);
			// Insert new rox
			var targetul = 'element_list';
			var my_li = document.createElement('li');
			my_li.id = 'element_'+element_id;
			$(targetul).appendChild(my_li);
			$('element_'+element_id).update(getElementRow(element_type_titre, element_id, rubrique_id));
			initElementSort('');
			elementsListeChange = true;
			new Effect.ScrollTo('element_'+element_id, {offset: -400, afterFinish:function() {new Effect.Highlight('tr_element_'+element_id);}});
		}
	});
};

var effElement = function() {
	if (window.confirm('Etes vous s&ucirc;r de vouloir effacer le/les &eacute;l&eacute;ment(s) s&eacute;lectionn&eacute;(s) ?')) {
		$$('#element_list input.effelementcheck').each(function(e) {
			if ($(e).checked) {
				var element_id = e.getAttribute('value');
				var targetFile = 'cms_element_ajax_action.php';
				s++;
				stockObj[s] = new Ajax.Request(targetFile, {
					method: 'get',
					parameters: 'action=efface_element&element_id='+element_id,
					evalScripts: true,
					onSuccess: function(requete) {
						Effect.Fade('element_'+element_id, {afterFinish:function(effect){
							Element.remove(effect.element);
							//Effect.Pulsate('PREVISUALISER');
							var rubrique_id = $('rubrique_id').options[$('rubrique_id').selectedIndex].value;
							getCmsPreview(rubrique_id, 0);
						}});
					}
				});
			}
		});
	}
}

var setActif = function(bool, element_id) {
	var targetFile = 'cms_element_ajax_action.php';
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'action=actif_element&element_id='+element_id+'&actif='+(bool ? '1' : '0'),
		evalScripts: true,
		onSuccess: function(requete) {
			new Effect.Highlight('tr_element_'+element_id);
			Effect.Pulsate('PREVISUALISER');
		}
	});
};

var editElement = function(element_id) {
	if (elementsEditChange) {
		if (!window.confirm('Etes vous s&ucirc;r de vouloir perdre les modifications sur l\'&eacute;l&eacute;ment que vous êtes en train d\'&eacute;diter ?')) return;
		else elementsEditChange = false;
	}
	var targetid = 'cms_element_pane';
	var targetFile = 'cms_element_form.php';
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'element_id='+element_id,
		evalScripts: true,
		onSuccess: function(requete) {
			$(targetid).update(requete.responseText);
			listenFormEdit();
			initFormElementSort('');
			new Effect.ScrollTo('cms_element_pane', {offset: -20});
		}
	});
};

var addFormElement = function(tpl_to_add) {
	var mytpl = new Template(tpl_to_add);
	var defaultAttributes = {input_id:getUniqueId()};
	mytplHtml = mytpl.evaluate(defaultAttributes);
	var targetul = 'form_list';
	var my_li = document.createElement('li');
	my_li.innerHTML = mytplHtml;
	$(targetul).appendChild(my_li);
	initFormElementSort('');
	
	/*
	// Insert new rox
	var targetul = 'form_list';
	var my_li = document.createElement('li');
	my_li.id = 'element_'+getUniqueId();
	$(targetul).appendChild(my_li);
	$('element_'+element_id).update(getElementRow(element_type_titre, element_id, rubrique_id));*/
	
	
};

var getCmsPreview = function(rubrique_id, element_id) {
	if (elementsEditChange) {
		if (!window.confirm('Etes vous s&ucirc;r de vouloir perdre les modifications sur l\'&eacute;l&eacute;ment que vous êtes en train d\'&eacute;diter ?')) return;
		else elementsEditChange = false;
	}
	var targetid = 'cms_element_pane';
	var targetFile = 'cms_elements_preview.php';
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'id='+rubrique_id+'&element_id='+element_id,
		evalScripts: true,
		onSuccess: function(requete) {
			$(targetid).update(requete.responseText);
			new Effect.ScrollTo('cms_element_pane', {offset: -20});
		}
	});
};

var saveElementListOrder = function() {
	var element_ids_order = $F('element_serialize');
	var targetFile = 'cms_element_ajax_action.php';
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'action=order_element&'+element_ids_order,
		evalScripts: true,
		onSuccess: function(requete) {
			elementsListeChange = false;
	 		$('iframe_element').src = $('iframe_element').src;
		}
	});
};

var buildElementPage = function(rubrique_id) { //build_page
	if (elementsEditChange) {
		if (!window.confirm('Etes vous s&ucirc;r de vouloir perdre les modifications sur l\'&eacute;l&eacute;ment que vous êtes en train d\'&eacute;diter ?')) return;
		else elementsEditChange = false;
	}
	var element_ids_order = $F('element_serialize');
	var targetFile = 'cms_element_ajax_action.php';
	s++;
	stockObj[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'action=build_page&id='+rubrique_id+'&elements_liste_change='+(elementsListeChange ? '1&'+element_ids_order : '0'),
		evalScripts: true,
		onSuccess: function(requete) {
			elementsListeChange = false;
			elementsEditChange = false;
	 		$('iframe_element').src = $('iframe_element').src;
			new Effect.Highlight('ENREGISTRER_texte');
		}
	});
};