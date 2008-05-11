<? 
require_once('../menu/menu.php');
require_once('data.php');

$rub_id = false; // MODE "RUBRIQUE" : false | intval(gpc('rub_id'))
$cat_id = 0;//intval(gpc('cat_id'));
$id = 1; //intval(gpc('id'));
$child = intval(gpc('child'));
$action = clean(gpc('action'));
$mode = clean(gpc('mode'));

switch ($mode) {
	case 'fiche' :
	if (!$rub_id || $rub_id > 0) { // $RO -> RUB // $R1 -> CAT // $R2 -> PROD
		if ($cat_id > 0) { // $R1 -> CAT // $R2 -> PROD
			switch($child) {
				case '1' : $A = new FICHE($R2,$R2_data,$id); break;
				case '2' : $A = new FICHE($R3,$R3_data,$id); break;
				case '3' : $A = new FICHE($R4,$R4_data,$id); break;
				case '4' : $A = new FICHE($R5,$R5_data,$id); break;
				case '5' : $A = new FICHE($R6,$R6_data,$id); break;
				case '6' : $A = new FICHE($R7,$R7_data,$id); break;
				case '7' : $A = new FICHE($R8,$R8_data,$id); break;
				case '8' : $A = new FICHE($R9,$R9_data,$id); break;
				default : $A = new FICHE($R2,$R2_data,$id); break;
			}
		}
		else $A = new FICHE($R1,$R1_data,$id);
	}
	else $A = new FICHE($R0,$R0_data,$id);
	$A->rub_id = $rub_id;
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createFICHE();
	break;
	
	case 'bdd' :
	if (!$rub_id || $rub_id > 0) {
		if ($cat_id > 0) {
			
			switch($child) {
				case '1' : $A = new BDD($R2,$R2_data,$action,$id); break;
				case '2' : $A = new BDD($R3,$R3_data,$action,$id); break;
				case '3' : $A = new BDD($R4,$R4_data,$action,$id); break;
				case '4' : $A = new BDD($R5,$R5_data,$action,$id); break;
				case '5' : $A = new BDD($R6,$R6_data,$action,$id); break;
				case '6' : $A = new BDD($R7,$R7_data,$action,$id); break;
				case '7' : $A = new BDD($R8,$R8_data,$action,$id); break;
				case '8' : $A = new BDD($R9,$R9_data,$action,$id); break;
				default : $A = new BDD($R2,$R2_data,$action,$id); break;
			}
		}
		else $A = new BDD($R1,$R1_data,$action,$id);
	}
	else $A = new BDD($R0,$R0_data,$action,$id);
	$A->rub_id = $rub_id;
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createBDD();
	break;
	
	default : // 'liste'
	
	$A = new FICHE($R1,$R1_data,$id);
	$A->rub_id = $rub_id;
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createFICHE();
	
	/*if (!$rub_id || $rub_id > 0) {
		if ($cat_id > 0) {
			switch($child) {
				case '1' : $A = new LISTE($R2,$R2_data,$id); break;
				case '2' : $A = new LISTE($R3,$R3_data,$id); break;
				case '3' : $A = new LISTE($R4,$R4_data,$id); break;
				case '4' : $A = new LISTE($R5,$R5_data,$id); break;
				case '5' : $A = new LISTE($R6,$R6_data,$id); break;
				case '6' : $A = new LISTE($R7,$R7_data,$id); break;
				case '7' : $A = new LISTE($R8,$R8_data,$id); break;
				case '8' : $A = new LISTE($R9,$R9_data,$id); break;
				default : $A = new LISTE($R2,$R2_data,$id); break;
			}
		}
		else $A = new LISTE($R1,$R1_data,$id);
	}
	else $A = new LISTE($R0,$R0_data,$id);
	$A->rub_id = $rub_id;
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createLISTE();*/
	break;

}

require_once("../menu/menu_bas.php");
?>