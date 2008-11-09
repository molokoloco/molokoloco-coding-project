<?

// ---------- Fill an array with all element types info -------------------------//
// $elements_type = getElementsType();
### $_SESSION[SITE_CONFIG]['elements_type'] = NULL;
function getElementsType() {
	if (!isset($_SESSION[SITE_CONFIG]['elements_type'])) {
		$Q =& new Q("SELECT id, actif, type, titre, valeurs, template FROM cms_elements_types ORDER BY type ASC, titre ASC");
		foreach($Q->V as $V)  { 
			$_SESSION[SITE_CONFIG]['elements_type'][$V['id']] = array(
				'titre'=>$V['titre'],
				'actif'=>$V['actif'],
				'type'=>$V['type'],
				'valeurs'=>$V['valeurs'],
				'template'=>$V['template'],
			);
		}
	}
	return $_SESSION[SITE_CONFIG]['elements_type'];
}


// ---------- Parse Valeur string -------------------------//
// ensemble_valeur:titre_valeur|nom_valeur|type_valeur[valeur_1/valeur_2(defaut_valeur,titre_valeur2|...;ensemble_valeur2:...
// 'item:texte|value|html,image|image|image,alignement|alignement[gauche,droite](gauche),legende|legende|textarea,auteur|auteur|text'
// Parse order ;:,|([/

function parseAbstractString($string) {
	$array_ensembe = array();
	$ensembles = explode(';', $string);
	foreach($ensembles as $k=>$ensemble) {
		list($ensemble_valeur, $valeurs) = explode(':', $ensemble);
		$array_ensembe[$k]['ensemble'] = $ensemble_valeur;
		$valeurs = explode(',', $valeurs);
		$array_ensembe[$k]['valeurs'] = array();
		foreach($valeurs as $j=>$valeur) {
			list($array_ensembe[$k]['valeurs'][$j]['titre'], $array_ensembe[$k]['valeurs'][$j]['nom'], $type) = explode('|', $valeur);
			list($type, $array_ensembe[$k]['valeurs'][$j]['defaut']) = explode('(', $type);
			list($type, $enum) = explode('[', $type);
			if (!empty($enum)) $array_ensembe[$k]['valeurs'][$j]['enum'] = explode('/', $enum);
			$array_ensembe[$k]['valeurs'][$j]['type'] = $type;
		}
	}
	return $array_ensembe;
}

// ---------- Custom fonction can be call from item -------------------------//
// item:Page*|page|func[getPage
function getPages() {
	global $S;
	if (!is_array($S->arbo)) {
		require_once('../lib/class/class_arbo.php');
		$S =& new ARBO();
		$S->fields = array('id', 'pid', 'type_id', 'titre_fr');
		$S->buildArbo();
	}
	$options = array();
	foreach($S->arbo as $rid=>$tmp) {
		$options[urlencode($S->arbo[$rid]['url'])] = $S->arbo[$rid]['titre_fr'].' (#'.$rid.')';
	}
	return $options;
}

