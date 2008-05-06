<? 

// Ajax Call
require('../lib/racine.php');
require_once('cms_fonctions.php');

setIsoHeader();

$action = gpc('action');
$rubrique_id = intval(gpc('id'));
$element_id = intval(gpc('element_id'));
$element_type_id = intval(gpc('element_type_id'));
$element_langue = gpc('element_langue');
if (empty($element_langue)) $element_langue = $_SESSION[SITE_CONFIG]['element_langue'];

$actif = gpc('actif');
$elements_liste_change = gpc('elements_liste_change');

switch($action) {
	
	case 'create_next_element' :
		if ($rubrique_id < 1 || $element_type_id < 1 || $element_langue == '') break;
		$E =& new Q("SELECT ordre FROM cms_pages_elements WHERE pid='$rubrique_id' ORDER BY ordre DESC LIMIT 1");
		$element_next_ordre = intval($E->V[0]['ordre']) + 10;
		$E =& new Q("
			INSERT INTO cms_pages_elements (id, ordre,  actif, pid, type_id, langue)
			VALUES ('', '$element_next_ordre', '0', '$rubrique_id', '$element_type_id', '$element_langue')
		");
		echo(intval($E->id));
	break;
	
	case 'actif_element' :	
		if ($element_id < 1 || ($actif != 0 && $actif != 1)) break;
		$E =& new Q("UPDATE cms_pages_elements SET actif='$actif' WHERE id='$element_id' LIMIT 1");
		echo $actif;
	break;
	
	case 'efface_element' :	
		if ($element_id < 1) break;
		$E =& new Q("DELETE FROM cms_pages_elements WHERE id='$element_id' LIMIT 1");
		echo 1;
	break;
	
	case 'order_element' :	
		if (count($_GET['element_id']) < 1) break;
		$ordre = 10;
		foreach((array)$_GET['element_id'] as $element_id) {
			$E =& new Q("UPDATE cms_pages_elements SET ordre='$ordre' WHERE id='$element_id' LIMIT 1");
			$ordre += 10;
		}
		echo 1;
	break;
	
	case 'build_page' :
		if ($rubrique_id < 1) break;
		if ($elements_liste_change == 1) { // Save liste order
			if (count($_GET['element_id']) > 0) {
				$ordre = 10;
				foreach((array)$_GET['element_id'] as $element_id) {
					$E =& new Q("UPDATE cms_pages_elements SET ordre='$ordre' WHERE id='$element_id' LIMIT 1");
					$ordre += 10;
				}
			}
		}
		// Publish...
		if (!is_dir($wwwRoot.'cache/')) createRep($wwwRoot.'cache/', '777');
		writeFile($wwwRoot.'cache/page_rid_'.$rubrique_id.'_'.$element_langue.'.html', getCmsHtml($rubrique_id, 0, $element_langue));
		echo 1;
	break;
}
?>