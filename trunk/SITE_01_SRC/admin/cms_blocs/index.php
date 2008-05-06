<?  include_once("../menu/menu.php"); ?><?
include_once("data.php");

$cat_id = intval($_GET['cat_id']);
$id = intval($_GET['id']);
$child = intval($_GET['child']);
$action = $_GET['action'];
$mode = $_GET['mode'];

switch ($mode) { // $R1 -> CAT // $RX -> SSCAT
	case 'fiche' :
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new FICHE($R2,$R2_data,$id); break;
			case '2' : $A = new FICHE($R3,$R3_data,$id); break;
			case '3' : $A = new FICHE($R4,$R4_data,$id); break;
			case '4' : $A = new FICHE($R5,$R5_data,$id); break;
			default : $A = new FICHE($R2,$R2_data,$id); break;
		}
	}
	else $A = new FICHE($R1,$R1_data,$id);
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createFICHE();
	break;
	
	case 'bdd' :
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new BDD($R2,$R2_data,$action,$id); break;
			case '2' : $A = new BDD($R3,$R3_data,$action,$id); break;
			case '3' : $A = new BDD($R4,$R4_data,$action,$id); break;
			case '4' : $A = new BDD($R5,$R5_data,$action,$id); break;
			default : $A = new BDD($R2,$R2_data,$action,$id); break;
		}
	}
	else $A = new BDD($R1,$R1_data,$action,$id);
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createBDD();
	break;
	
	default : // 'liste'
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new LISTE($R2,$R2_data,$id); break;
			case '2' : $A = new LISTE($R3,$R3_data,$id); break;
			case '3' : $A = new LISTE($R4,$R4_data,$id); break;
			case '4' : $A = new LISTE($R5,$R5_data,$id); break;
			default : $A = new LISTE($R2,$R2_data,$id); break;
		}
	}
	else $A = new LISTE($R1,$R1_data,$id);
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createLISTE();
	break;
}

?><? include_once("../menu/menu_bas.php"); ?>