// ---------- Build element edit form row -------------------------//
function getInputTr($i, $element_item, &$array_valeurs, $multiple='') {
	global $root,$rep;
	// require_once('../dat_bibliotheque_images/data.php');
	// $fileDir = $root.$R2['rep'];
	$fileDir = $root.$rep.'bibliotheque/';
	
	$inputTr = '';
	$input  = '';
	$jsVerif = '';
	$jsSubmitAdd = '';
	$jsAdd = '';
	
	$makeOneJsTpl = 0; // Create one JS template for the ensemble

	$name_suff = ( $multiple === '' ? '' : '[]' ); // Array for input name
	
	static $multiple_ex = '';
	
	if ($element_item['type'] != 'html') { // 2 cols pour inputs (sauf Wysiwyg)
		$inputTr .= '<tr>
		<td width="130" valign="top" align="right" class="table-entete'.($i%2 == 0 ? '1' : '2').'">';
		
		static $makeBtDrag = '';
		if (($multiple === 0 || $multiple > 0) && ($multiple_ex != $multiple || $makeBtDrag == '')) { // 1 seul bouton Drag par ensemble
			$makeBtDrag = 1;
			$inputTr .= '<img class="move" width="30" height="22" border="0" align="absmiddle" title="Déplacer" src="../images/drag.gif"/>';
			// <img class="delete" width="13" height="13" border="0" align="absmiddle" title="Effacer" src="../images/delete.gif"/>
		}
		
		$inputTr .= ucfirst(htmlentities(aff($element_item['titre']))).'&nbsp;:</td>
		<td class="table-ligne'.($i%2 == 0 ? '1' : '2').' comment">';
		
		if ($multiple === 0) {
			$jsAdd .= '
				tpl_add += \'<tr>\';
				tpl_add += \'<td width="130" valign="top" align="right" class="table-entete'.($i%2 == 0 ? '1' : '2').'">\';
			';
			static $makeBtJsDrag = '';
			if ($makeBtJsDrag == '') {
				$makeBtJsDrag = 1;
				$jsAdd .= '
					tpl_add += \'<img class="move" width="30" height="22" border="0" align="absmiddle" title="Déplacer" src="../images/drag.gif"/>\';
					//tpl_add += \'<img class="delete" width="13" height="13" border="0" align="absmiddle" title="Effacer" src="../images/delete.gif"/>\';
				';
			}
			$jsAdd .= '
				tpl_add += \''.ucfirst(htmlentities(aff($element_item['titre']))).'&nbsp;:</td>\';
				tpl_add += \'<td class="table-ligne'.($i%2 == 0 ? '1' : '2').' comment">\';
			';
		}
		
	}
	else { // 1 col pour le WYSIWYG
		$inputTr .= '<tr>
		<td class="table-entete'.($i%2 == 0 ? '1' : '2').'" colspan="2"><div style="width:130px; height:20px;text-align:right;">'.ucfirst(htmlentities(aff($element_item['titre']))).'&nbsp;:</div>';
	}

	switch($element_item['type']) {
		
		case 'num' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];
			
			$input  = '<input type="text" name="'.$element_item['nom'].$name_suff.'" value="'.htmlentities($valeur).'" size="6" maxlength="20" onchange="if(this.value&&this.value.indexOf(\'%\')==-1)this.value=parseFloat(this.value);"/>';
			$jsVerif .= '';

			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \'<input type="text" name="'.$element_item['nom'].$name_suff.'" value="'.htmlentities($element_item['defaut']).'" size="6" maxlength="20" onchange="if(this.value&&this.value.indexOf(\'%\')==-1)this.value=parseFloat(this.value);"/>\';
				';
			}
		break;
		
		case 'date' :
			if ($multiple === '') {
				if (empty($array_valeurs[$element_item['nom']])) $valeur = getDateTime();
				else $valeur = $array_valeurs[$element_item['nom']];
			}
			else {
				if (empty($array_valeurs[$element_item['nom']][$multiple])) $valeur = getDateTime();
				else $valeur = $array_valeurs[$element_item['nom']][$multiple];
			}
			
			$input  = '<input type="text" name="'.$element_item['nom'].$name_suff.'" value="'.htmlentities($valeur).'" size="12" maxlength="12" readonly onClick="displayCalendar(this,\'yyyy/mm/dd hh:ii\',this,true,true)"/> yyyy/mm/dd hh:ii';
			$jsVerif .= $element_item['nom'].": {type:'date', alerte:'Le champ ".$element_item['titre']." est obligatoire et doit etre valide'},";
			
			if ($multiple === 0) { // TO CHECK
				$jsAdd .= '
					tpl_add += \'<input type="text" name="'.$element_item['nom'].$name_suff.'" value="'.getDateTime().'" size="12" maxlength="12" readonly onClick="displayCalendar(this,\'yyyy/mm/dd hh:ii\',this,true,true)"/> yyyy/mm/dd hh:ii\';
				';
			}
		break;
		
		case 'enum' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];
			
			$input  = '<select name="'.$element_item['nom'].$name_suff.'">';
			foreach($element_item['enum'] as $enum) $input  .= '<option value="'.htmlentities($enum).'" '.($valeur==$enum ? 'selected="selected"' : '').'>'.$enum.'</option>';
			$input  .= '</select>';
			$jsVerif .= '';
			
			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \''.$input.'\';
				';
			}
		break;
		
		case 'func' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];
			$input  = '<select name="'.$element_item['nom'].$name_suff.'">';
			$arr_rep = '';
			@eval('$arr_rep = '.$element_item['enum'][0].'();');
			foreach((array)$arr_rep as $key=>$enum) $input  .= '<option value="'.$key.'" '.($valeur==$key ? 'selected="selected"' : '').'>'.$enum.'</option>';
			$input  .= '</select>';
			$jsVerif .= '';
			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \''.$input.'\';
				';
			}
		break;	
		
		case 'textvideo':
		case 'text' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];
			
			if ($element_item['type'] == 'textvideo') $onClick = 'onClick="javascript:promptVideo(\''.$element_item['nom'].$name_suff.'\');"';
			else $onClick = '';
			
			$input  = '<input type="text" name="'.$element_item['nom'].$name_suff.'" id="'.$element_item['nom'].$name_suff.'" value="'.htmlentities($valeur).'" size="50" maxlength="250" style="width:100%;" '.$onClick.'/>';
			$jsVerif .= '';
			
			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \'<input type="text" name="'.$element_item['nom'].$name_suff.'" value="'.htmlentities($element_item['defaut']).'" size="50" maxlength="250" style="width:100%;" '.$onClick.'/>\';
				';
			}
		break;
		
		case 'textarea' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];

			$input  = '<textarea name="'.$element_item['nom'].$name_suff.'" rows="10" cols="60" style="width:100%;white-space:normal;" onfocus="new ResizingTextArea(this);">'.$valeur.'</textarea>';
			$jsVerif .= '';
			
			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \'<textarea name="'.$element_item['nom'].$name_suff.'" rows="10" cols="60" style="width:100%;white-space:normal;" onfocus="new ResizingTextArea(this);">'.$element_item['defaut'].'</textarea>\';
				';
			}
		break;
		
		case 'html' :
			if ($multiple === '') $valeur = !empty($array_valeurs[$element_item['nom']]) ? $array_valeurs[$element_item['nom']] : $element_item['defaut'];
			else $valeur = !empty($array_valeurs[$element_item['nom']][$multiple]) ? $array_valeurs[$element_item['nom']][$multiple] : $element_item['defaut'];
			
			global $TinyMceEditorDone;
			$TinyMceEditorDone = 1; // Don't init JS in class TINYMCE, already done in cms_edit.php..., can't load JS src file by ajax

			$Ed = 'Ed'.$i;
			$$Ed = new TinyMce($element_item['nom'].$name_suff);
			
			$$Ed->width = '100%';
			$$Ed->height = '360';
			$$Ed->ToolbarSet = 'BasicStyle'; // BasicFormat
			$$Ed->Value = str_replace('&quot;','"',aff($valeur));

			$input = $$Ed->Create();
			
			$jsVerif .= '';
			$jsSubmitAdd .= 'tinyMCE.triggerSave();'; // Makes a cleanup and moves the contents from the editor to the form field
			//$jsAdd .= '';
		break;
		
		case 'image' :
			if ($multiple === '') {
				$nom = fetchValues('image', 'dat_bibliotheque_images', 'id', $array_valeurs[$element_item['nom']]);
				$valeur = array(
					'id' => 		$array_valeurs[$element_item['nom']],
					'image' => 		$nom,
					'taille' => 	$array_valeurs[$element_item['nom'].'_taille'],
					'popup' => 		$array_valeurs[$element_item['nom'].'_popup'],
					'legende' => 	$array_valeurs[$element_item['nom'].'_legende'],
					'credits' => 	$array_valeurs[$element_item['nom'].'_credits'],
					'auteur' => 	$array_valeurs[$element_item['nom'].'_auteur'],
					'date' => 		$array_valeurs[$element_item['nom'].'_date']
				);
			}
			else {
				$nom = fetchValues('image', 'dat_bibliotheque_images', 'id', $array_valeurs[$element_item['nom']][$multiple]);
				$valeur = array(
					'id' => 		$array_valeurs[$element_item['nom']][$multiple],
					'image' => 		$nom,
					'taille' => 	$array_valeurs[$element_item['nom'].'_taille'][$multiple],
					'popup' => 		$array_valeurs[$element_item['nom'].'_popup'][$multiple],
					'legende' => 	$array_valeurs[$element_item['nom'].'_legende'][$multiple],
					'credits' => 	$array_valeurs[$element_item['nom'].'_credits'][$multiple],
					'auteur' => 	$array_valeurs[$element_item['nom'].'_auteur'][$multiple],
					'date' => 		$array_valeurs[$element_item['nom'].'_date'][$multiple]
				);
			}
			$input_id = generateId($element_item['nom']);
	
			$input  = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			
			<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="texte">
			<tr>
			<td width="80" align="right">Taille :&nbsp;</td>
			<td><select name="'.$element_item['nom'].'_taille'.$name_suff.'">
			<option value="mini" '.($valeur['taille'] == 'mini' ? 'selected="selected"' : '').'>Petite</option>
			<option value="medium" '.($valeur['taille'] == 'medium' ? 'selected="selected"' : '').'>Moyenne</option>
			<option value="grand" '.($valeur['taille'] == 'grand' || $valeur['taille'] == '' ? 'selected="selected"' : '').'>Grande</option>
			</select></td>
			</tr>
			<tr>
			<td align="right">Popup :&nbsp;</td>
			<td><input name="'.$element_item['nom'].'_popup'.$name_suff.'" class="radio" value="1" type="radio" '.($valeur['popup'] == 1 ? 'checked="checked"' : '').'>&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_popup'.$name_suff.'" class="radio" value="0" type="radio" '.($valeur['popup'] != 1 ? 'checked="checked"' : '').'>&nbsp;Non</td>
			</tr>
			<tr>
			<td align="right">L&eacute;gende :&nbsp;</td>
			<td><input name="'.$element_item['nom'].'_legende'.$name_suff.'" class="radio" value="1" type="radio" '.($valeur['legende'] == 1 ? 'checked="checked"' : '').'>&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_legende'.$name_suff.'" class="radio" value="0" type="radio" '.($valeur['legende'] != 1 ? 'checked="checked"' : '').'>&nbsp;Non</td>
			</tr>
			<tr>
			<td align="right">Cr&eacute;dits :&nbsp;</td>
			<td><input name="'.$element_item['nom'].'_credits'.$name_suff.'" class="radio" value="1" type="radio" '.($valeur['credits'] == 1 ? 'checked="checked"' : '').'>&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_credits'.$name_suff.'" class="radio" value="0" type="radio" '.($valeur['credits'] != 1 ? 'checked="checked"' : '').'>&nbsp;Non</td>
			</tr>
			<tr>
			<td align="right">Auteur :&nbsp;</td>
			<td><input name="'.$element_item['nom'].'_auteur'.$name_suff.'" class="radio" value="1" type="radio" '.($valeur['auteur'] == 1 ? 'checked="checked"' : '').'>&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_auteur'.$name_suff.'" class="radio" value="0" type="radio" '.($valeur['auteur'] != 1 ? 'checked="checked"' : '').'>&nbsp;Non</td>
			</tr>
			<tr>
			<td align="right">Date :&nbsp;</td>
			<td><input name="'.$element_item['nom'].'_date'.$name_suff.'" class="radio" value="1" type="radio" '.($valeur['date'] == 1 ? 'checked="checked"' : '').'>&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_date'.$name_suff.'" class="radio" value="0" type="radio" '.($valeur['date'] != 1 ? 'checked="checked"' : '').'>&nbsp;Non</td>
			</tr>
			</table></td>
			
			<td align="center" valign="top">
			<div style="margin:10px;" id="'.$input_id.'_img">';
			if ($valeur['image'] == '') $input .= '<img src="../images/nothumfla.png" class="bor1" border="0" />';
			else $input .= '<a href="javascript:void(0);" onclick="popImg(\''.$fileDir.'grand/'.$valeur['image'].'\');"><img src="'.$fileDir.'mini/'.$valeur['image'].'" alt="" title="Agrandir l\'image" border="0" class="bor1"></a>';
			$input  .= '</div>
			<a href="../dat_bibliotheque_images/pop.php?input='.$input_id.'" onClick="return openWindow(this, {width:640,height:480,center:true});"><img src="../images/parcourir.png" width="23" height="21" style="vertical-align:middle;" border="0" /></a><input type="hidden" name="'.$element_item['nom'].$name_suff.'" id="'.$input_id.'" value="'.$valeur['id'].'"/>
			</td>
			
			</tr>
			</table>';
			
			if ($multiple === 0) { // TODO TEMPLATE IMAGE LIKE TEMPLATE FICHIER
				$jsAdd .= '
					tpl_add += \'<table width="100%" border="0" cellspacing="0" cellpadding="0">\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" class="texte">\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td width="80" align="right">Taille :&nbsp;</td>\';
					tpl_add += \'<td><select name="'.$element_item['nom'].'_taille'.$name_suff.'">\';
					tpl_add += \'<option value="mini">Petite</option>\';
					tpl_add += \'<option value="medium">Moyenne</option>\';
					tpl_add += \'<option value="grand">Grande</option>\';
					tpl_add += \'</select></td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td align="right">Popup :&nbsp;</td>\';
					tpl_add += \'<td><input name="'.$element_item['nom'].'_popup'.$name_suff.'" class="radio" value="1" type="radio">&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_popup'.$name_suff.'" class="radio" value="0" type="radio">&nbsp;Non</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td align="right">L&eacute;gende :&nbsp;</td>\';
					tpl_add += \'<td><input name="'.$element_item['nom'].'_legende'.$name_suff.'" class="radio" value="1" type="radio">&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_legende'.$name_suff.'" class="radio" value="0" type="radio">&nbsp;Non</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td align="right">Cr&eacute;dits :&nbsp;</td>\';
					tpl_add += \'<td><input name="'.$element_item['nom'].'_credits'.$name_suff.'" class="radio" value="1" type="radio">&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_credits'.$name_suff.'" class="radio" value="0" type="radio">&nbsp;Non</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td align="right">Auteur :&nbsp;</td>\';
					tpl_add += \'<td><input name="'.$element_item['nom'].'_auteur'.$name_suff.'" class="radio" value="1" type="radio">&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_auteur'.$name_suff.'" class="radio" value="0" type="radio">&nbsp;Non</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td align="right">Date :&nbsp;</td>\';
					tpl_add += \'<td><input name="'.$element_item['nom'].'_date'.$name_suff.'" class="radio" value="1" type="radio">&nbsp;Oui&nbsp;<input name="'.$element_item['nom'].'_date'.$name_suff.'" class="radio" value="0" type="radio">&nbsp;Non</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'</table></td>\';
					tpl_add += \'<td align="center" valign="top">\';
					tpl_add += \'<div style="margin:10px;" id="#{input_id}_img"><img src="../images/nothumfla.png" class="bor1" border="0" /></div>\';
					tpl_add += \'<a href="../dat_bibliotheque_images/pop.php?input=#{input_id}" onClick="return openWindow(this, {width:640,height:480,center:true});"><img src="../images/parcourir.png" width="23" height="21" style="vertical-align:middle;" border="0" /></a><input type="hidden" name="'.$element_item['nom'].$name_suff.'" id="#{input_id}" value=""/>\';
					tpl_add += \'</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'</table>\';
				';
			}
			
			$jsVerif .= '';
		break;
		
		case 'fichier' :

			if ($multiple === '') {
				$nom = fetchValues('fichier', 'dat_bibliotheque_fichiers', 'id', $array_valeurs[$element_item['nom']]);
				$valeur = array(
					'id' => 		$array_valeurs[$element_item['nom']],
					'fichier' => 	$nom,
					'titre' => 		$array_valeurs[$element_item['nom'].'_titre'],
					'date' => 		$array_valeurs[$element_item['nom'].'_date']
				);
			}
			else {
				$nom = fetchValues('fichier', 'dat_bibliotheque_fichiers', 'id', $array_valeurs[$element_item['nom']][$multiple]);
				$valeur = array(
					'id' => 		$array_valeurs[$element_item['nom']][$multiple],
					'fichier' => 	$nom,
					'titre' => 		$array_valeurs[$element_item['nom'].'_titre'][$multiple],
					'date' => 		$array_valeurs[$element_item['nom'].'_date'][$multiple]
				);
			}
				
			$input_id = generateId($element_item['nom']);
			
			$input  = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td valign="top">
			<a href="../dat_bibliotheque_fichiers/pop.php?input='.$input_id.'" onClick="return openWindow(this, {width:640,height:480,center:true});"><img src="../images/parcourir.png" width="23" height="21" style="vertical-align:middle;" border="0" /></a><input type="hidden" name="'.$element_item['nom'].$name_suff.'" id="'.$input_id.'" value="'.$valeur['id'].'"/>
			<div style="margin:10px; display:inline;" id="'.$input_id.'_doc" class="texte">';
			if ($valeur['fichier'] == '') $input .= '[Vide]';
			else $input .= '<a href="'.$fileDir.$valeur['fichier'].'" target="_blank">'.$valeur['fichier'].'</a>';
			$input  .= '</div>
			</td>
			</tr>
			</table>';

			if ($multiple === 0) {
				$jsAdd .= '
					tpl_add += \'<table width="100%" border="0" cellspacing="0" cellpadding="0">\';
					tpl_add += \'<tr>\';
					tpl_add += \'<td valign="top">\';
					tpl_add += \'<a href="../dat_bibliotheque_fichiers/pop.php?input=#{input_id}" onClick="return openWindow(this, {width:640,height:480,center:true});"><img src="../images/parcourir.png" width="23" height="21" style="vertical-align:middle;" border="0" /></a><input type="hidden" name="'.$element_item['nom'].$name_suff.'" id="#{input_id}" value=""/>\';
					tpl_add += \'<div style="margin:10px; display:inline;" id="#{input_id}_doc" class="texte">[Vide]</div>\';
					tpl_add += \'</td>\';
					tpl_add += \'</tr>\';
					tpl_add += \'</table>\';
				';
			}
		break;
	}
	
	$inputTr .= $input;
	
	$inputTr .= '</td>
	</tr>';
	
	if ($multiple === 0) {
		$jsAdd .= '
			tpl_add += \'</td>\';
			tpl_add += \'</tr>\';
		';
	}
	
	$multiple_ex = $multiple;

	return array(
		'tr'=>			$inputTr,
		'jsVerif'=>		$jsVerif,
		'jsSubmitAdd'=>	$jsSubmitAdd,
		'jsAdd'=>		$jsAdd
	);
}


