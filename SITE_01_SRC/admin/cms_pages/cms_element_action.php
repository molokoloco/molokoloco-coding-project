<? 

if (!isset($WWW)) { // Direct call ?
	require('../lib/racine.php');
	require_once('cms_fonctions.php');
	
	setIsoHeader();
	
	$rubrique_id = gpc('id');
	$element_id = gpc('element_id');
	$action = gpc('action');
	
	$elements_type = getElementsType();
	
	?><script language="javascript" src="../init.js"></script><?
}

switch($action) {
	
	case 'duplicate_element_langue' :
		
		$langue_dupli = gpc('langue_dupli');

		$E =& new Q("DELETE FROM cms_pages_elements WHERE pid='$rubrique_id' AND langue='$langue_dupli' ");
		
		$E =& new Q("SELECT * FROM cms_pages_elements WHERE pid='$rubrique_id' AND langue='$element_langue' ORDER BY ordre ASC");
		$ordre = 10;
		foreach($E->V as $V) {
			$ordre += 10;
			$actif = clean($V['actif']);
			$type_id = clean($V['type_id']);
			$valeurs = clean($V['valeurs']);
			$valeurs_keywords = clean($V['motcle']);
			$U =& new Q("
				INSERT INTO cms_pages_elements (ordre, actif, pid, type_id, langue, valeurs, motcle)
				VALUES ('$ordre', '$actif', '$rubrique_id', '$type_id', '$langue_dupli', '$valeurs', '$valeurs_keywords')
			");
			### db($U);
		}

		goto('index.php?mode=cms&id='.$rubrique_id.'&element_langue='.$langue_dupli);
	break;
	
	case 'update_element' :
		if ($element_id < 1) break;
		
		$E =& new Q("SELECT * FROM cms_pages_elements WHERE id='$element_id' LIMIT 1");

		$array_ensembles = parseAbstractString($elements_type[$E->V[0]['type_id']]['valeurs']);
		### db($array_ensembles);
		
		$duplicate = intval($_POST['duplicate']);
		$actif = intval($_POST['actif']);
		
		$serial_array = array();
		
		foreach((array)$array_ensembles as $i=>$array_ensemble) {
			if ($array_ensemble['ensemble'] == 'item') {
				foreach((array)$array_ensemble['valeurs'] as $i=>$valeurs) {
					switch($valeurs['type']) {
						case 'image' :
							$serial_array[$valeurs['nom']] = 				clean($_POST[$valeurs['nom']]);
							$serial_array[$valeurs['nom'].'_taille'] = 		clean($_POST[$valeurs['nom'].'_taille']);
							$serial_array[$valeurs['nom'].'_popup'] = 		intval($_POST[$valeurs['nom'].'_popup']);
							$serial_array[$valeurs['nom'].'_legende'] = 	intval($_POST[$valeurs['nom'].'_legende']);
							$serial_array[$valeurs['nom'].'_credits'] = 	intval($_POST[$valeurs['nom'].'_credits']);
							$serial_array[$valeurs['nom'].'_auteur'] = 		intval($_POST[$valeurs['nom'].'_auteur']);
							$serial_array[$valeurs['nom'].'_date'] = 		intval($_POST[$valeurs['nom'].'_date']);
						break;
						
						default :
							$serial_array[$valeurs['nom']] = stripslashes(clean($_POST[$valeurs['nom']]));
						break;
					}
				}
			}
			else {

				foreach((array)$array_ensemble['valeurs'] as $i=>$valeurs) {

					switch($valeurs['type']) {
						case 'image' :
							foreach($_POST[$valeurs['nom']] as $key=>$onePost) {
								if (empty($onePost)) continue;
								$serial_array[$valeurs['nom']][] = 					clean($onePost);
								$serial_array[$valeurs['nom'].'_taille'][] = 		clean($_POST[$valeurs['nom'].'_taille'][$key]);
								$serial_array[$valeurs['nom'].'_popup'][] = 		intval($_POST[$valeurs['nom'].'_popup'][$key]);
								$serial_array[$valeurs['nom'].'_legende'][] = 		intval($_POST[$valeurs['nom'].'_legende'][$key]);
								$serial_array[$valeurs['nom'].'_credits'][] = 		intval($_POST[$valeurs['nom'].'_credits'][$key]);
								$serial_array[$valeurs['nom'].'_auteur'][] = 		intval($_POST[$valeurs['nom'].'_auteur'][$key]);
								$serial_array[$valeurs['nom'].'_date'][] = 			intval($_POST[$valeurs['nom'].'_date'][$key]);
							}
							$serial_array['instance_count'][] = $key;
						break;

						default :
							foreach((array)$_POST[$valeurs['nom']] as $key=>$onePost) {
								//if (empty($onePost)) continue;
								$serial_array[$valeurs['nom']][] = stripslashes(clean($onePost));
							}
							$serial_array['instance_count'][] = $key;
						break;
					}
				}
			}
		}
		### d($serial_array);
		
		// SERIALISATION ELEMENT
		$valeurs = str_replace("'", "\\'", cleanSerial($serial_array));
		### d($valeurs, '$valeurs');
		
		// INDEXATION RECHERCHE ELEMENT
		$valeurs_keywords = '';
		foreach((array)$serial_array as $key=>$val)
			$valeurs_keywords .= str_replace("'", "\\'", str_replace(chr(13), '', str_replace(chr(10), ' ', striptags(html_entity_decode($val)))))."<br />";
		### d($valeurs_keywords, '$valeurs_keywords');

		if ($duplicate == 1) {
			$ordre = $E->V[0]['ordre'] + 5;
			$pid = $E->V[0]['pid'];
			$type_id = $E->V[0]['type_id'];
			$langue = $E->V[0]['langue'];
			$U =& new Q("
				INSERT INTO cms_pages_elements (ordre, actif, pid, type_id, langue, valeurs, motcle)
				VALUES ('$ordre', '$actif', '$pid', '$type_id', '$langue', '$valeurs', '$valeurs_keywords')
			");
			### db($U);
			$element_id = $U->id;
			js("
				var actionWin = parent.window;
				actionWin.redir();
			");
		}
		else {
			$U =& new Q("UPDATE cms_pages_elements SET actif='$actif', valeurs='$valeurs', motcle='$valeurs_keywords' WHERE id='$element_id' LIMIT 1");
			### db($U);
			js("
				var actionWin = parent.window;
				actionWin.Effect.Pulsate('PUBLIER');
				actionWin.$('actif_".$element_id."').checked = ".($actif == '1' ? 'true' : 'false').";
				new Effect.Highlight(actionWin.$('tr_element_".$element_id."'));
				actionWin.elementsEditChange = false;
				actionWin.getCmsPreview(".$E->V[0]['pid'].", ".$element_id.");	
			");
		}
	break;
	
}
?>