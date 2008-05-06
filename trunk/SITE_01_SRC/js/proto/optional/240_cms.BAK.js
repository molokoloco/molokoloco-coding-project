/*///////////////////////////////////////////////////////////////////////////////////////////////////////
///// Code mixing by Molokoloco for Agence Clark... [BETA TESTING FOR EVER] ........... (o_O)  /////////
///////////////////////////////////////////////////////////////////////////////////////////////////////

	TO USE IN VIRTUAL ADMIN ONLY : IT'S NOT FOR THE FRONT FOR THE MOMENT

*/
var elementsListeChange = false;
var inprogress = 'inprogress'; // update some action info

var stockAjax = {};
var s = 0;

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
			if ($('changements').style.display=='none') { 
				Effect.Appear('changements');
				Effect.Pulsate('changements');
			}
			$('element_serialize').value = Sortable.serialize('element_list', {name:'element_id'});
		}
	});
	$('element_serialize').value = Sortable.serialize('element_list', {name:'element_id'});
}
Event.observe(window, 'load', initElementSort, false);


var getElementRow = function(element_type_titre, element_id, rubrique_id) {
	var elementRow = '';
	elementRow += '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #fff;">';
	elementRow += '<tr  id="tr_element_'+element_id+'" class="table-ligne1" onmouseover="this.style.backgroundColor=\'#FFFFFF\';" onmouseout="this.style.backgroundColor=\'\';">';

	elementRow += '<td class="texte" style="border-left:1px solid #fff;"><img src="../images/drag.png" width="30" height="22" align="absmiddle" border="0" title="D&eacute;placer" class="move" /><a href="javascript:editElement('+element_id+','+rubrique_id+');" title="Editer l\'&eacute;l&eacute;ment">'+element_type_titre+'</a></td>';
	elementRow += '<td width="50" align="center" class="texte" style="border-left:1px solid #fff;"><input type="checkbox" name="actif[]" onfocus="setActif(this.checked,\''+element_id+'\');" class="radio" /></td>';
	elementRow += '<td width="50" align="center" style="border-left:1px solid #fff;"><input name="eff[]" type="checkbox" class="radio" value="'+element_id+'" /></td>';
	
	elementRow += '</tr>';
	elementRow += '</table>';
	return elementRow;
};


var addElement = function(element_type_titre, element_type_id, rubrique_id, element_langue) {
	// create_next_element
	var targetFile = 'cms_element_ajax_action.php';
	s++;
	stockAjax[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'action=create_next_element&rubrique_id='+rubrique_id+'&element_type_id='+element_type_id+'&element_langue='+element_langue,
		evalScripts: true,
		onSuccess: function(requete) {
			
			var targetPane = 'element_list_view';
			if ($(targetPane).style.display=='none') Effect.Appear(targetPane);
			
			var element_id = parseInt(requete.responseText);
			// Insert new rox
			var targetul = 'element_list';
			var my_li = document.createElement('li');
			my_li.setAttribute('id','element_'+element_id);
			my_li.update(getElementRow(element_type_titre, element_id, rubrique_id));
			$(targetul).appendChild(my_li);
			initElementSort('');
			
			elementsListeChange = true;
			new Effect.ScrollTo('element_'+element_id, {offset: -400, afterFinish:function() {new Effect.Highlight('tr_element_'+element_id);}});
			
		}
	});
};

var effElement = function() {
	$$('#element_list  input.effelementcheck').each(function(e) {
		if ($(e).checked) {
			var element_id = e.getAttribute('value');
			var targetFile = 'cms_element_ajax_action.php';
			s++;
			stockAjax[s] = new Ajax.Request(targetFile, {
				method: 'get',
				parameters: 'action=efface_element&element_id='+element_id,
				evalScripts: true,
				onSuccess: function(requete) {
					Effect.Fade('element_'+element_id, {afterFinish:function(effect){Element.remove(effect.element);}});
				}
			});
		}
	});
}

var setActif = function(bool, element_id) {
	var targetFile = 'cms_element_ajax_action.php';
	s++;
	stockAjax[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'action=set_actif&element_id='+element_id+'&bool='+(bool ? '0' : '1'),
		evalScripts: true,
		onSuccess: function(requete) {
			new Effect.Highlight('tr_element_'+element_id);
		}
	});
};

var editElement = function(element_id) {
	var targetid = 'cms_element_pane';
	var targetFile = 'cms_element_form.php';
	s++;
	stockAjax[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'element_id='+element_id,
		evalScripts: true,
		onSuccess: function(requete) {
			$(targetid).update(requete.responseText);
			new Effect.ScrollTo('element_edit_view', {offset: -20});
		}
	});
};

var getCmsPreview = function(rubrique_id) {
	var targetid = 'cms_element_pane';
	var targetFile = 'cms_elements_preview.php';
	s++;
	stockAjax[s] = new Ajax.Request(targetFile, {
		method: 'get',
		parameters: 'rubrique_id='+rubrique_id,
		evalScripts: true,
		onSuccess: function(requete) {
			$(targetid).update(requete.responseText);
			new Effect.ScrollTo('element_edit_view', {offset: -20});
		}
	});
};

function effClicTous(form, booleen) {
	form = document[form];
	for (i=0; i<form.elements.length; i++) { 
		if (form.elements[i].name.indexOf('eff') != -1) form.elements[i].checked = booleen;
	}
}