// ---------- SERIALISATION Valeur array to string -------------------------//
function cleanSerial($var=array(), $recurs=FALSE) {
	if ($recurs) {
		foreach ((array)$var as $k=>$v) {
			if (is_array($v)) $var[$k] = cleanSerial($v, 1);
			else $var[$k] = base64_encode($v);
		}
		return $var;
	}
	else return serialize(cleanSerial($var, 1));
}

function cleanUnserial($var=array(), $recurs=FALSE) {
	if ($recurs) {
		foreach ((array)$var as $k=>$v ) {
			if (is_array($v)) $var[$k] = cleanUnserial($v, 1);
			else $var[$k] = base64_decode($v);
		}
		return $var;
	}
	else return cleanUnserial(unserialize($var), 1);
}

// ---------- Error handling -------------------------//
$errors = array();
function error_hndl($errno, $errstr) {
    global $errors;
    $errors[] = $errno.' : '.$errstr;
}

// ---------- CREATE HTML FROM serial values :) -------------------------//
function getCmsHtml($rubrique_id=0, $element_id=0, $element_langue='') {

	global $WWW, $wwwRoot, $selfDir, $rep, $langues;
	if (empty($element_langue)) $element_langue = $langues[0];

	if ($element_id > 0) $E =& new Q("SELECT * FROM cms_pages_elements WHERE id='$element_id' AND langue='$element_langue' LIMIT 1");
	else if ($rubrique_id > 0) $E =& new Q("SELECT * FROM cms_pages_elements WHERE pid='$rubrique_id' AND langue='$element_langue' ORDER BY ordre ASC");
	else return '';
	
	$elements_type = getElementsType();
	### db($elements_type);

	$elements_html = '';
	$fileDir = $rep.'bibliotheque/';
	
	// Valeurs à protéger car fonction SANITIZE() sur tous les INPUTS mais valeur permise dans les templates
	$arr_replace = array(
		'#script#'=> '<script type="text/javascript">',
		'#/script#'=> '</script>',
		'#iframe#'=> '<iframe',
		'#/iframe#'=> '</iframe>',
		'#object#'=> '<object',
		'#/object#'=> '</object>',
		'#embed#'=> '<embed',
		'#/embed#'=> '</embed>',
	);
					
	$errors = array();
	$eval_errors_type_id = false;
    $orig_hndl = set_error_handler('error_hndl');
	
	foreach((array)$E->V as $V) { // Pour chaque Element
		
		if ($element_id < 1 && $rubrique_id > 0 && $V['actif'] == 0) continue; // N'affiche pas les element inactif en mode page
		
		$array_ensembles = parseAbstractString($elements_type[$V['type_id']]['valeurs']);
		### db($array_ensembles ,'$array_ensembles');
		
		$array_valeurs = cleanUnserial($V['valeurs']);
		### db($array_valeurs ,'$array_valeurs');
		
		$arr_template = $elements_type[$V['type_id']]['template'];
		### db('$arr_template :', $arr_template);
		
		
		$empty = false; // Si fichier ou images absente n'affiche pas l'element
		$elements_empty = '';
		
		foreach((array)$array_ensembles as $i=>$array_ensemble) { // Ensembles de valeurs d'element
		
			if ($array_ensemble['ensemble'] == 'item') {
				
				### db($array_ensemble['valeurs']);
				
				foreach((array)$array_ensemble['valeurs'] as $i=>$element_item) { // Valeur d'un ensemble
					
					$unique_id = generateId('element');
					
					$required = substr($element_item['titre'], -1);
					if ($empty != true) $empty = (empty($array_valeurs[$element_item['nom']]) && $required == '*' ? true : false ); // Si 1 seul element requis est vide, empty = true

					if (empty($array_valeurs[$element_item['nom']]) && $required == '*' && strpos($selfDir,'/admin/') !== false) {
						$elements_empty .= '<br /><h2 align="center">ATTENTION &eacute;l&eacute;ment manquant : '.htmlentities($element_item['titre']).'</h2><br />';
					}
					
					switch($element_item['type']) {
						
						case 'image' :

							// Attention : $array_valeurs[$element_item['nom']] stock l'ID de l'image
							$array_valeurs[$element_item['nom']] = fetchValues('image', 'dat_bibliotheque_images', 'id', $array_valeurs[$element_item['nom']]);
							
							$elementName = $element_item['nom'];
							$$elementName = $array_valeurs[$element_item['nom']];
							
							$elementNameSrc = $element_item['nom'].'_src';
							$$elementNameSrc = $WWW.$fileDir.$array_valeurs[$element_item['nom'].'_taille'].'/'.$array_valeurs[$element_item['nom']];
							
							$elementNameGde = $element_item['nom'].'_grande';
							$$elementNameGde = $WWW.$fileDir.'pop/'.$array_valeurs[$element_item['nom']];
							
							$elementNameTitre = $element_item['nom'].'_titre';
							$$elementNameTitre = affCleanName($array_valeurs[$element_item['nom']], 1);
							
							$elementNameLegende = $element_item['nom'].'_legende';
							if ($array_valeurs[$element_item['nom'].'_legende'] == 1)
								$$elementNameLegende = fetchValues('legende_'.$element_langue, 'dat_bibliotheque_images', 'image', $array_valeurs[$element_item['nom']]);
							else $$elementNameLegende = '';
							
							$elementNamePopup = $element_item['nom'].'_popup';
							if ($array_valeurs[$element_item['nom'].'_popup'] == 1) $$elementNamePopup = 1;
							else $$elementNamePopup = '';

							$elementNameCredits = $element_item['nom'].'_credits';
							if ($array_valeurs[$element_item['nom'].'_credits'] == 1)
								$$elementNameCredits = fetchValues('credits_'.$element_langue, 'dat_bibliotheque_images', 'image', $array_valeurs[$element_item['nom']]);
							else $$elementNameCredits = '';
							
							$elementNameAuteur = $element_item['nom'].'_auteur';
							if ($array_valeurs[$element_item['nom'].'_auteur'] == 1)
								$$elementNameAuteur = fetchValues('auteur', 'dat_bibliotheque_images', 'image', $array_valeurs[$element_item['nom']]);
							else $$elementNameAuteur = '';
							
							$elementNameDate = $element_item['nom'].'_date';
							if ($array_valeurs[$element_item['nom'].'_date'] == 1)
								$$elementNameDate = fetchValues('date', 'dat_bibliotheque_images', 'image', $array_valeurs[$element_item['nom']]);
							else $$elementNameDate = '';
						break;
						
						case 'fichier' :

							// Attention : $array_valeurs[$element_item['nom']] stock l'ID du fichier
							$array_valeurs[$element_item['nom']] = fetchValues('fichier', 'dat_bibliotheque_fichiers', 'id', $array_valeurs[$element_item['nom']]);
							
							$elementName = $element_item['nom'];
							$$elementName = $array_valeurs[$element_item['nom']];
							
							$elementNameSrc = $element_item['nom'].'_src';
							$$elementNameSrc = $WWW.$fileDir.$array_valeurs[$element_item['nom']];
							
							$elementNameTitre = $element_item['nom'].'_titre';
							$$elementNameTitre = fetchValues('titre_'.$element_langue, 'dat_bibliotheque_fichiers', 'fichier', $array_valeurs[$element_item['nom']]);
							if (empty($$elementNameTitre)) $$elementNameTitre = affCleanName($array_valeurs[$element_item['nom']], 1);
							
							$elementNameExt = $element_item['nom'].'_ext';
							$$elementNameExt = getExt($array_valeurs[$element_item['nom']]);
							
							$elementNameSize = $element_item['nom'].'_size';
							$$elementNameSize =  cleanKo(filesize($wwwRoot.$fileDir.$array_valeurs[$element_item['nom']]));
						break;
						
						case 'textarea' :
							$$element_item['nom'] = ($element_item['titre'] == 'Code' ? $array_valeurs[$element_item['nom']] : nl2br($array_valeurs[$element_item['nom']]));
						break;
						
						default :
							if ($element_item['type'] == 'text') $$element_item['nom'] = htmlentities($array_valeurs[$element_item['nom']]);
							else $$element_item['nom'] = $array_valeurs[$element_item['nom']];
						break;
					}

				}

				if (!$empty && !empty($elements_type[$V['type_id']]['valeurs'])) {	
					$arr_template = stripslashes($arr_template);
					$arr_template = str_replace(array_keys($arr_replace), array_values($arr_replace), $arr_template);
					if (@eval('$html = \''.$arr_template.'\';') === false) $eval_errors_type_id = $V['type_id'];
					else $elements_html .= "\n".$html; ### db($html);
				}

			}
			else {
				### db($array_ensemble['valeurs']);
				foreach((array)$array_ensemble['valeurs'] as $i=>$element_item) { // Valeur d'un ensemble
					
					$unique_id = generateId('element');
					$required = substr($element_item['titre'], -1);

					switch($element_item['type']) {
						
						case 'image' :
							$arr_tmp = array();
							foreach((array)$array_valeurs[$element_item['nom']] as $key=>$val) {
								if ($empty != true) $empty = (empty($val) && $required == '*' ? true : false ); // Si 1 seul element requis est vide, empty = true
								if (empty($val) && $required == '*' && strpos($selfDir,'/admin/') !== false) {
									$elements_empty .= '<br /><h2 align="center">ATTENTION &eacute;l&eacute;ment manquant : '.htmlentities($element_item['titre']).'</h2><br />';
								}
								// Attention : $array_valeurs[$element_item['nom']] stock l'ID du fichier
								$arr_tmp[$key][$element_item['nom']] = fetchValues('image', 'dat_bibliotheque_images', 'id', $val);
								if (!@is_file($wwwRoot.$fileDir.$arr_tmp[$key][$element_item['nom']])) continue;
								
								$arr_tmp[$key][$element_item['nom'].'_src'] = $WWW.$fileDir.$array_valeurs[$element_item['nom'].'_taille'][$key].'/'.$arr_tmp[$key][$element_item['nom']];
								$arr_tmp[$key][$element_item['nom'].'_grande'] = $WWW.$fileDir.'pop/'.$arr_tmp[$key][$element_item['nom']];
								$arr_tmp[$key][$element_item['nom'].'_titre'] = affCleanName($arr_tmp[$key][$element_item['nom']], 1);

								if ($array_valeurs[$element_item['nom'].'_legende'][$key] == 1)
									$arr_tmp[$key][$element_item['nom'].'_legende'] = htmlentities(fetchValues('legende_'.$element_langue, 'dat_bibliotheque_images', 'id', $val));
								else $arr_tmp[$key][$element_item['nom'].'_legende'] = '';
								
								if ($array_valeurs[$element_item['nom'].'_popup'][$key] == 1) $popup = 1;
								else $popup = '';		
								
								if ($array_valeurs[$element_item['nom'].'_credits'][$key] == 1)
									$arr_tmp[$key][$element_item['nom'].'_credits'] = htmlentities(fetchValues('credits_'.$element_langue, 'dat_bibliotheque_images', 'id', $val));
								else $arr_tmp[$key][$element_item['nom'].'_credits'] = '';
								
								if ($array_valeurs[$element_item['nom'].'_auteur'][$key] == 1)
									$arr_tmp[$key][$element_item['nom'].'_auteur'] = htmlentities(fetchValues('auteur', 'dat_bibliotheque_images', 'id', $val));
								else $arr_tmp[$key][$element_item['nom'].'_auteur'] = '';
								
								if ($array_valeurs[$element_item['nom'].'_date'][$key] == 1)
									$arr_tmp[$key][$element_item['nom'].'_date'] = rDate(fetchValues('date', 'dat_bibliotheque_images', 'id', $val));
								else $arr_tmp[$key][$element_item['nom'].'_date'] = '';
							}
							
							$valeur_name = $element_item['nom'].'s';
							$$valeur_name = $arr_tmp;
							
						break;

						case 'fichier' :
							$arr_tmp = array();
							foreach((array)$array_valeurs[$element_item['nom']] as $key=>$val) {
								if ($empty != true) $empty = (empty($val) && $required == '*' ? true : false ); // Si 1 seul element requis est vide, empty = true
								if (empty($val) && $required == '*' && strpos($selfDir,'/admin/') !== false) {
									$elements_empty .= '<br /><h2 align="center">ATTENTION &eacute;l&eacute;ment manquant : '.htmlentities($element_item['titre']).'</h2><br />';
								}
								// Attention : $array_valeurs[$element_item['nom']] stock l'ID du fichier
								$arr_tmp[$key][$element_item['nom']] = fetchValues('fichier', 'dat_bibliotheque_fichiers', 'id', $val);
								if (!@is_file($wwwRoot.$fileDir.$arr_tmp[$key][$element_item['nom']])) continue;
								
								$arr_tmp[$key][$element_item['nom'].'_src'] = $WWW.$fileDir.$arr_tmp[$key][$element_item['nom']];
								$arr_tmp[$key][$element_item['nom'].'_titre'] = htmlentities(fetchValues('titre_'.$element_langue, 'dat_bibliotheque_fichiers', 'id', $val));
								$arr_tmp[$key][$element_item['nom'].'_ext'] = getExt($arr_tmp[$key][$element_item['nom']]);
								$arr_tmp[$key][$element_item['nom'].'_size'] = cleanKo(filesize($wwwRoot.$fileDir.$arr_tmp[$key][$element_item['nom']]));
							}
							
							$valeur_name = $element_item['nom'].'s';
							$$valeur_name = $arr_tmp;
						break;
						
						case 'textarea' :
							$arr_tmp = array();
							foreach((array)$array_valeurs[$element_item['nom']] as $key=>$val) {
								if ($empty != true) $empty = (empty($val) && $required == '*' ? true : false ); // Si 1 seul element requis est vide, empty = true
								if (empty($val) && strpos($selfDir,'/admin/') !== false) {
									$elements_empty .= '<br /><h2 align="center">ATTENTION &eacute;l&eacute;ment manquant : '.htmlentities($element_item['titre']).'</h2><br />';
								}
								$arr_tmp[$key][$element_item['nom']] = ($element_item['titre'] == 'Code' ? $val : nl2br($val));
							}
							
							$valeur_name = $element_item['nom'].'s';
							$$valeur_name = $arr_tmp;
						break;
						
						default :
							$arr_tmp = array();
							foreach((array)$array_valeurs[$element_item['nom']] as $key=>$val) {
								if ($empty != true) $empty = (empty($val) && $required == '*' ? true : false ); // Si 1 seul element requis est vide, empty = true
								if (empty($val) && $required == '*' && strpos($selfDir,'/admin/') !== false) {
									$elements_empty .= '<br /><h2 align="center">ATTENTION &eacute;l&eacute;ment manquant : '.htmlentities($element_item['titre']).'</h2><br />';
								}
								$arr_tmp[$key][$element_item['nom']] = $val;
							}

							$valeur_name = $element_item['nom'].'s';

							if ($element_item['type'] == 'text') $$valeur_name = html_array($arr_tmp);
							else $$valeur_name = $arr_tmp;
						break;
					}
				}

				if (!$empty || empty($elements_type[$V['type_id']]['valeurs'])) {
					$arr_template = stripslashes($arr_template);
					$arr_template = str_replace(array_keys($arr_replace), array_values($arr_replace), $arr_template);
					
					if (@eval('$html = \''.$arr_template.'\';') === false) $eval_errors_type_id = $V['type_id'];
					else $elements_html .= "\n".$html; ### db($html);
				}
			}
		}
	}
	restore_error_handler();
	
	$html = '';
	
	if (!empty($elements_empty)) $html .= $elements_empty;

	if ($eval_errors_type_id) {
		global $errors;
		$html .= '<p align="center"><br /><strong>Error while evaluating check your &eacute;l&eacute;ment type (ID '.$eval_errors_type_id.')</strong><br />&nbsp;</p>';
		$html .= getDb($errors, 'Php error');
		$html .= getDb(stripslashes($arr_template), 'Template code');
		$html .= getDb(getVars(get_defined_vars()));
	}
	else if (!empty($elements_html)) {
		
		//if (strpos($selfDir,'/admin/') !== false)
		//$elements_html = str_replace('class="lightwindow"','class="lightwindow_iframe_link"', $elements_html);
		//db($elements_html);
		
		$html .= '<div id="cms">';
		$html .= $elements_html;
		$html .= '</div>';
	}

	return $html;
}
?>